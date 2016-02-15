<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Filter;

use InvalidArgumentException;

class Teacher
{
    // private static $required = [
    //     'school_id',
    //     'branch_id',
    //     'name',
    //     'surname',
    //     'email',
    //     'telephone',
    // ];

    // private static $optional = [
    //     'is_principle',
    //     'is_responsible',
    // ];

    // private static $messageTemplates = [
    //     'school_id' => 'Δεν ορίστηκε το σχολείο',
    //     'branch_id' => 'Δεν ορίστηκε η ειδικότητα',
    //     'name' => 'Δεν ορίστηκε το όνομα',
    //     'surname' => 'Δεν ορίστηκε το επώνυμο',
    //     'email'=> 'Δεν ορίστηκε το email',
    //     'telephone' => 'Δεν ορίστηκε το τηλέφωνο',
    // ];

    private static $filter = [
        'school_id' =>  [
            'filter' => FILTER_VALIDATE_INT,
            'flags' => FILTER_REQUIRE_SCALAR,
        ],
        'branch_id' => [
            'filter' => FILTER_VALIDATE_INT,
            'flags' => FILTER_REQUIRE_SCALAR,
        ],
        'name' => [
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_REQUIRE_SCALAR,
        ],
        'surname' => [
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_REQUIRE_SCALAR,
        ],
        'email' => FILTER_VALIDATE_EMAIL,
        'telephone' => FILTER_SANITIZE_NUMBER_INT,
        'is_principle' => [
            'filter' => FILTER_VALIDATE_BOOLEAN,
        ],
        'is_responsible' => [
            'filter' => FILTER_VALIDATE_BOOLEAN,
        ],

    ];

    public function __invoke(array $data, $create = true)
    {

        var_dump(filter_var_array($data, self::$filter, $create));
        die();

        // $messages = [];
        // $fields = array_merge(self::$required, self::$optional);
        // $data = array_intersect_key($data, array_flip($fields));
        // $filtered = array_map('trim', $data);

        // foreach (self::$required as $required) {
        //     if (!isset($data[$required])|| empty($data[$required])) {
        //         $messages[$required][] = self::$messageTemplates[$required];
        //     }
        // }

        // if (filter_input_array(type))

        // var_dump($messages);
        // die();
        
    }
}