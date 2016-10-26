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

use GrEduLabs\OpenData\Service\RedBeanQueryPagedDataProvider;

/**
 * Provide basic filtering capabilities over the standard paged redbean provider.
 * 
 * {@inheritdoc} 
 */
class RedBeanFilteredQueryPagedDataProvider extends RedBeanQueryPagedDataProvider
{

    /**
     * @var array An array containing filter information in the form of 
     * `"column_name" => ['value' => "filter_value", 'op' => $op]` associations. 
     * @see queryFilter()
     */
    protected $_filters;

    /**
     * Provide for default values. 
     */
    public function __construct()
    {
        parent::__construct();
        $this->_filters = [];
    }

    /**
     * Set a filter for the query. 
     * Only simple filtering is allowed, that is if you set a filter with
     * `queryFilter('name', 'the value')` a condition of the form
     * ` name = 'the value' ` will be generated. 
     * 
     * @param string|null $column_name the name of the column to apply filter for.
     * If the value is null, the filter for the column will be ignored.
     * @param type $value
     * @param string $op either '=' or 'LIKE' for partial match; anything else
     * computes to '='
     */
    public function queryFilter($column_name, $value = null, $op = '=')
    {
        if (!in_array(($op = trim(strtoupper($op))), ['=', 'LIKE'])) {
            $op = '=';
        }
        $this->_filters[$column_name] = [
            'value' => $value,
            'op' => $op
        ];
    }

    /**
     * Check if filters apply
     * 
     * @return boolean
     */
    protected function hasQueryFilters()
    {
        $filters = $this->_filters;
        if (!is_array($filters)) {
            return false;
        }
        if (count($filters) == 0) {
            return false;
        }
        return array_reduce(array_keys($filters), function ($non_null_cnt, $key) use ($filters) {
            if (isset($filters[$key])) {
                $non_null_cnt++;
            }
            return $non_null_cnt;
        }, 0);
    }

    /**
     * 
     * @see queryFilter()
     * @return string the appropriate where clause 
     */
    public function getFilterSql()
    {
        $filters_sql = [];
        if ($this->hasQueryFilters()) {
            $filters = $this->_filters;
            $filters_sql = array_map(function ($key) use ($filters) {
                $sname = preg_replace('/[^a-z0-9]/', '', $key);
                return (isset($filters[$key]['value']) ? " {$key} {$filters[$key]['op']} :value_{$sname} " : null);
            }, array_keys($filters));
        }

        return implode(' AND ', array_filter($filters_sql, function ($v) {
                return $v !== null;
            }));
    }

    /**
     * 
     * @return array the parameters to bind to the query 
     */
    public function getFilterParams()
    {
        $filters_params = [];
        if ($this->hasQueryFilters()) {
            $filters = $this->_filters;
            $filters_params = array_reduce(array_keys($filters), function ($params, $key) use ($filters) {
                if (isset($filters[$key]['value'])) {
                    $sname = preg_replace('/[^a-z0-9]/', '', $key);
                    switch ($filters[$key]['op']) {
                        case 'LIKE':
                            $params[":value_{$sname}"] = '%' . $filters[$key]['value'] . '%';
                            break;
                        case '=':
                        default:
                            $params[":value_{$sname}"] = $filters[$key]['value'];
                            break;
                    }
                }
                return $params;
            }, []);
        }
        return $filters_params;
    }

    /**
     * For simplicity let us assume that all of *our own* queried *do* have
     * a WHERE or GROUP BY or ORDER BY clause.
     * TODO: overcome this assumption 
     * 
     * Under the assumption that a WHERE or GROUP BY or ORDER BY exists in the 
     * query, add conditions just before the first occurance and append the 
     * parameters as appropriate. 
     */
    public function getData()
    {
//        echo "<pre>";
//        echo '_query ', var_export($this->_query, true), "\n";
//        echo '_query_params ', var_export($this->_query_params, true), "\n";
//        echo 'getDatalimitSql ', var_export($this->getDatalimitSql(), true), "\n";
//        echo 'getFilterSql ', var_export($this->getFilterSql(), true), "\n";
//        echo 'getFilterParams ', var_export($this->getFilterParams(), true), "\n";
//        echo "</pre>";
//        die();

        if ($this->hasQueryFilters()) {
            $replacements = 0;
            $this->_query = preg_replace('/ WHERE /', ' WHERE ' . $this->getFilterSql() . ' AND ', $this->_query, 1, $replacements);
            if ($replacements === 0) {
                $this->_query = preg_replace('/ (GROUP BY|ORDER BY) /', ' WHERE ' . $this->getFilterSql() . ' $1 ', $this->_query, 1);
            }
            $this->_query_params = array_merge($this->_query_params, $this->getFilterParams());
        }
//        die(var_export($this->_query, true));
        return parent::getData();
    }
}
