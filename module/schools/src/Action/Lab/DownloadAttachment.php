<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Action\Lab;

use GrEduLabs\Schools\Service\LabServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class DownloadAttachment
{
    private $labService;

    private $uploadDir;

    public function __construct(LabServiceInterface $labService, $uploadDir)
    {
        $this->labService = $labService;
        $this->uploadDir  = $uploadDir;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }

        $lab_id = $req->getParam('lab_id', false);
        if (!$lab_id) {
            return $res->withStatus(404, 'No lab id');
        }

        $lab = $this->labService->getLabForSchool($school->id, $lab_id);

        if ($lab['attachment'] && is_readable($this->uploadDir . '/' . $lab['attachment'])) {
            $contents    = file_get_contents($this->uploadDir . '/' . $lab['attachment']);
            $contentType = $lab['attachment_mime'] ? $lab['attachment_mime'] : 'application/octet-stream';
            $res         = $res->withHeader('Content-Type', $contentType);
            $res         = $res->withHeader(
                'Content-Disposition',
                'filename="' . basename($lab['attachment']) . '"'
            );
            $res->getBody()->write($contents);
        } else {
            $res->withStatus(404, 'No attachment');
        }

        return $res;
    }
}
