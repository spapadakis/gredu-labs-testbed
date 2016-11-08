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

        $eidikothta = new Input('eidikothta');
        $eidikothta->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Digits());
    
        $arithmhtroou = new Input('arithmhtroou');
        $arithmhtroou->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());
        $arithmhtroou->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 6]));

        $telef = new Input('telef');
        $telef->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\Digits());
        $telef->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 10]));

        
        $email = new Input('email');
        $email->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\EmailAddress([
                'useDomainCheck' => false,
            ]));

      
        $school = new Input('school');
        $school->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());
        $school->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 3]));
     


        $schooltelef = new Input('schooltelef');
        $schooltelef->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\Digits());
        $schooltelef->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 10]));


        //$this->inputFilter = new InputFilter();
        $this->add($name)
            ->add($surname)
            ->add($eidikothta)
            ->add($arithmhtroou)
            ->add($telef)
            ->add($email)
            ->add($school)
            ->add($schooltelef);
    }
}
