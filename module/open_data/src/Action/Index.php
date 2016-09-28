<?php

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\open_data\Action;

use GrEduLabs\open_data\Service\ODAServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;

class Index {

    protected $odaService;
    protected $ci;

    public function __construct(
    Container $ci, ODAServiceInterface $odaservice
    ) {
        $this->ci = $ci;
        $this->odaService = $odaservice;
    }

    public function __invoke(Request $req, Response $res, array $args = []) {

        $queryType = strtoupper($req->getQueryParam('queryType', ''));
        $outputFormat = strtoupper($req->getQueryParam('outputFormat', 'JSON'));
        $data = ['Wrong URL' => 'Something mistyped maybe'];
        switch ($queryType) {
            case 'SCHOOLS' :
                $data = $this->odaService->getSchools();
                break;

            case 'LABS' :
                $data = $this->odaService->getLabs();
                break;

            case 'ASSETS' :
                $data = $this->odaService->getAssets();
                break;

            case 'APPFORMS' :
                $data = $this->odaService->getAppForms();
                break;

            case 'APPFORMSITEMS' :
                $data = $this->odaService->getAppFormsItems();
                break;

            case 'SOFTWARE' :
                $data = $this->odaService->getSoftwareItems();
                break;
        }

        switch ($outputFormat) {
            case 'JSON' :
                return $res->withJson($data);

            case 'XML':
                if (is_scalar($data) || is_object($data) || is_resource($data)) {
                    $data = [$data];
                }
                $body = $res->getBody();
                $body->rewind();
                // TODO consider just printing the XML; no need for searchable xml... 
                // TODO consider using sabre/xml 
                $xml = new \SimpleXMLElement('<edulabsresults/>');
                foreach ($data as $k => $v) {
                    $item = $xml->addChild('item');
                    $item->addChild('key', $k);
                    if (!is_scalar($v)) {
                        if (is_array($v)) {
                            $value = $item->addChild('value');
                            foreach ($v as $k2 => $v2) {
                                $value->addChild($k2, is_scalar($v2) ? $v2 : null);
                            }
                        } else {
                            $item->addChild('value', null);
                        }
                    } else {
                        $item->addChild('value', $v);
                    }
                }
                $body->write($xml->asXml());
                $res = $res->withHeader('Content-Type', 'application/xml;charset=utf-8');
                if (isset($status)) {
                    return $res->withStatus($status);
                }
                return $res;
                break;

            case 'HTML' :
                $view = $this->ci->get('view');
                $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/../../templates');
                $view['data'] = $data;
                return $view->render($res, 'open_data/api-html-generic.twig');
                break;
        }
    }

}
