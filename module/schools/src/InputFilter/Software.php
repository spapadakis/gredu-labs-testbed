<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\InputFilter;

use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class Software
{
    use InputFilterTrait;

    public function __construct()
    {
        $id = new Input('id');
        $id->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\Digits());

        $softwarecategory_id = new Input('softwarecategory_id');
        $softwarecategory_id->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\Digits());

        $title = new Input('title');
        $title->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());
        $title->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 3]));

        $lab_id = new Input('lab_id');
        $lab_id->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\Digits());


        $vendor = new Input('vendor');
        $vendor->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());


        $url = new Input('url');
        $url->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\Uri([
                'allowRelative' => false,
                'allowAbsolute' => true,
            ]));

        $this->inputFilter = new InputFilter();
        $this->inputFilter
            ->add($id)
            ->add($softwarecategory_id)
            ->add($lab_id)
            ->add($title)
            ->add($vendor)
            ->add($url);
    }
}
