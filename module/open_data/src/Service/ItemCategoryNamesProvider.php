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
 * Sample data provider to get all item categories 
 * 
 */
class ItemCategoryNamesProvider implements DataProviderInterface
{

    /**
     * @var type array|null Data retrieved 
     */
    private $_data;

    /**
     * @var string|null Holds any filter associated with the groupflag property
     */
    private $_group_filter;

    public function __construct()
    {
        $this->_data = null;
        $this->_group_filter = null;
    }

    /**
     * Used to filter data by specific groupflag.
     * 
     * @param int|null $groupflag The group glag to filter 
     */
    public function filterGroupflag($groupflag = null)
    {
        $this->_group_filter = $groupflag;
    }

    /**
     * Reset any filter data for the groupflag. 
     */
    public function unfilterGroupflag($groupflag)
    {
        $this->_group_filter = null;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $sql = 'SELECT id, name, groupflag '
            . ' FROM itemcategory '
            . (isset($this->_group_filter) ? ' WHERE groupflag = :groupflag ' : '')
            . ' ORDER BY groupflag, sort ';
        $this->_data = R::getAll($sql, isset($this->_group_filter) ? [':groupflag' => $this->_group_filter] : []);

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
        return [
            'id' => 'Αναγνωριστικό ID',
            'name' => 'Ονομασία',
            'groupflag' => 'Ομάδα',
        ];
    }
}
