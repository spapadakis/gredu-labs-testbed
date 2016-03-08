<?php

/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
namespace GrEduLabs\TpeSurvey\Service;

use RedBeanPHP\R;


class SurveyService implements SurveyServiceInterface
{
    public function getAnswers($teacherId)
    {
        $bean                  = R::findOne('tpesurvey', 'teacher_id = ?', [$teacherId]);
        $data                  = $bean->export();
        $data['assets_in_use'] = explode('|', $data['assets_in_use']);

        return $data;
    }

    public function saveAnswers($teacherId, array $data)
    {
        if (isset($data['assets_in_use'])) {
            $data['assets_in_use'] = implode('|', $data['assets_in_use']);
        }
        $bean = R::findOne('tpesurvey', 'teacher_id = ?', [$teacherId]);
        if (null === $bean) {
            $bean = R::dispense('tpesurvey');
        }
        $bean->teacher_id = (int) $teacherId;
        $bean->import($data, [
            'knowledge_level',
            'assets_in_use',
            'sw_web2',
            'sw_packages',
            'sw_digitalschool',
            'sw_other',
            'uc_eduprograms',
            'uc_digitaldesign',
            'uc_asyncedu',
            'uc_other',
        ]);
        R::store($bean);
    }
}
