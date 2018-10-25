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
    /**
     * Instance of \RKD\PersonalIdCode
     */
    private $id;

    protected function setUp() : void
    {
        $this->id = new PersonalIdCode('39002102761');
    }

    public function testCanInitClass() : void
    {
        $this->assertInstanceOf(PersonalIdCode::class, $this->id);
    }

    public function testCanGetGender() : void
    {
        $this->assertEquals(PersonalIdCode::GENDER_MALE, $this->id->getGender());

        $femaleId = new PersonalIdCode('49002102761');
        $this->assertEquals(PersonalIdCode::GENDER_FEMALE, $femaleId->getGender());
    }
}
