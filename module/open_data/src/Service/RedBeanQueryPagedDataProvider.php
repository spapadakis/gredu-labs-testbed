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
 * This class assumes MySQL database and relies on it's FOUNR_ROWS function to
 * determine total results.
 * TODO: provide a more generic and platform independent way to do this. 
 * See also: http://dev.mysql.com/doc/refman/5.7/en/information-functions.html#function_found-rows
 * 
 */
class RedBeanQueryPagedDataProvider implements DataProviderInterface
{

    /**
     * @var int items per page
     */
    protected $_pagesize;

    /**
     * @var int the current page, 1 based 
     */
    protected $_page;

    /**
     * @var string the base query to use for data retrieval
     */
    protected $_query;

    /**
     * @var string the query bind params if any
     */
    protected $_query_params;

    /**
     * @var array the retrieved data
     */
    protected $_data;

    /**
     * @var int hold the total number of results without paging
     */
    protected $_data_count_all;

    /**
     * Provide for default values. 
     */
    public function __construct()
    {
        $this->_pagesize = 20;
        $this->_page = 1;
        $this->_query = '';
        $this->_data = [];
        $this->_data_count_all = 0;
    }

    /**
     * @return int the items per page 
     */
    public function getPagesize()
    {
        return $this->_pagesize;
    }

    /**
     * Set the page size.
     * 
     * @param int $pagesize the desired page size
     * @return int the current page size
     */
    public function setPagesize(int $pagesize)
    {
        $this->_pagesize = $pagesize;
        return $this->getPagesize();
    }

    /**
     * @return int the current page number (1 based)
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @return int the total number of pages 
     */
    public function getPages()
    {
        return ceil($this->getCountAll() / $this->getPagesize());
    }

    /**
     * Set the page size.
     * 
     * @param int $page the page requested
     * @return int the current page number 
     */
    public function setPage(int $page)
    {
        $this->_page = $page;
        return $this->getPage();
    }

    /**
     * Return an sql limit and offset clause part to get specific data parts
     * according to the page and pagesize.
     * 
     * @return string The sql limit, offset clause part
     */
    public function getDatalimitSql()
    {
        $sql = ' LIMIT ' . $this->getPagesize() .
            ' OFFSET ' . ($this->getPage() - 1) * $this->getPagesize();
        return $sql;
    }

    /**
     * Set the query to run. 
     * 
     * @param string $query
     * @param array $params bind params to query
     */
    public function setQuery(string $query, array $params = [])
    {
        // add SQL_CALC_FOUND_ROWS to query 
        $mod_query = str_ireplace('START_OF_QUERYSELECT ', 'SELECT SQL_CALC_FOUND_ROWS ', "START_OF_QUERY" . trim($query));
        $this->_query = $mod_query;
        $this->_query_params = $params;
    }

    /**
     * Runs the query to get paged data.
     * 
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->_data = R::getAll($this->_query . $this->getDatalimitSql(), $this->_query_params);
        $rows = R::getCell('SELECT FOUND_ROWS()');
        $this->_data_count_all = intval($rows);
        return $this->_data;
    }

    /**
     * @inheritdoc
     */
    public function getLabels()
    {
        if (isset($this->_data)) {
            return array_keys($this->_data[0]);
        } else {
            return [];
        }
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
     * 
     */
    public function getCountAll()
    {
        if (!isset($this->_data)) {
            $data = $this->getData();
        }
        return $this->_data_count_all;
    }
}
