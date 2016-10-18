<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
namespace GrEduLabs\OpenData\Service;

use RedBeanPHP\R;

/**
 *
 * 
 */
class SchoolProvider implements DataProviderInterface
{

    private $_data;

    /**
     * @inheritdoc
     */
    public function getData()
    {
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
            . ' ORDER BY school_name'
            . ' LIMIT 100 '; // TODO REMOVE 
        $this->_data = R::getAll($sql);

        return [];
        return $this->_data;
    }

    /**
     * @inheritdoc
     */
    public function getCount()
    {
        if (!isset($this->_data)) {
            $data = $this->getData();
        }
        return count($this->_data);
    }

    /**
     * @inheritdoc
     */
    public function getLabels()
    {
        // return R::inspect('school'); // return table columns 
        return [
            'registry_no',
            'school_name',
            'school_type',
            'prefecture',
            'eduadmin',
            'region_edu_admin',
            'education_level'
        ];
    }
}
