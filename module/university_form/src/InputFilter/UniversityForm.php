<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\UniversityForm\InputFilter;

use GrEduLabs\UniversityForm\Service;
use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class UniversityForm extends InputFilter 
{
 
    public function __construct()
    {
       

       
        $idrima = new Input('idrima');
        $idrima->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());
        $idrima->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 3]));

        $sxolh = new Input('sxolh');
        $sxolh->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());
        $sxolh->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 3]));
    

        $tmhma = new Input('tmhma');
        $tmhma->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());
        $tmhma->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 3]));
   

        $person = new Input('person');
        $person->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StringTrim());
        $person->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\StringLength(['min' => 3]));


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


       $comments = new Input('comments');
                $comments->setRequired(false)
                    ->getFilterChain()
                    ->attach(new Filter\StringTrim());


        $this ->add($idrima)
            ->add($sxolh)
            ->add($tmhma)
            ->add($person)
            ->add($telef)
            ->add($email)
            ->add($comments);
    }
}
