<?php
/**
 * RKD Personal ID code.
 *
 * @link https://github.com/renekorss/personal-id-code-php/
 *
 * @author Rene Korss <rene.korss@gmail.com>
 * @copyright 2020 Rene Korss
 * @license MIT
 */

namespace RKD\PersonalIdCode;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * PersonalIdCode class tests
 *
 * @author Rene Korss <rene.korss@gmail.com>
 */

final class PersonalIdCodeTest extends TestCase
{
    /**
     * Instance of \RKD\PersonalIdCode
     */
    private $personalCodeObj;

    protected function setUp() : void
    {
        $this->personalCodeObj = new PersonalIdCode('39002102761');
    }

    public function testCanInitClass() : void
    {
        $this->assertInstanceOf(PersonalIdCode::class, $this->personalCodeObj);
    }

    public function testCanGetGender() : void
    {
        $this->assertSame(PersonalIdCode::GENDER_MALE, $this->personalCodeObj->getGender());

        $femaleId = new PersonalIdCode('49002102761');
        $this->assertSame(PersonalIdCode::GENDER_FEMALE, $femaleId->getGender());
    }

    public function testCanGetBirthDate() : void
    {
        $expectedDate = new \Datetime('1990-02-10');
        $this->assertEquals($expectedDate, $this->personalCodeObj->getBirthDate());
    }

    public function testCanGetBirthDateIssue1() : void
    {
        // Issue #1
        $personalCodeObj = new PersonalIdCode('46402122210');
        $expectedDate = new \Datetime('1964-02-12');

        $this->assertEquals($expectedDate, $personalCodeObj->getBirthDate());
    }

    public function testCanGetCurrentAge() : void
    {
        $expectedAge = (new \Datetime())->diff(new \Datetime('1990-02-10'))->y;
        $this->assertSame($expectedAge, $this->personalCodeObj->getAge());
    }

    public function testCanGetAgeForDate() : void
    {
        $baseDate = new \Datetime('2018-11-25');
        $this->assertSame(28, $this->personalCodeObj->getAge($baseDate));

        // Person is year older on/after birthday
        $baseDate = new \Datetime('2019-02-10');
        $this->assertSame(29, $this->personalCodeObj->getAge($baseDate));

        // 50th birthday
        $baseDate = new \Datetime('2040-02-10');
        $this->assertSame(50, $this->personalCodeObj->getAge($baseDate));
    }

    public function testCanGetBirthCentury() : void
    {
        $nineteenthCentury = new PersonalIdCode('19002102761');
        $this->assertSame(1800, $nineteenthCentury->getBirthCentury());

        $twentiethCentury = new PersonalIdCode('49002102761');
        $this->assertSame(1900, $twentiethCentury->getBirthCentury());

        $twentyFirstCentury = new PersonalIdCode('59002102761');
        $this->assertSame(2000, $twentyFirstCentury->getBirthCentury());
    }

    public function testCanGetBirthYear() : void
    {
        // Default: A full numeric representation of a year, 4 digits
        $this->assertSame('1990', $this->personalCodeObj->getBirthYear());

        $formatsAndExpectedValues = [
            'L' => 0, // Whether it's a leap year
            'o' => 1990, // ISO-8601 week-numbering year.
            'y' => 90, // A two digit representation of a year
        ];

        foreach ($formatsAndExpectedValues as $format => $expectedValue) {
            $this->assertEquals($expectedValue, $this->personalCodeObj->getBirthYear($format));
        }
    }

    public function testCanGetBirthMonth() : void
    {
        // Default: Numeric representation of a month, with leading zeros
        $this->assertSame('02', $this->personalCodeObj->getBirthMonth());

        $formatsAndExpectedValues = [
            'F' => 'February', // A full textual representation of a month, such as January or March
            'M' => 'Feb', // A short textual representation of a month, three letters
            'n' => 2, // Numeric representation of a month, without leading zeros
            't' => 28, // Number of days in the given month
        ];

        foreach ($formatsAndExpectedValues as $format => $expectedValue) {
            $this->assertEquals($expectedValue, $this->personalCodeObj->getBirthMonth($format));
        }
    }

