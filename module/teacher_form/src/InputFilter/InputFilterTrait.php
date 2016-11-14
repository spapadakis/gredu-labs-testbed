<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\UniversityForm\InputFilter;

trait InputFilterTrait
{
    private $inputFillter;

    public function __invoke(array $data)
    {
        $this->inputFilter->setData($data);
        $isValid = $this->inputFilter->isValid();

        return [
            'is_valid' => $isValid,
            'values'   => $isValid ? $this->inputFilter->getValues() : [],
            'messages' => $this->inputFilter->getMessages(),
        ];
    }
}
