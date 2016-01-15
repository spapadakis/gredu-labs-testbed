<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authentication;

use JsonSerializable;

class Identity implements JsonSerializable
{
    protected $uid;

    protected $mail;

    protected $displayName;

    protected $officeName;

    public function __construct($uid, $mail, $displayName, $officeName)
    {
        $this->uid         = $uid;
        $this->mail        = $mail;
        $this->displayName = $displayName;
        $this->officeName  = $officeName;
    }

    public function __get($name)
    {
        if (property_exists($name, $this)) {
            return $this->{$name};
        }

        return;
    }

    public function __toString()
    {
        return $this->displayName;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function toArray()
    {
        return [
            'uid'         => $this->uid,
            'mail'        => $this->mail,
            'displayName' => $this->displayName,
            'officeName'  => $this->officeName,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}