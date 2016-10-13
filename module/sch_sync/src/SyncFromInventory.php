<?php

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchSync;

use Exception;
use GrEduLabs\Schools\Service\AssetServiceInterface;
use GrEduLabs\Schools\Service\LabServiceInterface;
use GrEduLabs\Schools\Service\SchoolServiceInterface;
use Psr\Log\LoggerInterface;
use RedBeanPHP\R;
use SchInventory\ServiceInterface as InventoryService;

class SyncFromInventory
{

    /**
     *
     * @var LabServiceInterface
     */
    protected $labService;

    /**
     *
     * @var AssetServiceInterface
     */
    protected $assetService;

    /**
     *
     * @var InventoryService
     */
    protected $inventoryService;

    /**
     *
     * @var SchoolServiceInterface
     */
    protected $schoolService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @var array
     */
    private $categoryMap;

    public function __construct(
        LabServiceInterface $labService,
        AssetServiceInterface $assetService,
        InventoryService $inventoryService,
        SchoolServiceInterface $schoolService,
        LoggerInterface $logger,
        array $categoryMap = [],
        $version
    ) {
        $this->labService       = $labService;
        $this->assetService     = $assetService;
        $this->inventoryService = $inventoryService;
        $this->schoolService    = $schoolService;
        $this->logger           = $logger;
        $this->categoryMap      = $categoryMap;
        $this->version          = $version;
    }

    public function __invoke($school_id)
    {
        $school = $this->schoolService->getSchool($school_id);
        try {
            /* inventory service called once here. Returns $equipment object */
            $equipment = $this->inventoryService->getUnitEquipment($school['registry_no']);
        } catch (Exception $e) {
            $this->logger->error(sprintf('Problem retrieving assets from inventory for school %s', $school_id));
            $this->logger->debug('Exception', [$e->getMessage(), $e->getTraceAsString()]);

            return false;
        }
        $labTypes   = $this->getLabTypes();
        $assetTypes = $this->getAssetTypes();

        try {
            $locations = $this->getLocations($school_id, $equipment, $labTypes, $assetTypes);
            R::storeAll($locations);
            $this->logger->info(sprintf('Add assets from inventory for school %s', $school_id));

            return $this->labService->getLabsBySchoolId($school_id);
        } catch (Exception $e) {
            $this->logger->error(sprintf('Problem inserting assets for school %s in database', $school_id));
            $this->logger->debug('Exception', [$e->getMessage(), $e->getTraceAsString()]);

            return false;
        }
    }

    private function getLabTypes()
    {
        return array_reduce($this->labService->getLabTypes(), function ($map, $type) {
            $map[trim($type['name'])] = $type['id'];

            return $map;
        }, []);
    }

    private function getAssetTypes()
    {
        return array_reduce($this->assetService->getAllItemCategories($this->version), function ($map, $type) {
            $map[trim($type['name'])] = $type['id'];

            return $map;
        }, []);
    }

    private function getLocations($school_id, array $equipment, array $labTypes, array $assetTypes)
    {
        return array_reduce($equipment, function ($uniq, $item) use ($school_id, $labTypes, $assetTypes) {
            if (!isset($uniq[$item['location.id']])) {
                $locationName = $item['location.name'];
                $locationId = $item['location.id'];

                $lab = R::findOne('lab', ' school_id = ? AND (inventory_key = ? OR name = ?)', [
                    $school_id,
                    $locationId,
                    $locationName,
                ]);

                if (null !== $lab) {
                    return $uniq;
                }

                $detected = reset(array_filter(array_keys($labTypes), function ($type) use ($locationName) {
                    return false !== stripos($locationName, $type) ||
                        false !== stripos($type, $locationName);
                }));
                $labType = $detected ? $labTypes[$detected] : end($labTypes);
                $data = [
                    'school_id'     => (int) $school_id,
                    'name'          => $locationName,
                    'labtype_id'    => (int) $labType,
                    'inventory_key' => (int) $locationId,
                ];
                $lab = R::dispense('lab');
                $lab->import($data);
                $uniq[$item['location.id']] = $lab;
            }

            $categoryName = $item['item_template.category.name'];

            $type = reset(array_filter(array_keys($this->categoryMap), function ($type) use ($categoryName) {
                return $type == $categoryName;
            }));
            $type = ($type) ? $assetTypes[$this->categoryMap[$type]] : false;

            if ($type !== false) {
                if (!isset($uniq[$item['location.id']]->ownSchoolAsset[$type])) {
                    $asset = R::dispense('schoolasset');
                    $asset->school_id = (int) $school_id;
                    $asset->itemcategory_id = (int) $type;
                    $uniq[$item['location.id']]->ownSchoolAsset[$type] = $asset;
                }
                $uniq[$item['location.id']]->ownSchoolAsset[$type]->qty += 1;
            }

            return $uniq;
        }, []);
    }
}
