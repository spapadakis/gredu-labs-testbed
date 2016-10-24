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

use InvalidArgumentException;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class ReceiveEquipService implements ReceiveEquipServiceInterface
{

public function __construct($logger) {
  $this->logger = $logger;
}

    public function submit(array $data, $receivedDocumentFileName)
    {
/*      $this->logger->info(sprintf(
          'application id = %s',
          $data['id']
      )); */
        $receiveEquip = $this->findById($data['id']);

        $receiveEquip->received_by      = $data['submitted_by'];
        $receiveEquip->received_ts      = date('Y-m-d G:i:s');
        $receiveEquip->received_document = $receivedDocumentFileName;

        $items = $receiveEquip->ownApplicationformitemList;
        $dataItems = $data['items'];
        $dataItemsLength = count($dataItems);
        foreach ($items as $item) {
          for ($i=0; $i<$dataItemsLength; $i++) {
            if ((int) $item['id'] === (int) $dataItems[$i]['id']) {
              $item['qtyreceived'] = (int) $dataItems[$i]['qtyreceived'];
              break;
            }
          }
        }
        $receiveEquip->ownApplicationformitemList = $items;

        R::store($receiveEquip);

        return $this->exportReceiveEquip($receiveEquip);
    }

    public function findSchoolReceiveEquip($schoolId)
    {
        $receiveEquip = R::findOne('applicationform', ' school_id = ? AND approved = 1 ORDER BY id DESC', [$schoolId]);
        if (null === $receiveEquip) {
            return null;
        }

        return $this->exportReceiveEquip($receiveEquip);
    }

    public function findById($id)
    {
        $receiveEquip = R::findOne('applicationform', ' id = ? ', [(int)$id]);
        if (!$receiveEquip) {
            throw new InvalidArgumentException('Application Form not found. Cannot proceed to receive');
        }
        return $receiveEquip;
    }

    private function exportReceiveEquip(OODBBean $bean)
    {
        $receiveEquip          = $bean->export();
        $receiveEquip['items'] = array_map(function ($itemBean) {
            return array_merge($itemBean->export(), [
                'itemcategory' => $itemBean->itemcategory->name,
                'version'      => $itemBean->itemcategory->groupflag,
            ]);
        }, $bean->ownApplicationformitemList);

        return $receiveEquip;
    }
}
