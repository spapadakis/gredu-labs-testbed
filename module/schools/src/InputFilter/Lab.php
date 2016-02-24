<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\InputFilter;

use GrEduLabs\Schools\Service\LabServiceInterface;
use Zend\Filter;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class Lab
{
    use InputFilterTrait;

    public function __construct(
        $uploadTmpPath,
        LabServiceInterface $labService
    ) {
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

        $labTypeId = new Input('labtype_id');
        $labTypeId->setRequired(true);
        $labTypeId->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $responsibleId = new Input('responsible_id');
        $responsibleId->setRequired(false)
            ->getValidatorChain()
            ->attach(new Validator\Digits());

        $area = new Input('area');
        $area->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Digits());

        $lessons = new Input('lessons');
        $lessons->setRequired(false);
        $lessons->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $attachment = new FileInput('attachment');
        $attachment->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\File\RenameUpload([
                'target'    => $uploadTmpPath,
                'randomize' => true,
            ]));
        $attachment->getValidatorChain()
            ->attach(new Validator\File\UploadFile());

        $use_ext_program= new Input('use_ext_program');
        $use_ext_program->setRequired(false);

        $use_in_program = new Input('use_in_program');
        $use_in_program->setRequired(false);

        $has_network = new Input('has_network');
        $has_network->setRequired(false);
        $has_network->getValidatorChain()
            ->attach(new Validator\NotEmpty())->attach(new Validator\InArray([
                'haystack' => $labService->getHasNetworkValues(),
            ]));

        $has_server = new Input('has_server');
        $has_server->setRequired(false);
        $has_server->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\InArray([
                'haystack' => $labService->getHasServerValues(),
            ]));


        $this->inputFilter = new InputFilter();
        $this->inputFilter
            ->add($id)
            ->add($name)
            ->add($labTypeId)
            ->add($responsibleId)
            ->add($area)
            ->add($lessons)
            ->add($attachment)
            ->add($use_in_program)
            ->add($use_ext_program)
            ->add($has_server)
            ->add($has_network);
    }
}
