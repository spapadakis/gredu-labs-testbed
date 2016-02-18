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
        $school          = $this->importSchool(R::dispense('school'), $data);
        $school->created = time();
        R::store($school);

        return $this->exportSchool($school);
    }
    public function getSchool($id)
    {
        $school = R::load('school', $id);

        return $this->exportSchool($school);
    }

    public function findSchoolByRegistryNo($registryNo)
    {
        $school = R::findOne('school', ' registry_no = ? ', [$registryNo]);
        if (null === $school) {
            return;
        }

        return $this->exportSchool($school);
    }

    private function exportSchool($bean)
    {
        return $bean->export();
    }

    private function importSchool($bean, array $data)
    {
        $bean->registry_no        = $data['registry_no'];
        $bean->name               = $data['name'];
        $bean->street_address     = $data['street_address'];
        $bean->postal_code        = $data['postal_code'];
        $bean->phone_number       = $data['phone_number'];
        $bean->fax_number         = $data['fax_number'];
        $bean->email              = $data['email'];
        $bean->municipality       = $data['municipality'];
        $bean->schooltype_id      = $data['schooltype_id'];
        $bean->prefecture_id      = $data['prefecture_id'];
        $bean->educationlevel_id  = $data['educationlevel_id'];
        $bean->eduadmin_id        = $data['eduadmin_id'];
        $bean->creator            = $data['creator'];

        return $bean;
    }
}
