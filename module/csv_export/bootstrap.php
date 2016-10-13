<?php

use League\Csv\Reader;
use League\Csv\Writer;
use RedBeanPHP\R;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

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

    $events('on', 'app.services', function (Container $c) {

        $c['csv_export_config'] = function ($c) {

            return [
                'schools' => [
                    'data_callback' => 'csv_export_schools',
                    'headers' => [
                        'Κωδικός μονάδας',
                        'Ονομασία',
                        'Τύπος μονάδας',
                        'Περιφερειακή ενότητα',
                        'Διεύθυνση εκπαίδευσης',
                        'Περιφερειακή διεύθυνση εκπαίδευσης',
                        'Βαθμίδα εκπαίδευσης',
                    ],
                ],
                'labs' => [
                    'data_callback' => 'csv_export_labs',
                    'headers' => [
                        'ID',
                        'Κωδικός σχολείου',
                        'Ονομασία σχολείου',
                        'Ονομασία χώρου',
                        'Τύπος χώρου',
                        'Ειδικότητα υπευθύνου',
                        'Νέος χώρος',
                        'Επιφάνεια (m^2)',
                        'Σύνδεση στο δίκτυο',
                        'Διαθέτει server',
                        'Μαθήματα',
                        'Χρήση στα πλαίσια μαθημάτων',
                        'Χρήση για δραστηριότητες εκτός εκπαιδευτικού προγράμματος',
                    ],
                ],
                'assets' => [
                    'data_callback' => 'csv_export_assets',
                    'headers' => [
                        'Είδος',
                        'Πλήθος ',
                        'Έτος κτήσης',
                        'ID χώρου',
                        'Τύπος χώρου',
                        'Κωδικός σχολείου',
                        'Ονομασία σχολείου',
                        'Σχόλια - Παρατηρήσεις',
                    ],
                ],
                 'software' => [
                    'data_callback' => 'csv_export_software',
                    'headers' => [
                        'Τύπος',
                        'Κωδικός σχολείου',
                        'Ονομασία σχολείου',
                        'ID χώρου',
                        'Τύπος χώρου',
                        'Ονομασία',
                        'Κατασκευαστής',
                        'URL',
                    ],
                ],
                'appforms' => [
                    'data_callback' => 'csv_export_appforms',
                    'headers' => [
                        'ID',
                        'Κωδικός σχολείου',
                        'Ονομασία σχολείου',
                        'Ημερομηνία υποβολής',
                        'Σχόλια - Παρατηρήσεις',
                    ],
                ],
             
                'appnewforms' => [
                    'data_callback' => 'csv_export_appnewforms',
                    'headers' => [
                        'ID',
                        'Κωδικός σχολείου',
                        'Ονομασία σχολείου',
                        'Ημερομηνία υποβολής',
                        'Σχόλια - Παρατηρήσεις',
                    ],
                ],
             

                'appforms_items' => [
                    'data_callback' => 'csv_export_appforms_items',
                    'headers' => [
                        'ID',
                        'Κωδικός σχολείου',
                        'Ονομασία σχολείου',
                        'Ημερομηνία υποβολής',
                        'ID χώρου',
                        'Τύπος χώρου',
                        'Νέος χώρος',
                        'Είδος',
                        'Πλήθος ',
                        'Αιτιολογία χρήσης',
                    ],
                ],

                'newapplication' => [
                    'data_callback' => 'csv_export_newapplication',
                    'headers' => [
                        'ID',
                        'Κωδικός σχολείου',
                        'Ονομασία σχολείου',
                        'Είδος',
                        'Πλήθος Υπαρχόντων που λειτουργούν',
                        'Πλήθος Αιτουμένων',
                        'Αιτιολογία χρήσης',
                    ],
                ],

                'sch_approved' => [
                    'data_callback' => 'csv_export_sch_approved',
                    'headers' => [
                        'ID',
                        'Κωδικός σχολείου',
                        'Ονομασία σχολείου',
                    ],
                ]
            ];
        };

        $c['csv_export_csv_response'] = function ($c) {

            return function ($res, callable $dataCallback, array $headers, $filename) use ($c) {
                $csvFactory = $c['csv_export_csv_factory'];
                $csv = $csvFactory($dataCallback(), $headers);
                $res = $res->withHeader('Content-Type', 'application/octet-stream');
                $res = $res->withHeader('Content-Transfer-Encoding', 'binary');
                $res = $res->withHeader('Content-Disposition', 'attachment; filename=' . $filename);
                $res->write($csv);

                return $res;
            };
        };

        $c['csv_export_csv_factory'] = function ($c) {

            return function (array $data, array $headers = null) {
                $csv = Writer::createFromFileObject(new SplTempFileObject());
                $csv->setOutputBOM(Reader::BOM_UTF8);
                $csv->addFormatter(function ($row) {
                    return array_map(function ($value) {
                        return str_replace(["\n", "\r"], ' ', $value);
                    }, $row);
                });
                if (null !== $headers) {
                    $csv->insertOne($headers);
                }

                $csv->insertAll($data);

                return $csv;
            };
        };

        $c['csv_export_schools'] = function ($c) {

            return function () {
                $sql = 'SELECT school.registry_no AS registry_no, '
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
                        . ' ORDER BY school_name';
                $schools = R::getAll($sql);

                return $schools;
            };
        };

        $c['csv_export_labs'] = function ($c) {

            return function () {
                $sql = 'SELECT lab.id AS id, '
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
                        . ' ORDER BY school_name ';

                $labs = R::getAll($sql);

                return $labs;
            };
        };

        $c['csv_export_assets'] = function ($c) {

            return function () {
                $sql = 'SELECT TRIM(itemcategory.name) AS category, '
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
                        . ' ORDER BY lab.id';

                $assets = R::getAll($sql);

                return $assets;
            };
        };

  


   $c['csv_export_software'] = function ($c) {

            return function () {
                $sql = 'SELECT softwarecategory.name AS name, '
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
                        . ' ORDER BY school_name ';

                $software = R::getAll($sql);

                $software = array_map(function ($row) {
                    $row['url'] = strtolower($row['url']);
                    $row['url'] = str_replace('\\', '/', $row['url']);
                    $row['url'] = urldecode($row['url']);

                    return $row;
                }, $software);

                return $software;
            };
        };









        $c['csv_export_appforms'] = function ($c) {

            //return function () {
        	 return function () use ($c) {
				$settings = $c->get('settings');
                $version = $settings['application_form']['itemcategory']['currentversion'];

                $appFormIdsSql = 'SELECT id FROM applicationform WHERE (submitted)IN (SELECT MAX( submitted )FROM applicationform '
                				. ' LEFT JOIN applicationformitem ON applicationformitem.applicationform_id = applicationform.id '
								. ' LEFT JOIN school ON applicationform.school_id = school.id'
								. ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id'
								. ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id'
								. ' LEFT JOIN labtype ON lab.labtype_id = labtype.id'
								. ' WHERE itemcategory.groupflag NOT IN(' . $version . ')'
								. ' GROUP BY school.id)';



                $appFormIds = R::getCol($appFormIdsSql);

                if (empty($appFormIds)) {
                    return [];
                }

                $in = implode(',', array_fill(0, count($appFormIds), '?'));


				 $sql = 'SELECT applicationform.id AS id, '
                       . ' school.registry_no AS school_registry_no, '
                        . ' school.name AS school_name, '
                        . ' FROM_UNIXTIME(applicationform.submitted) AS submitted, '
                        . ' TRIM(applicationform.comments) AS comments' 
                        . ' FROM applicationformitem '
                        . ' LEFT JOIN applicationform ON applicationformitem.applicationform_id = applicationform.id '
                        . ' LEFT JOIN school ON applicationform.school_id = school.id '
                        . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id '
                        . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id '
                        . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                        . ' WHERE applicationform.id IN(' . $in . ')'
                          . ' GROUP BY school.id ';

                $appForms = R::getAll($sql, $appFormIds);

                return $appForms;


              //  $sql = 'SELECT applicationform.id AS id, '
              //          . ' school.registry_no AS school_registry_no, '
              //          . ' school.name AS school_name, '
               //         . ' FROM_UNIXTIME(applicationform.submitted) AS submitted, '
               //         . ' TRIM(applicationform.comments) AS comments '
               //         . ' FROM applicationform '
                //        . ' LEFT JOIN school ON applicationform.school_id = school.id '
                //        . ' GROUP BY school.id '
                //        . ' HAVING MAX(applicationform.submitted)';

//                $appForms = R::getAll($sql);

  //              return $appForms;
            };
        };


        $c['csv_export_appnewforms'] = function ($c) {

       return function () use ($c) {


                $appFormIdsSql = 'SELECT id FROM applicationform WHERE (submitted) IN( SELECT MAX(submitted) FROM applicationform GROUP BY school_id)';

                $appFormIds = R::getCol($appFormIdsSql);


                if (empty($appFormIds)) {
                    return [];
                }
                $in = implode(',', array_fill(0, count($appFormIds), '?'));


                $settings = $c->get('settings');
                $version = $settings['application_form']['itemcategory']['currentversion'];

                $sql = 'SELECT applicationform.id AS id, '
                       . ' school.registry_no AS school_registry_no, '
                        . ' school.name AS school_name, '
                        . ' FROM_UNIXTIME(applicationform.submitted) AS submitted, '
                        . ' TRIM(applicationform.comments) AS comments' 
                        . ' FROM applicationformitem '
                        . ' LEFT JOIN applicationform ON applicationformitem.applicationform_id = applicationform.id '
                        . ' LEFT JOIN school ON applicationform.school_id = school.id '
                        . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id '
                        . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id '
                        . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                        . ' WHERE applicationform.id IN(' . $in . ')'
                        . ' AND itemcategory.groupflag IN(' . $version . ')'
                        . ' GROUP BY school.id ';

                $appForms = R::getAll($sql, $appFormIds);

                return $appForms;
            };
        };



        $c['csv_export_appforms_items'] = function ($c) {

            return function () use ($c){

            	$settings = $c->get('settings');
                $version = $settings['application_form']['itemcategory']['currentversion'];

                $appFormIdsSql = 'SELECT id FROM applicationform WHERE (submitted)IN (SELECT MAX( submitted )FROM applicationform '
                				. ' LEFT JOIN applicationformitem ON applicationformitem.applicationform_id = applicationform.id '
								. ' LEFT JOIN school ON applicationform.school_id = school.id'
								. ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id'
								. ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id'
								. ' LEFT JOIN labtype ON lab.labtype_id = labtype.id'
								. ' WHERE itemcategory.groupflag NOT IN(' . $version . ')'
								. ' GROUP BY school.id)';




              //  $appFormIdsSql = 'SELECT applicationform.id '
              //     . ' FROM applicationform '
              //     . ' GROUP BY school_id '
              //    . ' HAVING MAX(applicationform.submitted)';

                $appFormIds = R::getCol($appFormIdsSql);

                if (empty($appFormIds)) {
                    return [];
                }

                $in = implode(',', array_fill(0, count($appFormIds), '?'));
                $sql = 'SELECT applicationform.id AS id, '
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
                        . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id '
                        . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id '
                        . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                        . ' WHERE applicationform.id IN(' . $in . ') ';

                $appForms = R::getAll($sql, $appFormIds);

                return $appForms;
            };
        };

     
        $c['csv_export_newapplication'] = function ($c) {

            return function () use ($c) {


                $appFormIdsSql = 'SELECT id FROM applicationform WHERE (submitted) IN( SELECT MAX(submitted) FROM applicationform GROUP BY school_id)';

                $appFormIds = R::getCol($appFormIdsSql);


                if (empty($appFormIds)) {
                    return [];
                }
                $in = implode(',', array_fill(0, count($appFormIds), '?'));


                $settings = $c->get('settings');
                $version = $settings['application_form']['itemcategory']['currentversion'];

                $sql = 'SELECT applicationform.id AS id, '
                        . ' school.registry_no AS school_registry_no, '
                        . ' school.name AS school_name, '
                        . ' TRIM(itemcategory.name) AS category, '
                        . ' applicationformitem.qty AS qty, '
                        . ' applicationformitem.qtyacquired AS qtyacquired, '
                        . ' TRIM(applicationformitem.reasons) AS reasons '
                        . ' FROM applicationformitem '
                        . ' LEFT JOIN applicationform ON applicationformitem.applicationform_id = applicationform.id '
                        . ' LEFT JOIN school ON applicationform.school_id = school.id '
                        . ' LEFT JOIN itemcategory ON applicationformitem.itemcategory_id = itemcategory.id '
                        . ' LEFT JOIN lab ON applicationformitem.lab_id = lab.id '
                        . ' LEFT JOIN labtype ON lab.labtype_id = labtype.id '
                        . ' WHERE applicationform.id IN(' . $in . ')'
                        . ' AND itemcategory.groupflag IN(' . $version . ')';


                $appForms = R::getAll($sql, $appFormIds);

                return $appForms;
            };
        };


        $c['csv_export_sch_approved'] = function ($c) {

            return function () {
		         $sql = 'SELECT applicationform.id AS id, '
                        . ' school.registry_no AS school_registry_no, '
                        . ' school.name AS school_name '
                        . ' FROM applicationform'
                        . ' LEFT JOIN school ON applicationform.school_id = school.id '
                		. '	WHERE applicationform.approved = 1';	

                $sch_appr = R::getAll($sql);

                return $sch_appr;
            };
        };



    });

    $events('on', 'app.bootstrap', function (App $app, Container $c) {

        $app->get('/export/csv/edulabs_{type}.csv', function (Request $req, Response $res, array $args) use ($c) {
            $type = $args['type'];
            try {
                $config = $c['csv_export_config'];

                if (!array_key_exists($type, $config)) {
                    return $res->withStatus(404);
                }

                $typeConfig = $config[$type];
                $csvResponse = $c['csv_export_csv_response'];

                return $csvResponse($res, $c[$typeConfig['data_callback']], $typeConfig['headers'], 'edulabs_' . $type . '.csv');
            } catch (Exception $ex) {
                $flash = $c['flash'];
                $log = $c['logger'];
                $router = $c['router'];
                $flash->addMessage('warning', 'Προέκυψε κάποιο σφάλμα. Δοκιμάστε αργότερα');
                $log->error(sprintf('Problem downloading %s file. Exception message %s', $type, $ex->getMessage()));
                $log->debug('Exception Trace', ['trace' => $ex->getTraceAsString()]);

                return $res->withRedirect($router->pathFor('index'));
            }
            $config = $c['csv_export_config'];

            if (!array_key_exists($type, $config)) {
                return $res->withStatus(404);
            }

            $typeConfig = $config[$type];
            $csvResponse = $c['csv_export_csv_response'];

            return $csvResponse($res, $c[$typeConfig['data_callback']], $typeConfig['headers'], 'edulabs_' . $type . '.csv');
        })->setName('export.csv');
    });
};
