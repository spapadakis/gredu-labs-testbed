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
use GrEduLabs\Schools\Service\AssetServiceInterface;
use GrEduLabs\Schools\Service\LabServiceInterface;
use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class SchoolAsset
{
    use InputFilterTrait;

    public function __construct(
        LabServiceInterface $labService,
        AssetServiceInterface $assetsService
    ) {
        $id = new Input('id');
        $id->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\Digits());

        $itemCategoryId = new Input('itemcategory_id');
        $itemCategoryId->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Callback([
                'callback' => function ($value) use ($assetsService) {
                    try {
                        $itemCategory = $assetsService->getItemCategoryById($value);

                        return isset($itemCategory['id']) && $itemCategory['id'] == $value;
                    } catch (Exception $ex) {
                        return false;
                    }
                },
                'message' => 'Ο τύπος δεν βρέθηκε',
            ]));

        $labId = new Input('lab_id');
        $labId->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());
        $labId->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Callback([
                'callback' => function ($value) use ($labService) {
                    try {
                        $lab = $labService->getLabById($value);

                        return isset($lab['id']) && $lab['id'] == $value;
                    } catch (Exception $ex) {
                        return false;
                    }
                },
                'message' => 'Το εργαστήριο δεν βρέθηκε',
            ]));

        $qty = new Input('qty');
        $qty->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\Digits())
            ->attach(new Filter\ToInt());
        $qty->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\GreaterThan(['min' => 0]));

        $acquisitionYear = new Input('acquisition_year');
        $acquisitionYear->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\Digits());
        $acquisitionYear->getValidatorChain()
            ->attach(new Validator\Date([
                'format' => 'Y',
            ]))
            ->attach(new Validator\LessThan([
                'max'       => date('Y'),
                'inclusive' => true,
            ]));

        $comments = new Input('comments');
        $comments->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());

        $this->inputFilter = new InputFilter();
        $this->inputFilter
            ->add($id)
            ->add($labId)
            ->add($itemCategoryId)
            ->add($qty)
            ->add($acquisitionYear)
            ->add($comments)
                ;
    }
}
