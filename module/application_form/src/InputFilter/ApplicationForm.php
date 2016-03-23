<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ApplicationForm\InputFilter;

use GrEduLabs\ApplicationForm\Service\ApplicationFormServiceInterface;
use GrEduLabs\Schools\Service\SchoolServiceInterface;
use Zend\Filter;
use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class ApplicationForm extends InputFilter
{
    public function __construct(
        ApplicationFormServiceInterface $appFormService,
        SchoolServiceInterface $schoolService,
        CollectionInputFilter $itemsInputFilter
    ) {
        $schoolId = new Input('school_id');
        $schoolId->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());
        $schoolId->getValidatorChain()
            ->attach(new Validator\NotEmpty());

        $comments = new Input('comments');
        $comments->setRequired(false)
            ->getFilterChain()
            ->attach(new Filter\StripTags())
            ->attach(new Filter\StringTrim());

        $submittedBy = new Input('submitted_by');
        $submittedBy->setRequired(true)
            ->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\EmailAddress([
                'useDomainCheck' => false,
            ]));

        $this->add($schoolId)
            ->add($comments)
            ->add($submittedBy)
            ->add($itemsInputFilter, 'items');
    }
}
