<?php

/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\TpeSurvey\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class SurveyFormDefaults
{
    private static $knowledgeLevels = ['ΜΕΤΡΙΑ', 'ΚΑΛΑ', 'ΠΟΛΥ ΚΑΛΑ', 'ΑΡΙΣΤΑ'];
    private static $assetsInUse     = ['Η/Υ', 'TABLET', 'ΔΣΔ (Διαδραστικό Σύστημα Διδασκαλίας)', 'ΒΙΝΤΕΟΠΡΟΒΟΛΕΑΣ', 'ΚΙΤ ΡΟΜΠΟΤΙΚΗΣ'];
    private static $softwareInUse   = [
        'sw_web2' => [
            'label' => 'WEB 2.0 ΕΡΓΑΛΕΙΑ',
            'desc'  => 'Περιγράψτε, πχ socrative, padlet, wiki κλπ',
        ],
        'sw_packages' => [
            'label' => 'ΕΚΠΑΙΔΕΥΤΙΚΑ ΠΑΚΕΤΑ ΛΟΓΙΣΜΙΚΟΥ',
            'desc'  => 'Περιγράψτε, πχ interactive physics, modelus, geogebra κλπ',
        ],
        'sw_digitalschool' => [
            'label' => 'ΦΩΤΟΔΕΝΤΡΟ/ΨΗΦΙΑΚΟ ΣΧΟΛΕΙΟ',
            'desc'  => 'Περιγράψτε, πχ εμπλουτισμένα βιβλία, εφαρμογές φωτόδενδρου (ποιές), υπηρεσίες (ποιές)',
        ],
        'sw_other' => [
            'label' => 'ΑΛΛΟ',
            'desc'  => 'Περιγράψτε',
        ],
    ];
    private static $useCase = [
        'uc_eduprograms' => [
            'label' => 'ΣΥΜΜΕΤΟΧΗ ΣΕ ΠΡΟΓΡΑΜΜΑΤΑ',
            'desc'  => 'Περιγράψτε, πχ eTwinning, Scientix,  Erasmus+, άλλο',
        ],
        'uc_digitaldesign' => [
            'label' => 'ΨΗΦΙΑΚΑ ΣΧΕΔΙΑ ΜΑΘΗΜΑΤΩΝ',
            'desc'  => 'Περιγράψτε',
        ],
        'uc_asyncedu' => [
            'label' => 'ΑΣΥΓΧΡΟΝΗ ΔΙΔΑΣΚΑΛΙΑ',
            'desc'  => 'Περιγράψτε, πχ moodle, η-τάξη, άλλο',
        ],
        'uc_other' => [
            'label' => 'ΑΛΛΟ',
            'desc'  => 'Περιγράψτε',
        ],
    ];
    /**
     * 
     * @var Twig
     */
    private $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $req, Response $res, callable $next)
    {
        $map = function ($value) {
            return ['value' => $value, 'label' => $value];
        };

        if ($req->isGet()) {
            $this->view['tpe_survey'] = [
                'knowledge_levels' => array_map($map, self::$knowledgeLevels),
                'assets_in_use'    => array_map($map, self::$assetsInUse),
                'software_in_use'  => self::$softwareInUse,
                'use_case'         => self::$useCase,
            ];
        }

        return $next($req, $res);
    }
}
