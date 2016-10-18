<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
namespace GrEduLabs\OpenData\InputFilter;

use Zend\Filter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class PagerInputFilter extends InputFilter
{

    private $maxpagesize = 0;

    public function __construct($maxpagesize)
    {

        $this->maxpagesize = intval($maxpagesize);

        $pagenum = new Input('page');
        $pagesize = new Input('pagesize');

        $pagenum->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());
        $pagenum->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\GreaterThan(['min' => 0]));

        $pagesize->setRequired(true)
            ->getFilterChain()
            ->attach(new Filter\ToInt());
        $pagesize->getValidatorChain()
            ->attach(new Validator\NotEmpty())
            ->attach(new Validator\Between([
                'min' => 1,
                'max' => $this->maxpagesize,
                'inclusive' => true
        ]));

        $this->add($pagenum)
            ->add($pagesize);
    }
}
