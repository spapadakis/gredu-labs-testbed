<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\InputFilter;

use Exception;
use GrEduLabs\Schools\Service\SchoolServiceInterface;
use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class School
{
    use InputFilterTrait;

    public function __construct(
        SchoolServiceInterface $schoolService
    ) {
        $id = new Input('id');
        $id->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\Digits());

        $registryNo = new Input('registry_no');
        $registryNo->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Callback([
                'callback' => function ($value, $context = []) use ($schoolService) {

                    $id = isset($context['id']) ? $context['id'] : false;
                    if (false === $id) {
                        throw new Exception('No id in context');
                    }

                    try {
                        $school = $schoolService->findSchoolByRegistryNo($value);
                        if (!$school || (isset($school['id']) && !$school['id'])) {
                            return true;
                        }

                        return $school['id'] == $id;
                    } catch (Exception $ex) {
                        return false;
                    }
                },
                'messageTemplate' => 'Το σχολείο με κωδικό %value% υπάρχει ήδη',
            ]));

        $name = new Input('name');
        $name->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $name->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $streetAddress = new Input('street_address');
        $streetAddress->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());

        $postalCode = new Input('postal_code');
        $postalCode->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\Digits());

        $phoneNumber = new Input('phone_number');
        $phoneNumber->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\Digits());

        $faxNumber = new Input('fax_number');
        $faxNumber->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\Digits());

        $email = new Input('email');
        $email->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\EmailAddress([
                'useDomainCheck' => false,
            ]));

        $municipality = new Input('municipality');
        $municipality->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());

        $schoolTypeId = new Input('schooltype_id');
        $schoolTypeId->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());

        $prefectureId = new Input('prefecture_id');
        $prefectureId->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());

        $educationlevelId = new Input('educationlevel_id');
        $educationlevelId->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());

        $eduadminId = new Input('eduadmin_id');
        $eduadminId->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\ToInt());

        $creator = new Input('creator');
        $creator->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\EmailAddress([
                'useDomainCheck' => false,
            ]));

        $this->inputFilter = new InputFilter();
        $this->inputFilter
            ->add($id)
            ->add($registryNo)
            ->add($name)
            ->add($streetAddress)
            ->add($postalCode)
            ->add($phoneNumber)
            ->add($faxNumber)
            ->add($email)
            ->add($municipality)
            ->add($schoolTypeId)
            ->add($prefectureId)
            ->add($educationlevelId)
            ->add($eduadminId)
            ->add($creator);
    }
}
