<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authentication\Storage;

use Zend\Authentication\Storage\StorageInterface;

class PhpSession implements StorageInterface
{
    /**
     * Default session namespace
     */
    const NAMESPAGE_DEFAULT = 'GrEduLabs_Auth';

    /**
     * Default session object member name
     */
    const MEMBER_DEFAULT = 'storage';

    /**
     * Session array
     * 
     * @var array
     */
    protected $session;

    /**
     * Session namespace
     *
     * @var mixed
     */
    protected $namespace = self::NAMESPAGE_DEFAULT;

    /**
     * Session object member
     *
     * @var mixed
     */
    protected $member = self::MEMBER_DEFAULT;

    /**
     * Sets session storage options and initializes session namespace
     *
     * @param  array $session
     * @param  mixed $namespace
     * @param  mixed $member
     */
    public function __construct(array &$session, $namespace = null, $member = null)
    {
        if ($namespace !== null) {
            $this->namespace = $namespace;
        }
        if ($member !== null) {
            $this->member = $member;
        }
        $this->session                   =& $session;
        $this->session[$this->namespace] = [];
    }

    /**
     * Returns the session namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Returns the name of the session object member
     *
     * @return string
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Returns true if and only if storage is empty
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If it is impossible to determine whether storage is empty
     * @return bool
     */
    public function isEmpty()
    {
        return !isset($this->session[$this->namespace][$this->member]);
    }

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If reading contents from storage is impossible
     * @return mixed
     */
    public function read()
    {
        return $this->session[$this->namespace][$this->member];
    }

    /**
     * Writes $contents to storage
     *
     * @param  mixed $contents
     * @throws \Zend\Authentication\Exception\ExceptionInterface If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents)
    {
        $this->session[$this->namespace][$this->member] = $contents;
    }

    /**
     * Clears contents from storage
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If clearing contents from storage is impossible
     * @return void
     */
    public function clear()
    {
        unset($this->session[$this->namespace][$this->member]);
    }
}
