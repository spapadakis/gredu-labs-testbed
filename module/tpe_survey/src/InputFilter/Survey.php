<?php

/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\TpeSurvey\InputFilter;

use GrEduLabs\Schools\InputFilter\InputFilterTrait;
use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class Survey
{
    use InputFilterTrait;

    public function __construct()
    {
        $id = new Input('id');
        $id->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\Digits());

        $alreadyUsing_tpe = new Input('already_using_tpe');
        $alreadyUsing_tpe->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $alreadyUsing_tpe->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $knowledgeLevel = new Input('knowledge_level');
        $knowledgeLevel->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $knowledgeLevel->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $assetsInUse = new Input('assets_in_use');
        $assetsInUse->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());

        $swWeb2 = new Input('sw_web2');
        $swWeb2->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $swWeb2->getValidatorChain()
            ->attach(new Validator\StringLength([
                'max' => '191',
            ]));

        $swPackages = new Input('sw_packages');
        $swPackages->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $swPackages->getValidatorChain()
            ->attach(new Validator\StringLength([
                'max' => '191',
            ]));

        $swDigitalschool = new Input('sw_digitalschool');
        $swDigitalschool->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $swDigitalschool->getValidatorChain()
            ->attach(new Validator\StringLength([
                'max' => '191',
            ]));

        $swOther = new Input('sw_other');
        $swOther->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $swOther->getValidatorChain()
            ->attach(new Validator\StringLength([
                'max' => '191',
            ]));

        $ucEduprograms = new Input('uc_eduprograms');
        $ucEduprograms->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $ucEduprograms->getValidatorChain()
            ->attach(new Validator\StringLength([
                'max' => '191',
            ]));

        $ucDigitaldesign = new Input('uc_digitaldesign');
        $ucDigitaldesign->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $ucDigitaldesign->getValidatorChain()
            ->attach(new Validator\StringLength([
                'max' => '191',
            ]));

        $ucAsyncedu = new Input('uc_asyncedu');
        $ucAsyncedu->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $ucAsyncedu->getValidatorChain()
            ->attach(new Validator\StringLength([
                'max' => '191',
            ]));

        $ucOther = new Input('uc_other');
        $ucOther->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $ucOther->getValidatorChain()
            ->attach(new Validator\StringLength([
                'max' => '191',
            ]));

        $eduFieldsCurrent = new Input('edu_fields_current');
        $eduFieldsCurrent->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());

        $eduFieldsFuture = new Input('edu_fields_future');
        $eduFieldsFuture->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());

        $eduFieldsFutureSyncType = new Input('edu_fields_future_sync_type');
        $eduFieldsFutureSyncType->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\ToInt());

        $eduFieldsFutureASyncType = new Input('edu_fields_future_async_type');
        $eduFieldsFutureASyncType->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\ToInt());


        $extraNeeds = new Input('extra_needs');
        $extraNeeds->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());

        $this->inputFilter = new InputFilter();
        $this->inputFilter
            ->add($id)
            ->add($alreadyUsing_tpe)
            ->add($knowledgeLevel)
            ->add($assetsInUse)
            ->add($swWeb2)
            ->add($swPackages)
            ->add($swDigitalschool)
            ->add($swOther)
            ->add($ucEduprograms)
            ->add($ucDigitaldesign)
            ->add($ucAsyncedu)
            ->add($ucOther)
            ->add($eduFieldsCurrent)
            ->add($eduFieldsFuture)
            ->add($eduFieldsFutureSyncType)
            ->add($eduFieldsFutureASyncType)
            ->add($extraNeeds);
    }
}