    public function testCanGetBirthDay() : void
    {
        // Default: Day of the month, 2 digits with leading zeros
        $this->assertSame('10', $this->personalCodeObj->getBirthDay());

        $formatsAndExpectedValues = [
            'D' => 'Sat', // A textual representation of a day, three letters
            'j' => 10, // Day of the month without leading zeros
            'l' => 'Saturday', // A full textual representation of the day of the week
            'N' => 6, // ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0)
            'S' => 'th', // English ordinal suffix for the day of the month, 2 characters
            'w' => 6, // Numeric representation of the day of the week
            'z' => 40, // The day of the year (starting from 0)
        ];

        foreach ($formatsAndExpectedValues as $format => $expectedValue) {
            $this->assertEquals($expectedValue, $this->personalCodeObj->getBirthDay($format));
        }
    }

    public function testCanGetHospital() : void
    {
        $hospitalTest = [
            '011' => 'Kuressaare Haigla',
            '019' => 'Tartu Ülikooli Naistekliinik, Tartumaa, Tartu',
            220 => 'Ida-Tallinna Keskhaigla, Pelgulinna sünnitusmaja, Hiiumaa, Keila, Rapla haigla, Loksa haigla',
            270 => 'Ida-Viru Keskhaigla (Kohtla-Järve, endine Jõhvi)',
            370 => 'Maarjamõisa Kliinikum (Tartu), Jõgeva Haigla',
            420 => 'Narva Haigla',
            470 => 'Pärnu Haigla',
            490 => 'Pelgulinna Sünnitusmaja (Tallinn), Haapsalu haigla',
            520 => 'Järvamaa Haigla (Paide)',
            570 => 'Rakvere, Tapa haigla',
            600 => 'Valga Haigla',
            650 => 'Viljandi Haigla',
            710 => 'Lõuna-Eesti Haigla (Võru), Põlva Haigla',
            950 => 'Väljaspool Eestit',
            800 => 'Teadmata',
        ];

        foreach ($hospitalTest as $hospitalCode => $expectedResult) {
            $hospitalCode--;
            if ($hospitalCode >= 799) {
                $hospitalCode = $hospitalCode + 2;
            }
            $this->assertSame($expectedResult, (new PersonalIdCode('4900210'.$hospitalCode.'1'))->getHospital());
        }

        // Unknown if born after 2013
        $this->assertSame('Teadmata', (new PersonalIdCode('51302102731'))->getHospital());
    }

    public function testCantUseInvalidYearFormat() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->assertSame('1990', $this->personalCodeObj->getBirthYear('invalidFormat'));
    }

    public function testCantUseInvalidMonthFormat() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->assertSame('02', $this->personalCodeObj->getBirthMonth('invalidFormat'));
    }

    public function testCantUseInvalidDayFormat() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->assertSame('10', $this->personalCodeObj->getBirthDay('invalidFormat'));
    }

    public function testCanValidate() : void
    {
        $this->assertTrue($this->personalCodeObj->validate());

        $invalidPersonalCode = new PersonalIdCode('59002102761');
        $this->assertFalse($invalidPersonalCode->validate());

        $invalidPersonalCode = new PersonalIdCode('59002102761');
        $this->assertFalse($invalidPersonalCode->validate());

        $validPersonalCode = new PersonalIdCode('51107121760');
        $this->assertTrue($validPersonalCode->validate());

        $invalidPersonalCode = new PersonalIdCode('59002102721');
        $this->assertFalse($invalidPersonalCode->validate());

        $invalidPersonalCode = new PersonalIdCode('590021');
        $this->assertFalse($invalidPersonalCode->validate());

        $invalidPersonalCode = new PersonalIdCode('590021');
        $this->assertFalse($invalidPersonalCode->validate());

        $invalidPersonalCode = new PersonalIdCode(59002102721);
        $this->assertFalse($invalidPersonalCode->validate());

        $invalidPersonalCode = new PersonalIdCode('51127121760');
        $this->assertFalse($invalidPersonalCode->validate());
    }

    public function testCorrectControlNumberIssue5() : void
    {
        // Issue #5
        $personalCodeObj = new PersonalIdCode('38605230034');
        $this->assertTrue($personalCodeObj->validate());
    }
}
