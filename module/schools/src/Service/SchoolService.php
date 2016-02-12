<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Service;

use RedBeanPHP\R;

class SchoolService implements SchoolServiceInterface
{
    public function createSchool(array $data)
    {
        $school   = R::dispense('school');
        $required = ['registry_no', 'name', 'municipality','schooltype_id', 'prefecture_id',
                     'educationlevel_id', 'eduadmin_id', 'created', 'creator', ];
        $optional = ['street_address', 'postal_code', 'phone_number', 'fax_number', 'email'];
        foreach ($required as $value) {
            if (array_key_exists($value, $data)) {
                $school[$value] = $data[$value];
            } else {
                return -1;
            }
        }
        foreach ($optional as $value) {
            if (array_key_exists($value, $data)) {
                $school[$value] = $data[$value];
            } else {
                $school[$value] = '';
            }
        }
        $id = R::store($school);

        return $id;
    }
    public function getSchool($id)
    {
        $school = R::load('school', $id);

        return $school;
    }
}
