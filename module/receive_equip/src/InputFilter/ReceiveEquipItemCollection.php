<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ReceiveEquip\InputFilter;

use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\InputFilterInterface;

class ReceiveEquipItemCollection extends CollectionInputFilter
{
    public function __construct(InputFilterInterface $itemInputFilter)
    {
        $this->setInputFilter($itemInputFilter);
    }
}
