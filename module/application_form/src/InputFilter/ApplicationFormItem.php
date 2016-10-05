<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ApplicationForm\InputFilter;

use GrEduLabs\Schools\Service\AssetServiceInterface;
use GrEduLabs\Schools\Service\LabServiceInterface;
use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class ApplicationFormItem extends InputFilter
{
    public function __construct(
        LabServiceInterface $labService,
        AssetServiceInterface $assetsService
    ) {
        $labId = new Input('lab_id');
        $labId->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());
        $labId->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $itemCategoryId = new Input('itemcategory_id');
        $itemCategoryId->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());
        $itemCategoryId->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Callback([
                'callback' => function ($value) use ($assetsService) {
                    try {
                        $type = $assetsService->getItemCategoryById($value);
                        return $type && $type['id'] == $value;
                    } catch (Exception $ex) {
                        return false;
                    }
                },
                'message' => 'Ο τύπος εξοπλισμού δεν είναι έγκυρος',
            ]));

        $qty = new Input('qty');
        $qty->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());
        $qty->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\GreaterThan([
                'min' => 0,
            ]));

        $qtyacquired = new Input('qtyacquired');
        $qtyacquired->setRequired(true)
                ->getFilterChain()
                ->attach(new Filter\ToInt());
        $qtyacquired->getValidatorChain()
                ->attach(new Validator\NotEmpty())
                ->attach(new Validator\GreaterThan([
                    'min' => 0,
                    'inclusive' => true
                        ]
        ));

        $reasons = new Input('reasons');
        $reasons->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());
        $reasons->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $this->add($labId)
            ->add($itemCategoryId)
            ->add($qty)
            ->add($qtyacquired)
            ->add($reasons);
    }
}
