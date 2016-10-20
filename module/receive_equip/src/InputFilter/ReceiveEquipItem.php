<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ReceiveEquip\InputFilter;

use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class ReceiveEquipItem extends InputFilter
{
    public function __construct()
    {
        $itemId = new Input('id');
        $itemId->setRequired(true)
          ->getFilterChain()
          ->attach(new Filter\ToInt());
        $this->add($itemId);


        $qtyreceived = new Input('qtyreceived');
        $qtyreceived->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());
        $qtyreceived->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\GreaterThan([
                'min'       => 0,
                'inclusive' => true,
            ])
          );
        $this->add($qtyreceived);
    }
}
