<?php

namespace GrEduLabs\open_data\Service;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class ODAService implements ODAServiceInterface {

    public function getSchools() {
        $sql = 'SELECT school.registry_no AS registry_no, '
                . ' school.name AS school_name, '
                . ' schooltype.name as school_type, '
                . ' prefecture.name AS prefecture, '
                . ' eduadmin.name AS eduadmin, '
                . ' regioneduadmin.name AS region_edu_admin, '
                . ' educationlevel.name AS education_level '
                . ' FROM school '
                . ' LEFT JOIN eduadmin ON school.eduadmin_id = eduadmin.id '
                . ' LEFT JOIN regioneduadmin ON eduadmin.regioneduadmin_id = regioneduadmin.id '
                . ' LEFT JOIN educationlevel ON school.educationlevel_id = educationlevel.id '
                . ' LEFT JOIN schooltype ON school.schooltype_id = schooltype.id '
                . ' LEFT JOIN prefecture ON school.prefecture_id = prefecture.id '
                . ' GROUP BY school.id '
                . ' ORDER BY school_name';
        $schools = R::getAll($sql);

        return $schools;
    }

    public function getLabs() {
        $sql = 'SELECT lab.id AS id, '
                . ' school.registry_no AS school_registry_no, '
                . ' school.name AS school_name, '
                . ' TRIM(lab.name) AS name, '
                . ' TRIM(labtype.name) AS type, '
                . ' branch.name AS responsible_branch, '
                . ' IF(lab.is_new = 1, "ΝΑΙ", "ΟΧΙ") AS is_new, '
                . ' lab.area AS area, '
                . ' lab.has_network AS has_network, '
                . ' lab.has_server AS has_server, '
                . ' GROUP_CONCAT(lesson.name SEPARATOR ", ") AS lessons, '
                . ' TRIM(lab.use_in_program) AS use_in_program, '
                . ' TRIM(lab.use_ext_program) AS use_ext_program '
                . ' FROM lab '
                . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                . ' LEFT JOIN school ON lab.school_id = school.id '
                . ' LEFT JOIN lab_lesson ON lab_lesson.lab_id = lab.id '
                . ' LEFT JOIN lesson ON lab_lesson.lesson_id = lesson.id '
                . ' LEFT JOIN teacher ON lab.responsible_id = teacher.id '
                . ' LEFT JOIN branch ON branch.id = teacher.branch_id '
                . ' GROUP BY lab.id '
                . ' ORDER BY school_name ';

        $labs = R::getAll($sql);

        return $labs;
    }

    public function getAssets() {

        $sql = 'SELECT TRIM(itemcategory.name) AS category, '
                . ' schoolasset.qty AS qty, '
                . ' schoolasset.acquisition_year AS acquisition_year, '
                . ' lab.id AS lab_id, '
                . ' TRIM(labtype.name) AS lab_type, '
                . ' school.registry_no AS school_registry_no, '
                . ' school.name AS school_name, '
                . ' schoolasset.comments AS comments '
                . ' FROM schoolasset '
                . ' LEFT JOIN itemcategory ON schoolasset.itemcategory_id = itemcategory.id '
                . ' LEFT JOIN school ON schoolasset.school_id = school.id '
                . ' LEFT JOIN lab ON schoolasset.lab_id = lab.id '
                . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                . ' GROUP BY schoolasset.id '
                . ' ORDER BY lab.id';

        $assets = R::getAll($sql);

        return $assets;
    }

    public function getAppForms() {

        $sql = 'SELECT applicationform.id AS id, '
                . ' school.registry_no AS school_registry_no, '
                . ' school.name AS school_name, '
                . ' FROM_UNIXTIME(applicationform.submitted) AS submitted, '
                . ' TRIM(applicationform.comments) AS comments '
                . ' FROM applicationform '
                . ' LEFT JOIN school ON applicationform.school_id = school.id '
                . ' GROUP BY school.id '
                . ' HAVING MAX(applicationform.submitted)';

        $appForms = R::getAll($sql);

        return $appForms;
    }

    public function getAppFormsItems() {

        $appFormIdsSql = 'SELECT applicationform.id '
                . ' FROM applicationform '
                . ' GROUP BY school_id '
                . ' HAVING MAX(applicationform.submitted)';

        $appFormIds = R::getCol($appFormIdsSql);

        if (empty($appFormIds)) {
            return [];
        }

        $in = implode(',', array_fill(0, count($appFormIds), '?'));
        $sql = 'SELECT applicationform.id AS id, '
                . ' school.registry_no AS school_registry_no, '
                . ' school.name AS school_name, '
                . ' FROM_UNIXTIME(applicationform.submitted) AS submitted, '
                . ' lab.id AS lab_id, '
                . ' TRIM(labtype.name) AS lab_type, '
                . ' IF(lab.is_new = 1, "ΝΑΙ", "ΟΧΙ") AS is_new, '
                . ' TRIM(itemcategory.name) AS category, '
                . ' applicationformitem.qty AS qty, '
                . ' TRIM(applicationformitem.reasons) AS reasons '
                . ' FROM applicationformitem '
                . ' LEFT JOIN applicationform ON applicationformitem.applicationform_id = applicationform.id '
                . ' LEFT JOIN school ON applicationform.school_id = school.id '
                . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id '
                . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id '
                . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                . ' WHERE applicationform.id IN(' . $in . ') ';

        $appFormsItems = R::getAll($sql, $appFormIds);

        return $appFormsItems;
    }

    public function getSoftwareItems() {

        $sql = 'SELECT softwarecategory.name AS name, '
                . ' school.registry_no AS school_registry_no, '
                . ' school.name AS school_name, '
                . ' lab.id AS lab_id, '
                . ' TRIM(labtype.name) AS lab_type, '
                . ' TRIM(software.title) AS title, '
                . ' TRIM(software.vendor) AS vendor, '
                . ' TRIM(software.url) AS url '
                . ' FROM software '
                . ' LEFT JOIN softwarecategory ON software.softwarecategory_id = softwarecategory.id '
                . ' LEFT JOIN school ON software.school_id = school.id '
                . ' LEFT JOIN lab ON software.lab_id = lab.id '
                . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                . ' ORDER BY school_name ';

        $softwareItems = R::getAll($sql);

        $softwareItems = array_map(function ($row) {
            $row['url'] = strtolower($row['url']);
            $row['url'] = str_replace('\\', '/', $row['url']);
            $row['url'] = urldecode($row['url']);

            return $row;
        }, $softwareItems);

        return $softwareItems;
    }

}
