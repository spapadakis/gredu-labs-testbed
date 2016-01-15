<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabstest\Authentication;

use GrEduLabs\Authentication\Identity;

class IdentityTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $identity = new Identity(
            'someUid',
            'some@mail.com',
            'Jonh Doe',
            'Office'
        );

        $this->assertAttributeSame('someUid', 'uid', $identity);
        $this->assertAttributeSame('some@mail.com', 'mail', $identity);
        $this->assertAttributeSame('Jonh Doe', 'displayName', $identity);
        $this->assertAttributeSame('Office', 'officeName', $identity);
    }
}
