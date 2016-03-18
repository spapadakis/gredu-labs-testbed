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

class Teacher
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

        $surname = new Input('surname');
        $surname->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());
        $surname->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 3]));

        $email = new Input('email');
        $email->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\EmailAddress([
                'useDomainCheck' => false,
            ]));

        $telephone = new Input('telephone');
        $telephone->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\Digits());
        $telephone->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 10]));

        $branch_id = new Input('branch_id');
        $branch_id->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Digits());

        $is_principle = new Input('is_principle');
        $is_principle->setRequired(false);

        $is_responsible = new Input('is_responsible');
        $is_responsible->setRequired(false);


        $this->inputFilter = new InputFilter();
        $this->inputFilter
            ->add($id)
            ->add($name)
            ->add($surname)
            ->add($email)
            ->add($telephone)
            ->add($branch_id)
            ->add($is_principle)
            ->add($is_responsible);
    }
}
