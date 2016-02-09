<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

return function (Slim\App $app) {

    $container = $app->getContainer();

    $container['ldap'] = function ($c) {
        $settings = $c['settings']['ldap'];

        return new Zend\Ldap\Ldap($settings);
    };

    $events = $container['events'];

    $events('on', 'authenticate.success', function (callable $stop, $identity) use ($container) {
        $ldap = $container['ldap'];
        $filter = Zend\Ldap\Filter::equals('mail', $identity->mail)
            ->addAnd(new Zend\Ldap\Filter\StringFilter($container['settings']['sso']['allowed']));
        $dn = Zend\Ldap\Dn::factory($ldap->getBaseDn())->prepend(['ou' => 'people']);
        $result = $ldap->search($filter, $dn, Zend\Ldap\Ldap::SEARCH_SCOPE_ONE, ['dn']);

        if (0 === $result->count()) {
            $stop();
            $container['authentication_service']->clearIdentity();
        }

    }, 1000);
};
