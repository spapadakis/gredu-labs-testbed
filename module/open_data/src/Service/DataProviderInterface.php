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

interface DataProviderInterface
{

    /**
     * Return any available data. 
     * 
     * @return array|null|false Null in case of any error collecting data, 
     * false in case the data cannot be located, 
     * empty array if no data is available, 
     * or an array of the data available.
     */
    public function getData();

    /**
     * Return the labels for the available data. 
     * 
     * @return array An array of text labels 
     */
    public function getLabels();

    /**
     * Return the number of data items provided. 
     * 
     * @return int the number of data items provided
     */
    public function getCount();
}
