<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Action\Software;

use GrEduLabs\Schools\Service\SoftwareServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ListAll
{
    private $view;
    private $softwareService;

    public function __construct(Twig $view, SoftwareServiceInterface $softwareService)
    {
        $this->view         = $view;
        $this->softwareService = $softwareService;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }
        $software   = $this->softwareService->getSoftwareBySchoolId($school->id);
        $categories = $this->softwareService->getSoftwareCategories();
        return $this->view->render($res, 'schools/software.twig', [
            'softwareArray'     => $software,
            'categories'   => $categories
        ]);
    }
}
