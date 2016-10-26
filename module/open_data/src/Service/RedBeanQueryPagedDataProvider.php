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
     * @var array|null An array containing data labels.
     * If this is not set, the keys of the result dataset will be used.
     * @see setLabels()
     */
    protected $_labels;

    /**
     * @var int hold the total number of results without paging
     */
    protected $_data_count_all;

    /**
     * @var callback Possible data post handling can be achieved by setting a callback function 
     * @see setDataPostHandleCallback()
     */
    protected $_data_handle_callback;

    /**
     * Provide for default values. 
     */
    public function __construct()
    {
        $this->_pagesize = 20;
        $this->_page = 1;
        $this->_query = '';
        $this->_data = [];
        $this->_labels = null;
        $this->_data_count_all = 0;
        $this->_data_handle_callback = null;
    }

    /**
     * Possible data post handling can be achieved by setting a closure. 
     * The closure must accept one argument, the array of data and
     * should return the modified array of data.
     * 
     * For example
     * ```php
     * function ($data) {
     *      return array_map(function ($row) {
     *          $row['url'] = strtolower($row['url']);
     *          $row['url'] = str_replace('\\', '/', $row['url']);
     *          $row['url'] = urldecode($row['url']);
     *          return $row;
     *      }, $data);
     * }
     * ```
     * @param \Closure $callback
     */
    public function setDataPostHandleCallback(\Closure $callback)
    {
        $this->_data_handle_callback = $callback;
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
    public function setPagesize($pagesize)
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
    public function setPage($page)
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
    public function setQuery($query, array $params = [])
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

        if (is_callable($callback = $this->_data_handle_callback)) {
            $this->_data = $callback($this->_data);
        }
        return $this->_data;
    }

    /**
     * Set the labels for the result data set. 
     *
     * @param array $labels
     */
    public function setLabels(array $labels)
    {
        $this->_labels = $labels;
    }

    /**
     * @inheritdoc
     */
    public function getLabels()
    {
        if (isset($this->_labels)) {
            return $this->_labels;
        } elseif (is_array($this->_data) && count($this->_data) > 0) {
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
