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
 * @inheritdoc
 */
class PrefecturesProvider extends RedBeanQueryPagedDataProvider
{

    /**
     * @var string|null Holds any filter associated with the name property
     */
    private $_name;

    /**
     * Provide for default values. 
     */
    public function __construct()
    {
        parent::__construct();
        $this->_name = null;

        $this->setQuery('SELECT id, name FROM prefecture');
        $this->setLabels([
            'id' => 'Αναγνωριστικό ID',
            'name' => 'Ονομασία Π.Ε.'
        ]);
    }

    /**
     * Used to filter data by specific name.
     * 
     * @param string|null $name The name to filter 
     */
    public function filterName(string $name = null)
    {
        $this->_name = $name;
        if (isset($this->_name)) {
            $filter_part = ' WHERE name = :name ';
            $filter_params = [':name' => $this->_name];
        } else {
            $filter_part = '';
            $filter_params = [];
        }
        $this->setQuery("SELECT id, name FROM prefecture {$filter_part}", $filter_params);
        
        parent::setQuery($this->_query, $this->_query_params);
    }
}
