<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

return [
    'modules' => [
        'module/application/bootstrap.php',
        'module/authentication/bootstrap.php',
        'module/authorization/bootstrap.php',
        'module/sch_ldap/bootstrap.php',
        'module/sch_sso/bootstrap.php',
        'module/sch_mm/bootstrap.php',
        'module/sch_inventory/bootstrap.php',
        'module/sch_sync/bootstrap.php',
        'module/schools/bootstrap.php',
        'module/application_form/bootstrap.php',
        'module/receive_equip/bootstrap.php',
        'module/tpe_survey/bootstrap.php',
        'module/in_numbers/bootstrap.php',
        'module/in_numbers_mm/bootstrap.php',
        'module/csv_export/bootstrap.php',
        'module/open_data/bootstrap.php',
    ],
    'cache_config' => 'data/cache/config/settings.php',
];
