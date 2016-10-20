<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\UniversityForm\Action;

use Exception;
use GrEduLabs\UniversityForm\Service\UniversityFormInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class UniversityForm
{
    private $service;

    public function __construct(UniversityFormServiceInterface $service)
    {
        $this->view = $view;
        $this->service = $service;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {

        $id                  = $params['id'];
        $params['id'] = $univ->id;
        try {
                $univ  = $this->service->createuniv($params);
                $res  = $res->withStatus(201);
                $res = $res->withJson($univ);
        } catch (Exception $ex) {
            $res = $res->withStatus(500, $ex->getMessage());
        }

        return $res;
    }
}
