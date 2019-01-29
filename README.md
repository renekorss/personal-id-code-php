[![Build Status](https://travis-ci.org/renekorss/personal-id-code-php.svg?branch=master)](https://travis-ci.org/renekorss/personal-id-code-php)
[![Coverage Status](https://coveralls.io/repos/renekorss/personal-id-code-php/badge.svg?branch=master&service=github)](https://coveralls.io/github/renekorss/personal-id-code-php?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/634a0a6cf7c84e74aeedb2989bc299c5)](https://www.codacy.com/app/renekorss/personal-id-code-php?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=renekorss/personal-id-code-php&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/renekorss/personal-id-code-php/v/stable)](https://packagist.org/packages/renekorss/personal-id-code-php)
[![Total Downloads](https://poser.pugx.org/renekorss/personal-id-code-php/downloads)](https://packagist.org/packages/renekorss/personal-id-code-php)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

# Personal ID code
Estonian personal ID code validator and processor

## Install 

````bash
composer require renekorss/personal-id-code-php
````

## Usage 

````php
use RKD\PersonalIdCode\PersonalIdCode;

$id = new PersonalIdCode('39002102761');

// These results are examples as of 2018-11-26
echo $id->getGender(); // male

$datetime = $id->getBirthDate(); // Datetime object
echo $datetime->format('Y-m-d'); // 1990-02-10

echo $id->getAge(); // 28
echo $id->getBirthCentury(); // 1900

// Birth year in different formats
echo $id->getBirthYear(); // 1990
echo $id->getBirthYear('y'); // 90

// Birth month in different formats
echo $id->getBirthMonth(); // 02
echo $id->getBirthMonth('M'); // Feb
echo $id->getBirthMonth('F'); // February

// Birth day in different formats
echo $id->getBirthDay(); // 10
echo $id->getBirthDay('D'); // Sat
echo $id->getBirthDay('l'); // Saturday

// Presumable hospital where person was born
echo $id->getHospital(); // Maarjamõisa Kliinikum (Tartu), Jõgeva Haigla

// Check validity
if ($id->validate()) {
    echo 'Valid personal ID code';
} else {
    echo 'Invalid personal ID code';  
}
````

## Tasks

- `composer build` - build by running tests and all code checks
- `composer tests` - run tests
- `composer format` - format code against standards
- `composer docs` - build API documentation
- `composer phpmd` - run PHP Mess Detector
- `composer phpcs` - run PHP CodeSniffer

## License

Licensed under [MIT](LICENSE)
