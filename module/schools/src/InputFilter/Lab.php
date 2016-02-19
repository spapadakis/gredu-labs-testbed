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

class Lab
{
    use InputFilterTrait;

    public function __construct()
    {
        $id = new Input('id');
        $id->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\Digits());

        $name = new Input('name');
        $name->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());
        $name->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 3]));

        $type = new Input('type');
        $type->setRequired(true);
        $type->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $responsible = new Input('responsible');
        $responsible->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\Digits());

        $area = new Input('area');
        $area->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Digits());

        $lessons = new Input('lessons');
        $lessons->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $attachment = new Input('attachment');
        $attachment->setRequired(false);

        $use_ext_program= new Input('use_ext_program');
        $use_ext_program->setRequired(false);

        $use_in_program = new Input('use_in_program');
        $use_in_program->setRequired(false);

        $has_server = new Input('has_server');
        $has_server->setRequired(false);

        $has_network = new Input('has_network');
        $has_network->setRequired(false);


        $this->inputFilter = new InputFilter();
        $this->inputFilter
            ->add($id)
            ->add($name)
            ->add($type)
            ->add($responsible)
            ->add($area)
            ->add($lessons)
            ->add($attachment)
            ->add($use_in_program)
            ->add($use_ext_program)
            ->add($has_server)
            ->add($has_network);
    }
}
