<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Stdlib;

use ArrayObject;
use LogicException;

class ImmutableArrayObject extends ArrayObject
{

    public function append($value)
    {
        throw new LogicException('Attempting to write to an immutable array');
    }

    public function exchangeArray($input)
    {
        throw new LogicException('Attempting to write to an immutable array');
    }

    public function offsetSet($index, $newval)
    {
        throw new LogicException('Attempting to write to an immutable array');
    }

    public function offsetUnset($index)
    {
        throw new LogicException('Attempting to write to an immutable array');
    }
}
