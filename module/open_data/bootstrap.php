<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use GrEduLabs\OpenData\Action;

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
return function (App $app) {
    $container = $app->getContainer();
    $events = $container['events'];

    $events('on', 'app.autoload', function ($autoloader) {
        $autoloader->addPsr4('GrEduLabs\\OpenData\\', __DIR__ . '/src/');
    });

    /**
     * Data queries specifications.
     */
    $events('on', 'app.services', function ($container) {
        $container['data_retrieve_query_specs'] = function ($container) {
            return [
                'schools' => [
                    'query' => 'SELECT school.registry_no AS registry_no, '
                    . ' school.name AS school_name, '
                    . ' schooltype.name as school_type, '
                    . ' prefecture.name AS prefecture, '
                    . ' eduadmin.name AS eduadmin, '
                    . ' regioneduadmin.name AS region_edu_admin, '
                    . ' educationlevel.name AS education_level '
                    . ' FROM school '
                    . ' LEFT JOIN eduadmin ON school.eduadmin_id = eduadmin.id '
                    . ' LEFT JOIN regioneduadmin ON eduadmin.regioneduadmin_id = regioneduadmin.id '
                    . ' LEFT JOIN educationlevel ON school.educationlevel_id = educationlevel.id '
                    . ' LEFT JOIN schooltype ON school.schooltype_id = schooltype.id '
                    . ' LEFT JOIN prefecture ON school.prefecture_id = prefecture.id '
                    . ' GROUP BY school.id '
                    . ' ORDER BY school_name',
                    'headers' => [
                        'registry_no' => 'Κωδικός μονάδας',
                        'school_name' => 'Ονομασία',
                        'school_type' => 'Τύπος μονάδας',
                        'prefecture' => 'Περιφερειακή ενότητα',
                        'eduadmin' => 'Διεύθυνση εκπαίδευσης',
                        'region_edu_admin' => 'Περιφερειακή διεύθυνση εκπαίδευσης',
                        'education_level' => 'Βαθμίδα εκπαίδευσης',
                    ],
                ],
                'labs' => [
                    'query' => 'SELECT lab.id AS id, '
                    . ' school.registry_no AS school_registry_no, '
                    . ' school.name AS school_name, '
                    . ' TRIM(lab.name) AS name, '
                    . ' TRIM(labtype.name) AS type, '
                    . ' branch.name AS responsible_branch, '
                    . ' IF(lab.is_new = 1, "ΝΑΙ", "ΟΧΙ") AS is_new, '
                    . ' lab.area AS area, '
                    . ' lab.has_network AS has_network, '
                    . ' lab.has_server AS has_server, '
                    . ' GROUP_CONCAT(lesson.name SEPARATOR ", ") AS lessons, '
                    . ' TRIM(lab.use_in_program) AS use_in_program, '
                    . ' TRIM(lab.use_ext_program) AS use_ext_program '
                    . ' FROM lab '
                    . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                    . ' LEFT JOIN school ON lab.school_id = school.id '
                    . ' LEFT JOIN lab_lesson ON lab_lesson.lab_id = lab.id '
                    . ' LEFT JOIN lesson ON lab_lesson.lesson_id = lesson.id '
                    . ' LEFT JOIN teacher ON lab.responsible_id = teacher.id '
                    . ' LEFT JOIN branch ON branch.id = teacher.branch_id '
                    . ' GROUP BY lab.id '
                    . ' ORDER BY school_name ',
                    'headers' => [
                        'id' => 'ID',
                        'school_registry_no' => 'Κωδικός σχολείου',
                        'school_name' => 'Ονομασία σχολείου',
                        'name' => 'Ονομασία χώρου',
                        'type' => 'Τύπος χώρου',
                        'responsible_branch' => 'Ειδικότητα υπευθύνου',
                        'is_new' => 'Νέος χώρος',
                        'area' => 'Επιφάνεια (m^2)',
                        'has_network' => 'Σύνδεση στο δίκτυο',
                        'has_server' => 'Διαθέτει server',
                        'lessons' => 'Μαθήματα',
                        'use_in_program' => 'Χρήση στα πλαίσια μαθημάτων',
                        'use_ext_program' => 'Χρήση για δραστηριότητες εκτός εκπαιδευτικού προγράμματος',
                    ],
                ],
                'assets' => [
                    'query' => 'SELECT TRIM(itemcategory.name) AS category, '
                    . ' schoolasset.qty AS qty, '
                    . ' schoolasset.acquisition_year AS acquisition_year, '
                    . ' lab.id AS lab_id, '
                    . ' TRIM(labtype.name) AS lab_type, '
                    . ' school.registry_no AS school_registry_no, '
                    . ' school.name AS school_name, '
                    . ' schoolasset.comments AS comments '
                    . ' FROM schoolasset '
                    . ' LEFT JOIN itemcategory ON schoolasset.itemcategory_id = itemcategory.id '
                    . ' LEFT JOIN school ON schoolasset.school_id = school.id '
                    . ' LEFT JOIN lab ON schoolasset.lab_id = lab.id '
                    . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                    . ' GROUP BY schoolasset.id '
                    . ' ORDER BY lab.id',
                    'headers' => [
                        'category' => 'Είδος',
                        'qty' => 'Πλήθος ',
                        'acquisition_year' => 'Έτος κτήσης',
                        'lab_id' => 'ID χώρου',
                        'lab_type' => 'Τύπος χώρου',
                        'school_registry_no' => 'Κωδικός σχολείου',
                        'school_name' => 'Ονομασία σχολείου',
                        'comments' => 'Σχόλια - Παρατηρήσεις',
                    ],
                ],
                'software' => [
                    'query' => 'SELECT softwarecategory.name AS name, '
                    . ' school.registry_no AS school_registry_no, '
                    . ' school.name AS school_name, '
                    . ' lab.id AS lab_id, '
                    . ' TRIM(labtype.name) AS lab_type, '
                    . ' TRIM(software.title) AS title, '
                    . ' TRIM(software.vendor) AS vendor, '
                    . ' TRIM(software.url) AS url '
                    . ' FROM software '
                    . ' LEFT JOIN softwarecategory ON software.softwarecategory_id = softwarecategory.id '
                    . ' LEFT JOIN school ON software.school_id = school.id '
                    . ' LEFT JOIN lab ON software.lab_id = lab.id '
                    . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                    . ' ORDER BY school_name ',
                    'data_post_handle_callback' => function ($data) {
                        return array_map(function ($row) {
                                $row['url'] = strtolower($row['url']);
                                $row['url'] = str_replace('\\', '/', $row['url']);
                                $row['url'] = urldecode($row['url']);
                                return $row;
                            }, $data);
                    },
                    'headers' => [
                        'name' => 'Τύπος',
                        'school_registry_no' => 'Κωδικός σχολείου',
                        'school_name' => 'Ονομασία σχολείου',
                        'lab_id' => 'ID χώρου',
                        'lab_type' => 'Τύπος χώρου',
                        'title' => 'Ονομασία',
                        'vendor' => 'Κατασκευαστής',
                        'url' => 'URL',
                    ],
                ],
                'applications' => [
                    'query' => 'SELECT applicationform.id AS id, '
                    . ' school.registry_no AS school_registry_no, '
                    . ' school.name AS school_name, '
                    . ' FROM_UNIXTIME(applicationform.submitted) AS submitted, '
                    . ' TRIM(applicationform.comments) AS comments'
                    . ' FROM applicationformitem '
                    . ' LEFT JOIN applicationform ON applicationformitem.applicationform_id = applicationform.id '
                    . ' LEFT JOIN school ON applicationform.school_id = school.id '
                    . ' LEFT JOIN eduadmin ON school.eduadmin_id = eduadmin.id '
                    . ' LEFT JOIN regioneduadmin ON eduadmin.regioneduadmin_id = regioneduadmin.id '
                    . ' LEFT JOIN prefecture ON school.prefecture_id = prefecture.id '
                    . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id '
                    . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id '
                    . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                    . ' WHERE applicationform.id IN '
                    . '('
                    . 'SELECT id FROM applicationform WHERE (submitted)IN (SELECT MAX( submitted )FROM applicationform '
                    . ' LEFT JOIN applicationformitem ON applicationformitem.applicationform_id = applicationform.id '
                    . ' LEFT JOIN school ON applicationform.school_id = school.id'
                    . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id'
                    . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id'
                    . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id'
                    . ' WHERE itemcategory.groupflag NOT IN ( :version )'
                    . ' GROUP BY school.id)'
                    . ')'
                    . ' GROUP BY school.id ',
                    'headers' => [
                        'id' => 'ID',
                        'school_registry_no' => 'Κωδικός σχολείου',
                        'school_name' => 'Ονομασία σχολείου',
                        'submitted' => 'Ημερομηνία υποβολής',
                        'comments' => 'Σχόλια - Παρατηρήσεις',
                    ],
                    'params_callback' => function ($container) {
                    $settings = $container->get('settings');
                    return [
                        ':version' => $settings['application_form']['itemcategory']['currentversion'],
                    ];
                },
                ],
                'new_applications' => [
                    'query' =>
                    'SELECT applicationform.id AS id, '
                    . ' school.registry_no AS school_registry_no, '
                    . ' school.name AS school_name, '
                    . ' FROM_UNIXTIME(applicationform.submitted) AS submitted, '
                    . ' TRIM(applicationform.comments) AS comments'
                    . ' FROM applicationformitem '
                    . ' LEFT JOIN applicationform ON applicationformitem.applicationform_id = applicationform.id '
                    . ' LEFT JOIN school ON applicationform.school_id = school.id '
                    . ' LEFT JOIN eduadmin ON school.eduadmin_id = eduadmin.id '
                    . ' LEFT JOIN regioneduadmin ON eduadmin.regioneduadmin_id = regioneduadmin.id '
                    . ' LEFT JOIN prefecture ON school.prefecture_id = prefecture.id '
                    . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id '
                    . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id '
                    . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                    . ' WHERE applicationform.id IN '
                    . '('
                    . 'SELECT id FROM applicationform WHERE (submitted) IN( SELECT MAX(submitted) FROM applicationform GROUP BY school_id)'
                    . ')'
                    . ' AND itemcategory.groupflag IN ( :version )'
                    . ' GROUP BY school.id ',
                    'headers' => [
                        'id' => 'ID',
                        'school_registry_no' => 'Κωδικός σχολείου',
                        'school_name' => 'Ονομασία σχολείου',
                        'submitted' => 'Ημερομηνία υποβολής',
                        'comments' => 'Σχόλια - Παρατηρήσεις',
                    ],
                    'params_callback' => function ($container) {
                    $settings = $container->get('settings');
                    return [
                        'version' => $settings['application_form']['itemcategory']['currentversion'],
                    ];
                },
                ],
                'application_items' => [
                    'query' =>
                    'SELECT applicationform.id AS id, '
                    . ' school.registry_no AS school_registry_no, '
                    . ' school.name AS school_name, '
                    . ' FROM_UNIXTIME(applicationform.submitted) AS submitted, '
                    . ' lab.id AS lab_id, '
                    . ' TRIM(labtype.name) AS lab_type, '
                    . ' IF(lab.is_new = 1, "ΝΑΙ", "ΟΧΙ") AS is_new, '
                    . ' TRIM(itemcategory.name) AS category, '
                    . ' applicationformitem.qty AS qty, '
                    . ' TRIM(applicationformitem.reasons) AS reasons '
                    . ' FROM applicationformitem '
                    . ' LEFT JOIN applicationform ON applicationformitem.applicationform_id = applicationform.id '
                    . ' LEFT JOIN school ON applicationform.school_id = school.id '
                    . ' LEFT JOIN eduadmin ON school.eduadmin_id = eduadmin.id '
                    . ' LEFT JOIN regioneduadmin ON eduadmin.regioneduadmin_id = regioneduadmin.id '
                    . ' LEFT JOIN prefecture ON school.prefecture_id = prefecture.id '
                    . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id '
                    . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id '
                    . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                    . ' WHERE applicationform.id IN '
                    . '('
                    . 'SELECT id FROM applicationform WHERE (submitted)IN (SELECT MAX( submitted )FROM applicationform '
                    . ' LEFT JOIN applicationformitem ON applicationformitem.applicationform_id = applicationform.id '
                    . ' LEFT JOIN school ON applicationform.school_id = school.id'
                    . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id'
                    . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id'
                    . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id'
                    . ' WHERE itemcategory.groupflag NOT IN ( :version )'
                    . ' GROUP BY school.id)'
                    . ') '
                    ,
                    'headers' => [
                        'id' => 'ID αίτησης',
                        'school_registry_no' => 'Κωδικός σχολείου',
                        'school_name' => 'Ονομασία σχολείου',
                        'submitted' => 'Ημερομηνία υποβολής',
                        'lab_id' => 'ID χώρου',
                        'lab_type' => 'Τύπος χώρου',
                        'is_new' => 'Νέος χώρος',
                        'category' => 'Είδος',
                        'qty' => 'Πλήθος ',
                        'reasons' => 'Αιτιολογία χρήσης',
                    ],
                    'params_callback' => function ($container) {
                    $settings = $container->get('settings');
                    return [
                        'version' => $settings['application_form']['itemcategory']['currentversion'],
                    ];
                },
                ],
                'new_application_items' => [
                    'query' => 'SELECT applicationform.id AS id, '
                    . ' school.registry_no AS school_registry_no, '
                    . ' school.name AS school_name, '
                    . ' TRIM(itemcategory.name) AS category, '
                    . ' applicationformitem.qty AS qty, '
                    . ' applicationformitem.qtyacquired AS qtyacquired, '
                    . ' TRIM(applicationformitem.reasons) AS reasons '
                    . ' FROM applicationformitem '
                    . ' LEFT JOIN applicationform ON applicationformitem.applicationform_id = applicationform.id '
                    . ' LEFT JOIN school ON applicationform.school_id = school.id '
                    . ' LEFT JOIN eduadmin ON school.eduadmin_id = eduadmin.id '
                    . ' LEFT JOIN regioneduadmin ON eduadmin.regioneduadmin_id = regioneduadmin.id '
                    . ' LEFT JOIN prefecture ON school.prefecture_id = prefecture.id '
                    . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id '
                    . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id '
                    . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                    . ' WHERE applicationform.id IN '
                    . '('
                    . 'SELECT id FROM applicationform WHERE (submitted) IN( SELECT MAX(submitted) FROM applicationform GROUP BY school_id)'
                    . ')'
                    . ' AND itemcategory.groupflag IN ( :version )',
                    'headers' => [
                        'id' => 'ID',
                        'school_registry_no' => 'Κωδικός σχολείου',
                        'school_name' => 'Ονομασία σχολείου',
                        'category' => 'Είδος',
                        'qtyacquired' => 'Πλήθος Υπαρχόντων που λειτουργούν',
                        'qty' => 'Πλήθος Αιτουμένων',
                        'reasons' => 'Αιτιολογία χρήσης',
                    ],
                    'params_callback' => function ($container) {
                    $settings = $container->get('settings');
                    return [
                        'version' => $settings['application_form']['itemcategory']['currentversion'],
                    ];
                },
                ],
                'approved' => [
                    'query' => 'SELECT school.registry_no AS school_registry_no, '
                    . 'school.name AS school_name,'
                    . 'regioneduadmin.name AS regionedu_name,'
                    . 'eduadmin.name AS eduadmin_name '
                    . 'FROM applicationform '
                    . 'JOIN school ON applicationform.school_id=school.id '
                    . 'JOIN eduadmin ON school.eduadmin_id=eduadmin.id '
                    . 'JOIN regioneduadmin ON eduadmin.regioneduadmin_id=regioneduadmin.id '
                    . ' LEFT JOIN prefecture ON school.prefecture_id = prefecture.id '
                    . 'WHERE applicationform.approved=1 '
                    . 'ORDER BY regioneduadmin.name, eduadmin.name, school.name',
                    'headers' => [
                        'school_registry_no' => 'Κωδικός σχολείου',
                        'school_name' => 'Ονομασία σχολείου',
                        'regionedu_name' => 'Περιφέρεια',
                        'eduadmin_name' => 'Διεύθυνση',
                    ],
                ],
                'eduadminunits' => [
                    'query' => 'SELECT id, name '
                    . 'FROM eduadmin '
                    . 'ORDER BY name ',
                    'headers' => [
                        'id' => 'Κωδικός',
                        'name' => 'Ονομασία',
                    ],
                ],
                'regioneduadminunits' => [
                    'query' => 'SELECT id, name '
                    . 'FROM regioneduadmin '
                    . 'ORDER BY name ',
                    'headers' => [
                        'id' => 'Κωδικός',
                        'name' => 'Ονομασία',
                    ],
                ],
                'educationlevels' => [
                    'query' => 'SELECT id, name '
                    . 'FROM educationlevel '
                    . 'ORDER BY name ',
                    'headers' => [
                        'id' => 'Κωδικός',
                        'name' => 'Ονομασία',
                    ],
                ],
            ];
        };
    });

    /**
     * Adds api routes to acl 
     */
    $events('on', 'app.services', function ($container) {
        $data_retrieve_query_types = array_keys($container['data_retrieve_query_specs']);
        $acl = $container['settings']['acl'];
        $acl['guards']['routes'] = array_merge($acl['guards']['routes'], [
            ['/open-data/api', ['guest', 'user'], ['get']],
            ['/open-data/api/index', ['guest', 'user'], ['get']],
            ['/open-data/api/prefectures', ['guest', 'user'], ['get']],
            ['/open-data/api/prefecture/{name}', ['guest', 'user'], ['get']],
            ['/open-data/api/itemcategorynames', ['guest', 'user'], ['get']],
            ['/open-data/api/allschools', ['guest', 'user'], ['get']],
        ]);
        foreach ($data_retrieve_query_types as $data_retrieve_query_type) {
            $acl['guards']['routes'][] = ["/open-data/api/raw_{$data_retrieve_query_type}", ['guest', 'user'], ['get']];
            $acl['guards']['routes'][] = ["/open-data/api/{$data_retrieve_query_type}", ['guest', 'user'], ['get']];
        }
        $acl['guards']['routes'] = array_merge($acl['guards']['routes'], [
            ["/open-data/api/schools/education_level/{education_level}", ['guest', 'user'], ['get']],
            ["/open-data/api/schools/prefecture/{prefecture}/education_level/{education_level}", ['guest', 'user'], ['get']],
            ["/open-data/api/schools/eduadmin/{eduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/schools/regioneduadmin/{regioneduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/schools/prefecture/{prefecture}", ['guest', 'user'], ['get']],
            ["/open-data/api/school/{registry_no:[0-9]+}/application_items", ['guest', 'user'], ['get']],
            ["/open-data/api/school/{registry_no:[0-9]+}/new_application_items", ['guest', 'user'], ['get']],
            ["/open-data/api/applications/eduadmin/{eduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/applications/regioneduadmin/{regioneduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/applications/prefecture/{prefecture}", ['guest', 'user'], ['get']],
            ["/open-data/api/application_items/eduadmin/{eduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/application_items/regioneduadmin/{regioneduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/application_items/prefecture/{prefecture}", ['guest', 'user'], ['get']],
            ["/open-data/api/new_applications/eduadmin/{eduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/new_applications/regioneduadmin/{regioneduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/new_applications/prefecture/{prefecture}", ['guest', 'user'], ['get']],
            ["/open-data/api/new_application_items/eduadmin/{eduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/new_application_items/regioneduadmin/{regioneduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/new_application_items/prefecture/{prefecture}", ['guest', 'user'], ['get']],
            ["/open-data/api/approved/eduadmin/{eduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/approved/regioneduadmin/{regioneduadmin}", ['guest', 'user'], ['get']],
            ["/open-data/api/approved/prefecture/{prefecture}", ['guest', 'user'], ['get']],
        ]);
        $container['settings']->set('acl', $acl);
    });

    $events('on', 'app.services', function ($container) {
        $specs = $container['data_retrieve_query_specs'];
        $data_retrieve_query_types = array_keys($specs);

        // root api page handler 
        $container[GrEduLabs\OpenData\Service\IndexProvider::class] = function ($c) {
            $settings = $c->get('settings');
            return new GrEduLabs\OpenData\Service\IndexProvider((string) $settings['api_doc_url'], $c['router']);
        };
        $container[GrEduLabs\OpenData\Action\Index::class] = function ($c) {
            return new GrEduLabs\OpenData\Action\Index(
                $c, $c->get(GrEduLabs\OpenData\Service\IndexProvider::class), true
            );
        };

        // fully fledged /prefectures{/<name>} ??
        $container[GrEduLabs\OpenData\Service\PrefecturesProvider::class] = function ($c) {
            return new GrEduLabs\OpenData\Service\PrefecturesProvider();
        };
        $container[GrEduLabs\OpenData\Action\Prefectures::class] = function ($c) {
            return new GrEduLabs\OpenData\Action\Prefectures(
                $c, $c->get(GrEduLabs\OpenData\Service\PrefecturesProvider::class), true
            );
        };

        // item categories available
        // NEW for demo
        $container[GrEduLabs\OpenData\Service\ItemCategoryNamesProvider::class] = function ($c) {
            return new GrEduLabs\OpenData\Service\ItemCategoryNamesProvider();
        };
        $container[GrEduLabs\OpenData\Action\ItemCategoryNames::class] = function ($c) {
            return new GrEduLabs\OpenData\Action\ItemCategoryNames(
                $c, $c->get(GrEduLabs\OpenData\Service\ItemCategoryNamesProvider::class), false
            );
        };

        // all school id and names provider 
        // NEW for demo 
        $container[GrEduLabs\OpenData\Action\AllSchools::class . '_provider'] = function ($c) {
            $dataProvider = new GrEduLabs\OpenData\Service\RedBeanQueryPagedDataProvider();
            $dataProvider->setPagesize(10);
            $dataProvider->setPage(1);
            $dataProvider->setQuery('select id, name from school');
            return $dataProvider;
        };
        $container[GrEduLabs\OpenData\Action\AllSchools::class] = function ($c) {
            return new GrEduLabs\OpenData\Action\AllSchools(
                $c, $c->get(GrEduLabs\OpenData\Action\AllSchools::class . '_provider'), true
            );
        };

        // unified wrapper for existing csv exports 
        // Using "raw_" prefix to distinguish from new, pager enabled stuff 
        // BEWARE! no paging!
        foreach ($data_retrieve_query_types as $data_retrieve_query_type) {
            $container["raw_{$data_retrieve_query_type}_provider"] = function ($c) use ($data_retrieve_query_type) {
                return new GrEduLabs\OpenData\Service\CsvExportDataProvider($c, $data_retrieve_query_type);
            };
            $container["raw_{$data_retrieve_query_type}_action"] = function ($c) use ($data_retrieve_query_type) {
                return new GrEduLabs\OpenData\Action\ApiAction(
                    $c, $c->get("raw_{$data_retrieve_query_type}_provider"), false
                );
            };
        }

        // unified paged interface for all previously existing csv exports of open data
        foreach ($data_retrieve_query_types as $data_retrieve_query_type) {
            $spec = $specs[$data_retrieve_query_type];
            $container["{$data_retrieve_query_type}_provider"] = function ($c) use ($spec, $container) {
                $dataProvider = new GrEduLabs\OpenData\Service\RedBeanQueryPagedDataProvider();
                $dataProvider->setPagesize(20);
                $dataProvider->setPage(1);
                $params = (isset($spec['params_callback']) ? $spec['params_callback']($container) : []);
                $dataProvider->setQuery($spec['query'], $params);
                if (isset($spec['data_post_handle_callback'])) {
                    $dataProvider->setDataPostHandleCallback($spec['data_post_handle_callback']);
                }
                if (isset($spec['headers'])) {
                    $dataProvider->setLabels($spec['headers']);
                }
                return $dataProvider;
            };
            $container["{$data_retrieve_query_type}_action"] = function ($c) use ($data_retrieve_query_type) {
                return new GrEduLabs\OpenData\Action\PagedApiAction(
                    $c, $c->get("{$data_retrieve_query_type}_provider"), true
                );
            };
        }

        // fully fledged /schools{/<prefecture>} /schools{/education_level} ??
        $container[GrEduLabs\OpenData\Action\SchoolsFilteredAction::class . "_provider"] = function ($c) use ($specs) {
            $dataProvider = new GrEduLabs\OpenData\Service\RedBeanFilteredQueryPagedDataProvider();
            $dataProvider->setLabels($specs['schools']['headers']);
            $dataProvider->setQuery($specs['schools']['query']);
            return $dataProvider;
        };
        $container[GrEduLabs\OpenData\Action\SchoolsFilteredAction::class] = function ($c) {
            return new GrEduLabs\OpenData\Action\SchoolsFilteredAction(
                $c, $c->get(GrEduLabs\OpenData\Action\SchoolsFilteredAction::class . "_provider"), true
            );
        };

        // eduadmin, regioneduadmin and prefecture filter enabled actions 
        foreach (['schools', 'applications', 'application_items', 'new_applications', 'new_application_items', 'approved'] as $spec_key) {
            $container[GrEduLabs\OpenData\Action\EduadminFilteredPagedApiAction::class . "_{$spec_key}_provider"] = function ($c) use ($specs, $spec_key) {
                $spec = $specs[$spec_key];
                $dataProvider = new GrEduLabs\OpenData\Service\RedBeanFilteredQueryPagedDataProvider();
                $dataProvider->setLabels($spec['headers']);
                $dataProvider->setQuery($spec['query'], isset($spec['params_callback']) ? $spec['params_callback']($c) : []);
                return $dataProvider;
            };
            $container["{$spec_key}_filtered_action"] = function ($c) use ($spec_key) {
//                return new GrEduLabs\OpenData\Action\EduadminFilteredPagedApiAction(
                return new GrEduLabs\OpenData\Action\PrefectureEduadminFilteredPagedApiAction(
                    $c, $c->get(GrEduLabs\OpenData\Action\EduadminFilteredPagedApiAction::class . "_{$spec_key}_provider"), true
                );
            };
        }

        // application items by school 
        $container["application_items_of_school_filtered_action"] = function ($c) {
            return new GrEduLabs\OpenData\Action\RegistryEduadminFilteredPagedApiAction(
                $c, $c->get(GrEduLabs\OpenData\Action\EduadminFilteredPagedApiAction::class . "_application_items_provider"), true
            );
        };
        $container["new_application_items_of_school_filtered_action"] = function ($c) {
            return new GrEduLabs\OpenData\Action\RegistryEduadminFilteredPagedApiAction(
                $c, $c->get(GrEduLabs\OpenData\Action\EduadminFilteredPagedApiAction::class . "_new_application_items_provider"), true
            );
        };
    });

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        $data_retrieve_query_types = array_keys($c['data_retrieve_query_specs']);
        $router = $c['router'];

        $app->get('/open-data', function (Request $req, Response $res) use ($c) {
            $view = $c->get('view');
            $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

            return $view->render($res, 'open_data/index.twig');
        })->setName('open_data');

        /**
         * Define api routes. Each route is handled by a devoted class.
         */
        $app->group('/open-data/api', function () use ($data_retrieve_query_types, $router) {
            $this->get('', function (Request $request, Response $response) use ($router) {
                    return $response->withStatus(302)->withHeader('Location', $router->pathFor('open_data.api.index'));
                })
                ->setName('open_data.api');
            $this->get('/index', Action\Index::class)
                ->setName('open_data.api.index');

            $this->get('/prefectures', Action\Prefectures::class)
                ->setName('open_data.api.prefectures');
            $this->get('/prefecture/{name}', Action\Prefectures::class)
                ->setName('open_data.api.prefecture');

            $this->get('/itemcategorynames', Action\ItemCategoryNames::class)
                ->setName('open_data.api.itemcategorynames');

            $this->get('/allschools', Action\AllSchools::class)
                ->setName('open_data.api.allschools');

            // raw, unpaged exports 
            foreach ($data_retrieve_query_types as $data_retrieve_query_type) {
                $this->get("/raw_{$data_retrieve_query_type}", "raw_{$data_retrieve_query_type}_action")
                    ->setName("open_data.api.raw_{$data_retrieve_query_type}");
            }

            // page enabled actions 
            foreach ($data_retrieve_query_types as $data_retrieve_query_type) {
                $this->get("/{$data_retrieve_query_type}", "{$data_retrieve_query_type}_action")
                    ->setName("open_data.api.{$data_retrieve_query_type}");
            }

            // custom filter enabled actions for schools 
//            $this->get('/schools/prefecture/{prefecture}', Action\SchoolsFilteredAction::class)
//                ->setName('open_data.api.schools.prefecture');
            $this->get('/schools/education_level/{education_level}', Action\SchoolsFilteredAction::class)
                ->setName('open_data.api.schools.education_level');
            $this->get('/schools/prefecture/{prefecture}/education_level/{education_level}', Action\SchoolsFilteredAction::class)
                ->setName('open_data.api.schools.prefecture_education_level');

            // eduadmin, regioneduadmin and prefecture filter enabled actions 
            foreach (['schools', 'applications', 'application_items', 'new_applications', 'new_application_items', 'approved'] as $spec_key) {
                $this->get("/{$spec_key}/eduadmin/{eduadmin}", "{$spec_key}_filtered_action")
                    ->setName("open_data.api.{$spec_key}.eduadmin");
                $this->get("/{$spec_key}/regioneduadmin/{regioneduadmin}", "{$spec_key}_filtered_action")
                    ->setName("open_data.api.{$spec_key}.regioneduadmin");
                $this->get("/{$spec_key}/prefecture/{prefecture}", "{$spec_key}_filtered_action")
                    ->setName("open_data.api.{$spec_key}.prefecture");
            }

            // application items by school 
            $this->get("/school/{registry_no:[0-9]+}/application_items", "application_items_of_school_filtered_action")
                ->setName("open_data.api.school.application_items");
            $this->get("/school/{registry_no:[0-9]+}/new_application_items", "new_application_items_of_school_filtered_action")
                ->setName("open_data.api.school.new_application_items");
        });
    });
};
