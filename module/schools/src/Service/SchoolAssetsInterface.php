<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Service;

interface SchoolAssetsInterface
{
    public function getAssetsForSchool($school_id, array $filters = []);
    public function updateAssetForSchool($schoo_id, array $assetData, $assetId);
    public function addAssetForSchool($school_id, array $assetData);
    public function removeAssetFromSchool($school_id, $asset_id);
}
