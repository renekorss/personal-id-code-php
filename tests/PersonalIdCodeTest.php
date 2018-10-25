<?php
/**
 * RKD Blockchain.
 *
 * @link https://github.com/renekorss/personal-id-code-php/
 *
 * @author Rene Korss <rene.korss@gmail.com>
 * @copyright 2018 Rene Korss
 * @license MIT
 */

namespace RKD\PersonalIdCode;

use PHPUnit\Framework\TestCase;

/**
 * PersonalIdCode class tests
 *
 * @author Rene Korss <rene.korss@gmail.com>
 */

final class PersonalIdCodeTests extends TestCase
{
    public function testCanInitClass() : void
    {
        $id = new PersonalIdCode('39002102761');
        $this->assertInstanceOf(PersonalIdCode::class, $id);
    }
}
