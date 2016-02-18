<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ApplicationForm\Service;

use RedBeanPHP\R;

class ApplicationFormService implements ApplicationFormServiceInterface
{
    protected static $applyForChoices = [
        'ΠΛΗΡΕΣ ΕΡΓΑΣΤΗΡΙΟ',
        'ΑΝΑΒΑΘΜΙΣΗ ΕΡΓΑΣΤΗΡΙΟΥ',
        'ΚΙΝΗΤΟ ΕΡΓΑΣΤΗΡΙΟ',
    ];

    public function getApplyForChoices()
    {
        return static::$applyForChoices;
    }

    public function submit(array $data)
    {
        $appForm                      = R::dispense('applicationform');
        $appForm->school_id           = $data['school_id'];
        $appForm->apply_for           = $data['apply_for'];
        $appForm->new_lab_perspective = $data['new_lab_perspective'];
        $appForm->comments            = $data['comments'];
    }
}
