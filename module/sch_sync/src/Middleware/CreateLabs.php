<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchSync\Middleware;

use Exception;
use GrEduLabs\Schools\Service\AssetServiceInterface;
use GrEduLabs\Schools\Service\LabServiceInterface;
use GrEduLabs\Schools\Service\SchoolServiceInterface;
use Psr\Log\LoggerInterface;
use RedBeanPHP\R;
use SchInventory\ServiceInterface as InventoryService;
use Slim\Http\Request;
use Slim\Http\Response;
use Zend\Authentication\AuthenticationServiceInterface;

class CreateLabs
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
     *
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LabServiceInterface $labService,
        AssetServiceInterface $assetService,
        InventoryService $inventoryService,
        SchoolServiceInterface $schoolService,
        AuthenticationServiceInterface $authService,
        LoggerInterface $logger
    ) {
        $this->labService       = $labService;
        $this->assetService     = $assetService;
        $this->inventoryService = $inventoryService;
        $this->schoolService    = $schoolService;
        $this->authService      = $authService;
        $this->logger           = $logger;
    }

    public function __invoke(Request $req, Response $res, callable $next)
    {
        $res = $next($req, $res);

        $identity = $this->authService->getIdentity();
        if (null === $identity) {
            return $res;
        }
        $user = R::load('user', $identity->id);
        if (!$user->school_id) {
            return $res;
        }

        $school_id = $user->school_id;
        $school    = $this->schoolService->getSchool($school_id);

        if (0 < count($this->labService->getLabsBySchoolId($school_id))) {
            return $res;
        }
        try {
            $equipment = $this->inventoryService->getUnitEquipment($school['registry_no']);
        } catch (Exception $e) {
            $this->logger->error(sprintf('Problem retrieving assets from inventory for school %s', $school_id));
            $this->logger->debug('Exception', [$e->getMessage(), $e->getTraceAsString()]);

            return $res;
        }
        $labTypes  = array_reduce($this->labService->getLabTypes(), function ($map, $type) {
            $map[trim($type['name'])] = $type['id'];

            return $map;
        }, []);
        $assetTypes = array_reduce($this->assetService->getAllItemCategories(), function ($map, $type) {
            $map[trim($type['name'])] = $type['id'];

            return $map;
        }, []);

        try {
            $locations = array_reduce($equipment, function ($uniq, $item) use ($school_id, $labTypes, $assetTypes) {
                if (!isset($uniq[$item['location.id']])) {
                    $locationName = $item['location.name'];
                    $detected = reset(array_filter(array_keys($labTypes), function ($type) use ($locationName) {

                        return false !== stripos($locationName, $type) ||
                                false !== stripos($type, $locationName);
                    }));
                    $labType = $detected ? $labTypes[$detected] : end($labTypes);
                    $data = [
                        'school_id'  => (int) $school_id,
                        'name'       => $locationName,
                        'labtype_id' => (int) $labType,
                    ];
                    $lab = R::dispense('lab');
                    $lab->import($data);
                    $uniq[$item['location.id']] = $lab;
                }

                $categoryName = $item['item_template.category.name'];

                $type = reset(array_filter(array_keys($assetTypes), function ($type) use ($categoryName) {
                    return $type == $categoryName;
                }));
                $type = ($type) ? $assetTypes[$type] : false;

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
            R::storeAll($locations);
            $this->logger->info(sprintf('Add assets from inventory for school %s', $school_id));
        } catch (Exception $e) {
            $this->logger->error(sprintf('Problem inserting assets for school %s in database', $school_id));
            $this->logger->debug('Exception', [$e->getMessage(), $e->getTraceAsString()]);
        }

        return $res;
    }
}
