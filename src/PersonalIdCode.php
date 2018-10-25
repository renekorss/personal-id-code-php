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

class PersonalIdCode
{
    /**
     * Gender constants
     */
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    /**
     * Personal ID code
     *
     * @var string
     */
    private $code = null;

    /**
     * Constructor
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
}
