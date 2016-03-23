<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ApplicationForm\Service;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class ApplicationFormService implements ApplicationFormServiceInterface
{
    public function submit(array $data)
    {
        $appForm                      = R::dispense('applicationform');
        $appForm->school_id           = $data['school_id'];
        $appForm->comments            = $data['comments'];
        $appForm->submitted           = time();
        $appForm->submitted_by        = $data['submitted_by'];
        $items                        = [];
        foreach ($data['items'] as $itemData) {
            $item                  = R::dispense('applicationformitem');
            $item->lab_id          = $itemData['lab_id'];
            $item->itemcategory_id = $itemData['itemcategory_id'];
            $item->qty             = $itemData['qty'];
            $item->reasons         = $itemData['reasons'];
            $items[]               = $item;
        }
        if (!empty($items)) {
            $appForm->ownApplicationformitemList = $items;
        }

        R::store($appForm);

        return $this->exportApplicationForm($appForm);
    }

    public function findSchoolApplicationForm($schoolId)
    {
        $appForm = R::findOne('applicationform', ' school_id = ? ORDER BY id DESC', [$schoolId]);
        if (null === $appForm) {
            return;
        }

        return $this->exportApplicationForm($appForm);
    }

    private function exportApplicationForm(OODBBean $bean)
    {
        $appForm          = $bean->export();
        $appForm['items'] = array_map(function ($itemBean) {
            return array_merge($itemBean->export(), [
                'lab'          => $itemBean->lab->name,
                'itemcategory' => $itemBean->itemcategory->name,
            ]);
        }, $bean->ownApplicationformitemList);

        return $appForm;
    }
}
