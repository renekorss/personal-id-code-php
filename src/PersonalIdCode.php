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
}
