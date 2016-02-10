<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchSync\Listener;

use GrEduLabs\Authentication\Identity;
use RedBeanPHP\R;
use Zend\Ldap\Dn;
use Zend\Ldap\Filter;
use Zend\Ldap\Ldap;

class CreateSchool
{
    /**
     * @var Ldap
     */
    private $ldap;

    /**
     * @var callable
     */
    private $fetchUnit;

    public function __construct(Ldap $ldap, callable $fetchUnitFromMM)
    {
        $this->ldap      = $ldap;
        $this->fetchUnit = $fetchUnitFromMM;
    }

    public function __invoke(callable $stop, Identity $identity)
    {
        $registryNo = $this->findUnitRegitryNo($identity);
        if (null === $registryNo) {
            $stop();
        }
        $registryNo = ($registryNo === '1111111') ? '0601010' : $registryNo;

        $unit = call_user_func($this->fetchUnit, $registryNo);
        if (null === $unit) {
            $stop();
        }

        $school = R::findOne('school', 'registryNo = ?', [$registryNo]);
        try {
            if (!$school) {
                $school                    = R::dispense('school');
                $school->name              = $unit['name'];
                $school->streetAddress     = $unit['street_address'];
                $school->postalCode        = $unit['postal_code'];
                $school->phoneNumber       = $unit['phone_number'];
                $school->faxNumber         = $unit['fax_number'];
                $school->email             = $unit['email'];
                $school->municipality      = $unit['municipality'];
                $school->schooltype_id     = $unit['unit_type_id'];
                $school->prefecture_id     = $unit['prefecture_id'];
                $school->educationlevel_id = $unit['education_level_id'];
                $school->eduadmin_id       = $unit['edu_admin_id'];
                $school->created           = time();
                $school->creator           = $identity->mail;
                R::store($school);
            }
        } catch (\Exception $e) {
            // todo handle exceptions
            die('ERROR');
        }
    }

    private function findUnitRegitryNo(Identity $identity)
    {
        $filter = Filter::equals('mail', $identity->mail);
        $baseDn = Dn::factory($this->ldap->getBaseDn())->prepend(['ou' => 'people']);
        $result = $this->ldap->search($filter, $baseDn, Ldap::SEARCH_SCOPE_ONE, ['l']);

        if (1 !== $result->count()) {
            return;
        }
        $result = $result->current();
        $unitDn = $result['l'][0];

        $unit = $this->ldap->getNode($unitDn);

        return $unit->getAttribute('gsnunitcode', 0);
    }
}
