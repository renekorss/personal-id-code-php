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

use Datetime;
use InvalidArgumentException;

/**
 * Personal ID code class
 *
 * @author Rene Korss <rene.korss@gmail.com>
 */
class PersonalIdCode
{
    // Gender constants
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    // Default formats
    const FORMAT_YEAR_DEFAULT = 'Y';
    const FORMAT_MONTH_DEFAULT = 'm';
    const FORMAT_DAY_DEFAULT = 'd';

    // Regex to validate personal code
    const PERSONAL_CODE_REGEX = '/^[1-6][0-9]{2}[0-1][0-9][0-9]{2}[0-9]{4}$/';

    /**
     * Personal ID code
     *
     * @var string
     */
    private $code = null;

    /**
     * Birth date
     *
     * @var \Datetime
     */
    private $birthDate = null;

    /**
     * Constructor
     *
     * @param string $code Personal identification code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Get person gender
     *
     * @return string Person gender
     */
    public function getGender() : string
    {
        // Code's first number represents person gender
        // Odd number for male and even nubmer for female
        $genderNo = substr($this->code, 0, 1);
        $modulo = $genderNo % 2;

        return $modulo === 0 ? self::GENDER_FEMALE : self::GENDER_MALE;
    }

    /**
     * Get person birthday as Datetime object
     *
     * @return \Datetime Person birthday
     */
    public function getBirthDate() : Datetime
    {
        if (is_null($this->birthDate)) {
            $year = $this->getBirthCentury() + substr($this->code, 1, 2);
            $month = substr($this->code, 3, 2);
            $day = substr($this->code, 5, 2);

            $this->birthDate = new Datetime($year.'-'.$month.'-'.$day);
        }

        return $this->birthDate;
    }

    /**
     * Get person age
     *
     * @param \Datetime $date Optional \Datetime from which to calculate age
     *
     * @return int Person age
     */
    public function getAge(Datetime $date = null) : int
    {
        if (is_null($date)) {
            $date = new Datetime();
        }

        return $date->diff($this->getBirthDate())->y;
    }

    /**
     * Get person birth century
     *
     * @return int Birth century
     */
    public function getBirthCentury() : int
    {
        $firstNo = substr($this->code, 0, 1);
        return 1700 + ceil($firstNo / 2) * 100;
    }

    /**
     * Get birth year
     *
     * @param string $format Desired format. Defaults to 'Y', e.g 2018
     *
     * @return string Person birth year
     */
    public function getBirthYear($format = self::FORMAT_YEAR_DEFAULT) : string
    {
        $allowedFormats = ['L', 'o', 'Y', 'y'];
        if (!in_array($format, $allowedFormats)) {
            throw new InvalidArgumentException(
                'Not allowed year format. Allowed values are: '.implode($allowedFormats)
            );
        }

        return $this->getBirthDate()->format($format);
    }

    /**
     * Get birth month
     *
     * @param string $format Desired format. Defaults to 'm', e.g 05
     *
     * @return string Person birth month
     */
    public function getBirthMonth($format = self::FORMAT_MONTH_DEFAULT) : string
    {
        $allowedFormats = ['F', 'm', 'M', 'n', 't'];
        if (!in_array($format, $allowedFormats)) {
            throw new InvalidArgumentException(
                'Not allowed month format. Allowed values are: '.implode($allowedFormats)
            );
        }

        return $this->getBirthDate()->format($format);
    }

    /**
     * Get birth day
     *
     * @param string $format Desired format. Defaults to 'd', e.g 07
     *
     * @return string Person birth day
     */
    public function getBirthDay($format = self::FORMAT_DAY_DEFAULT) : string
    {
        $allowedFormats = ['d', 'D', 'j', 'l', 'N', 'S', 'w', 'z'];
        if (!in_array($format, $allowedFormats)) {
            throw new InvalidArgumentException(
                'Not allowed day format. Allowed values are: '.implode($allowedFormats)
            );
        }

        return $this->getBirthDate()->format($format);
    }

    /**
     * Get hospital where person presumably was born
     *
     * 001...010 = Kuressaare Haigla
     * 011...019 = Tartu Ülikooli Naistekliinik, Tartumaa, Tartu
     * 021...220 = Ida-Tallinna Keskhaigla, Pelgulinna sünnitusmaja, Hiiumaa, Keila, Rapla haigla, Loksa haigla
     * 221...270 = Ida-Viru Keskhaigla (Kohtla-Järve, endine Jõhvi)
     * 271...370 = Maarjamõisa Kliinikum (Tartu), Jõgeva Haigla
     * 371...420 = Narva Haigla
     * 421...470 = Pärnu Haigla
     * 471...490 = Pelgulinna Sünnitusmaja (Tallinn), Haapsalu haigla
     * 491...520 = Järvamaa Haigla (Paide)
     * 521...570 = Rakvere, Tapa haigla
     * 571...600 = Valga Haigla
     * 601...650 = Viljandi Haigla
     * 651...710? = Lõuna-Eesti Haigla (Võru), Põlva Haigla
     *
     * @source https://et.wikipedia.org/wiki/Isikukood#Haigla_tunnus
     *
     * @return string Hospital name
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getHospital()
    {
        // Only for persons born before 2013
        if ($this->getBirthYear() < 2013) {
            $personHospitalCode = (int)substr($this->code, -4, 3);

            $hospitals = [
                 11 => 'Kuressaare Haigla',
                 19 => 'Tartu Ülikooli Naistekliinik, Tartumaa, Tartu',
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
            ];

            foreach ($hospitals as $hospitalCode => $hospitalName) {
                if ($personHospitalCode < $hospitalCode) {
                    return $hospitalName;
                }
            }

            if ($personHospitalCode >= 950) {
                return 'Väljaspool Eestit';
            }
        }

        return 'Teadmata';
    }

    /**
     * Validate person code
     *
     * @return boolean True if valid personal code, false otherwise
     */
    public function validate() : bool
    {
        // Personal code is 11 digits long
        if (strlen($this->code) !== 11) {
            return false;
        }

        // Validate against regex
        if (!preg_match(self::PERSONAL_CODE_REGEX, $this->code)) {
            return false;
        }

        // Check control number
        $controlNumber = (int)substr($this->code, -1);
        if ($controlNumber !== $this->getControlNumber()) {
            return false;
        }

        return true;
    }

    /**
     * Get personal code control number
     */
    private function getControlNumber()
    {
        $total = 0;
        for ($i = 0; $i < 10; $i++) {
            $multiplier = $i + 1;
            $total += substr($this->code, $i, 1) * ($multiplier > 9 ? 1 : $multiplier);
        }

        $modulo = $total % 11;

        // Second round
        if ($modulo === 10) {
            for ($i = 0; $i < 10; $i++) {
                $multiplier = $i + 3;
                $total += substr($this->code, $i, 1) * ($multiplier > 9 ? $multiplier - 9 : $multiplier);
            }

            $modulo = $total % 11;
            if ($modulo === 10) {
                $modulo = 0;
            }
        }

        return $modulo;
    }
}
