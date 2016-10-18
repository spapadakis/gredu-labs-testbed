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

//use RedBeanPHP\R;
use Slim\Container;

/**
 *
 * 
 */
class AppNewFormProvider implements DataProviderInterface
{

    private $_data;
    private $_container;

    public function __construct(Container $container)
    {
        $this->_container = $container;
        $this->_data = null;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        // use existing data collection methods
        if ($this->_container->has('csv_export_appnewforms')) {
            $this->_data = call_user_func($this->_container['csv_export_appnewforms'], $this->_container);
        }

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
            'id',
            'school_registry_no',
            'school_name',
            'submitted',
            'comments',
        ];
    }
}
