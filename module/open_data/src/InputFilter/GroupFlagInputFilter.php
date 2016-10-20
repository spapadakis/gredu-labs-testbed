<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
namespace GrEduLabs\OpenData\InputFilter;

use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use RedBeanPHP\R;

class GroupFlagInputFilter extends InputFilter
{

    public function __construct()
    {
        $groupflag = new Input('group');

        $groupflag->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\ToInt());
        $groupflag->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\GreaterThan(['min' => 0, 'inclusive' => true]))
            ->attach(new Validator\Callback([
                'message' => 'Το group δεν είναι έγκυρο',
                'callback' => function ($value) {
                    $exists = R::getCol('select distinct groupflag from itemcategory where groupflag = :groupflag', [':groupflag' => $value]);
                    if (!$exists) {
                        return false;
                    } else {
                        return true;
                    }
                }
            ]));

        $this->add($groupflag);
    }
}
    