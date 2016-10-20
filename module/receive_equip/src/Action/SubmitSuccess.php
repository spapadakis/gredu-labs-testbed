<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ReceiveEquip\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class SubmitSuccess
{
    protected $view;

    protected $formUrl;

    public function __construct(Twig $view, $formUrl)
    {
        $this->view    = $view;
        $this->formUrl = $formUrl;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school = $req->getAttribute('school');

        if (!isset($_SESSION['receiveEquipForm']['receiveEquip'])) {
            $res = $res->withRedirect($this->formUrl);

            return $res;
        }
        $receiveEquip = $_SESSION['receiveEquipForm']['receiveEquip'];

        $_SESSION['receiveEquipForm']['receiveEquip'] = null;
        unset($_SESSION['receiveEquipForm']['receiveEquip']);

        return $this->view->render($res, 'receive_equip/submit_success.twig', [
            'school'  => $school,
            'receiveEquip' => $receiveEquip,
        ]);
    }
}
