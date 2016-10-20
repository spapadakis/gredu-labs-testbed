<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\UniversityForm\Service;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class UniversityFormService implements UniversityFormServiceInterface
{
    public function submit(array $data)
    {
        $appForm                      = R::dispense('univ');
        $appForm->idrima              = $data['idrima'];
        $appForm->sxolh               = $data['sxolh'];
        $appForm->tmhma               = $data['tmhma'];
        $appForm->person              = $data['person'];
        $appForm->telef               = $data['telef'];
        $appForm->email               = $data['email'];
 
        R::store($appForm);

   
    }

}
