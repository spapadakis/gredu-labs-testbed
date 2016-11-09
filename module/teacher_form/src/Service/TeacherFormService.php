<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
namespace GrEduLabs\TeacherForm\Service;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;
class TeacherFormService implements TeacherFormServiceInterface
{
    public function submit(array $data, array $reqParams)
    {
        $appForm                      = R::dispense('volunteerteachers');
        $appForm->id                  = $data['id'];
        $appForm->name                = $data['name'];
        $appForm->surname             = $data['surname'];
        $appForm->eidikothta          = $reqParams['eidikothta'];
        $appForm->arithmitroou        = $data['arithmitroou'];
        $appForm->email               = $data['email'];
        $appForm->telef               = trim($data['telef']);
        $appForm->school              = $reqParams['school'];
        $appForm->schooltelef         = trim($data['schooltelef']);
        $appForm->comments            = $reqParams['comments'];
        R::store($appForm);
        return $appForm;
    }
    public function getBranches()
    {
        return array_map(function ($branch) {
            return $branch->export();
        }, R::findAll('branch', 'ORDER BY name ASC'));
    }
}