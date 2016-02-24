<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class InputFilterLab
{
    private $inputFilter;
    
    public function __construct(callable $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }
    
    public function __invoke(Request $req, Response $res, callable $next)
    {
        $data        = array_merge_recursive($req->getParams(), $req->getUploadedFiles());
        $inputFilter = $this->inputFilter;
        $result      = $inputFilter($data);

        var_dump($req->getParams(), $req->getUploadedFiles(), $data);
        die();
        
        if (!$result['is_valid']) {
            $res = $res->withStatus(422, 'validation error');
            $res->withJson($result);

            return $res;
        }

        $req = $req->withParsedBody($result['values']);

        return $next($req, $res);
    }
}
