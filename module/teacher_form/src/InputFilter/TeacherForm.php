<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\TeacherForm\InputFilter;

use GrEduLabs\TeacherForm\Service;
use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class TeacherForm extends InputFilter
{


    public function __construct()
    {

        $id = new Input('id');
        $id->setRequired(true)
          ->getFilterChain()
          ->attach(new Filter\ToInt());
        $id->getValidatorChain()
          ->attach(new Validator\NotEmpty());

        $email = new Input('email');
        $email->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\EmailAddress([
                'useDomainCheck' => false,
            ]));

        $telef = new Input('telef');
        $telef->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\Digits());
        $telef->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 10]));

//        $this->inputFilter = new InputFilter();
        $this->add($id)
            ->add($email)
            ->add($telef);
          }
}
