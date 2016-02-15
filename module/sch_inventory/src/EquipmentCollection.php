<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace  SchInventory;

use CallbackFilterIterator;
use InvalidArgumentException;
use Traversable;

class EquipmentCollection extends ImmutableArrayObject
{
    /**
     * Collection constructor
     *
     * @param array|Traversable
     */
    public function __construct($equipmentObjects)
    {
        if ($equipmentObjects instanceof Traversable) {
            $equipmentObjects = iterator_to_array($equipmentObjects);
        }

        $previousHandler = set_error_handler(['self', 'handleErrors']);
        parent::__construct(array_map(function (Equipment $equipment) {
            return $equipment;
        }, $equipmentObjects));
        set_error_handler($previousHandler);
    }

    /**
     * Returns a new Equipment collection with equimpment matching the given location
     *
     * @param string $location
     * @retun EquipmentCollection
     */
    public function withLocation($location)
    {
        return new self(new CallbackFilterIterator($this->getIterator(), function (Equipment $equipment) use ($location) {
            return $equipment->location === $location;
        }));
    }

    /**
     * Returns a new Equipment collection with equimpment matching the given category
     *
     * @param string $category
     * @retun EquipmentCollection
     */
    public function withCategory($category)
    {
        return new self(new CallbackFilterIterator($this->getIterator(), function (Equipment $equipment) use ($category) {
            return $equipment->category === $category;
        }));
    }

    private static function handleErrors($severity, $message, $file, $line)
    {
        throw new InvalidArgumentException($message);
    }
}
