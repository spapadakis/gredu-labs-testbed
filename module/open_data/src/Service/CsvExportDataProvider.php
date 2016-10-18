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

use Slim\Container;

/**
 *
 * 
 */
class CsvExportDataProvider implements DataProviderInterface
{

    private $_data;
    private $_container;

    /**
     * @var string the csv export type
     * @see csv_export/bootstrap.php 
     */
    private $_csv_export_type;

    /**
     * @var null|callable null if no data callback exists 
     */
    private $_data_callback;

    public function __construct(Container $container, $csv_export_type)
    {
        $this->_container = $container;
        $this->_data = null;
        $this->_csv_export_type = $csv_export_type;
        $this->_data_callback = null;

        // use existing data collection methods
        if ($this->_container->has('csv_export_config')) {
            $config = $this->_container['csv_export_config'];
            if (array_key_exists($this->_csv_export_type, $config)) {
                $data_callback = $config[$this->_csv_export_type]['data_callback'];
                if ($this->_container->has($data_callback)) {
                    $this->_data_callback = $this->_container[$data_callback];
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        if ($this->_data_callback !== null) {
            $this->_data = call_user_func($this->_data_callback, $this->_container);
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
        // use existing data collection methods
        if ($this->_data_callback !== null) {
            $values = $this->_container['csv_export_config'][$this->_csv_export_type]['headers'];
            if (is_array($this->_data) && count($this->_data) > 0) {
                $keys = array_keys($this->_data[0]);
            } else {
                $keys = & $values;
            }
            return array_combine($keys, $values);
        } else {
            return [];
        }
    }
}
