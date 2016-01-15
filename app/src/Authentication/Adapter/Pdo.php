<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authentication\Adapter;

use PDO as PDOConnection;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;

class Pdo extends AbstractAdapter
{

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * Construct adapter
     * 
     * @param PDOConnection $db
     */
    public function __construct(PDOConnection $db)
    {
        $this->db = $db;
    }


    public function authenticate()
    {
        return new Result(Result::FAILURE, null, ['Authentication failure']);
    }
}
