<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ReceiveEquip\Service;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class ReceiveEquipService implements ReceiveEquipServiceInterface
{
    public function submit(array $data)
    {
        $receiveEquip                      = R::dispense('receiveEquip');
        $receiveEquip->school_id           = $data['school_id'];
        $receiveEquip->comments            = $data['comments'];
        $receiveEquip->submitted           = time();
        $receiveEquip->submitted_by        = $data['submitted_by'];
        $items                        = [];
        foreach ($data['items'] as $itemData) {
            $item                  = R::dispense('applicationformitem');
            $item->lab_id          = $itemData['lab_id'];
            $item->itemcategory_id = $itemData['itemcategory_id'];
            $item->qty             = $itemData['qty'];
            $item->qtyacquired     = $itemData['qtyacquired'];
            $item->reasons         = $itemData['reasons'];
            $items[]               = $item;
        }
        if (!empty($items)) {
            $appForm->ownApplicationformitemList = $items;
        }

        R::store($appForm);

        return $this->exportApplicationForm($appForm);
    }

    public function findSchoolReceiveEquip($schoolId)
    {
        $appForm = R::findOne('applicationform', ' school_id = ? ORDER BY id DESC', [$schoolId]);
        if (null === $appForm) {
            return;
        }

        return $this->exportApplicationForm($appForm);
    }

    private function exportApplicationForm(OODBBean $bean)
    {
        $receiveEquip          = $bean->export();
        $receiveEquip['items'] = array_map(function ($itemBean) {
            return array_merge($itemBean->export(), [
                'itemcategory' => $itemBean->itemcategory->name,
                'version'      => $itemBean->itemcategory->groupflag,
            ]);
        }, $bean->ownReceiveequipitemList);

        return $receiveEquip;
    }
}
