<?php
// ============================================================================
// Advent of Code - Day 4 - Part 2 - Passport Processing
// ============================================================================
// get the puzzle input
$bunchOfPassports = file_get_contents("puzzle_input.txt");

// create an array containing all the passports
$passports = explode("\n\n", $bunchOfPassports);

echo "Total passports = ".count($passports);

echo "\n\nFirst passport:\n".$passports[0];
echo "\n\nLast passport:\n".$passports[count($passports) - 1];

// loop through passports and determine how many valid passports we've got
$totalValidPassports = 0;
$requiredFields = ["byr", "iyr", "eyr", "hgt", "hcl", "ecl", "pid"];
foreach ($passports as $passport) {
    if (isAValidPassport($passport, $requiredFields)) {
        ++$totalValidPassports;
    }
}

echo "\n\nTotal valid passports = ".$totalValidPassports."\n\n";
// ============================================================================
// Work out if the passport $passport contains all the required fields in the
// array $requiredFields. If so, the passport is valid, so return 1.
// Otherwise return 0.
// ============================================================================
function isAValidPassport($passport, $requiredFields) {

    // remove external whitespace
    $passport = trim($passport);

    // split passport into lines
    $lines = explode("\n", $passport);

    // copy of required fields. We're going to remove fields from
    // requiredFieldsRemaining as we discover them in the passport. Then, if
    // there are no required fields remaining after parsing the entire
    // passport, the passport is valid, otherwise it isn't.
    $requiredFieldsRemaining = $requiredFields;

    // array to hold all the key/value pairs
    $passportInfo = [];

    // loop through lines and search for the required fields.
    foreach ($lines as $line) {
        $line = trim($line);
        $elements = explode(" ", $line);
        // loop through elements and parse key/element pairs
        foreach ($elements as $element) {
            $colonPosition = strpos($element, ':');
            $key = substr($element, 0, $colonPosition);
            // echo "\n\nkey = ".$key;
            $value = substr($element, $colonPosition + 1);
            // store the key/value pair in $passportInfo
            $passportInfo[$key] = $value;
            // echo "\nvalue = ".$value;
            // remove the key from $requiredFieldsRemaining, is it's still
            // in the array
            if (in_array($key, $requiredFieldsRemaining)) {
                $singleKeyArray = [$key];
                $requiredFieldsRemaining = array_diff(
                                    $requiredFieldsRemaining, $singleKeyArray);
            }
        }
    }

    // test print $passportInfo
    // echo "\n\nPassport Info:\n";
    // print_r($passportInfo);
    // exit(0);

    // check passport contains the requisite number of fields
    if (count($requiredFieldsRemaining) > 0) {
        return 0;   // invalid passport
    }

    // ++++++++++++++++++++++++++++++++++++++
    // validate the three pieces of year info
    // ++++++++++++++++++++++++++++++++++++++
    if (!isvalidYearValue($passportInfo["byr"], 1920, 2002)) {
        return 0;   // invalid passport
    }
    if (!isvalidYearValue($passportInfo["iyr"], 2010, 2020)) {
        return 0;   // invalid passport
    }
    if (!isvalidYearValue($passportInfo["eyr"], 2020, 2030)) {
        return 0;   // invalid passport
    }

    // validate height
    if (!isValidHeight($passportInfo["hgt"])) {
        return 0;   // invalid passport
    }

    // validate hair colour
    if (!isValidHairColour($passportInfo["hcl"])) {
        return 0;   // invalid passport
    }

    // validate eye colour
    $validEyeColours = ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth'];
    $hairColour = $passportInfo["ecl"];
    if (!in_array($hairColour, $validEyeColours)) {
        return 0;   // invalid passport
    }

    // validate passport id
    $passportId = $passportInfo["pid"];
    if (!isValidPassportId($passportId)) {
        return 0;
    }

    // if we've fallen through, the passport has passed all the validation
    // checks
    return 1;
}
// ============================================================================
// For the year string $yearString (in the format "1987", "2011" etc.):
// 1. ensure that it contains 4 digits
// 2. ensure that it lies between $minYear and $maxYear (both inclusive)
// If both the above conditions are true, the year is valid, so return 1,
// otherwise return 0.
// ============================================================================
function isValidYearValue($yearString, $minYear, $maxYear) {

    $digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    // remove external whitespace
    $yearString = trim($yearString);

    // check that $yearString contains 4 characters
    if (strlen($yearString) != 4) {
        return 0;
    }

    // check that all the characters in $yearString are digits
    for ($i = 0; $i < strlen($yearString); ++$i) {
        $currentCharacter = substr($yearString, $i, 1);
        if (strpos($yearString, $currentCharacter) === false) {
            return 0;   // current character is not a digit
        }
    }

    // check that $yearString, when converted to an int, lies between
    // $minYear and $maxYear (both inclusive)
    $year = (int)$yearString;
    if (($year < $minYear) || ($year > $maxYear)) {
        return 0;
    }

    // if we've fallen through, the year has passed all the validation checks
    // and is valid
    return 1;
}
// ============================================================================
// Check that a height string is valid. To be valid, it must:
// 1. end in either 'cm' or 'in'
// then:
// 2. if it ends in 'cm', the value must lie between 150 and 193 inclusive
// or...
// 2. if it ends in 'in', the value must lie between 59 and 76 inclusive
// ============================================================================
function isValidHeight($heightString) {

    $heightString = trim($heightString);

    echo "\n=============================";
    echo "\nheightString = ".$heightString;

    // check for valid suffix
    $suffix = substr($heightString, strlen($heightString) - 2);
    // echo "height string suffix = ".$suffix;
    if (($suffix != 'cm') && ($suffix != 'in')) {
        echo " (Invalid - wrong suffix)";
        return 0;   // invalid height string
    }

    // If we've fallen through, the passport contains a valid suffix.
    // Remove the suffix and extract the value.
    $valueString = substr($heightString, 0, strlen($heightString) - 2);
    $valueString = trim($valueString);
    // echo "\n\nvalueString = ".$valueString;
    $height = (int) $valueString;

    echo "\n\nAfter casting to int, height = ".$height;

    // check that $height lies within the acceptable range
    if ($suffix == 'cm') {
        if (($height < 150) || ($height > 193)) {
            echo " (Invalid - outside of range)";
            return 0; // invalid height
        }
    }
    if ($suffix == 'in') {
        if (($height < 59) || ($height > 76)) {
            echo " (Invalid - outside of range)";
            return 0; // invalid height
        }
    }

    // if we've fallen through, height is valid
    echo " (Valid)";
    return 1;
}
// ============================================================================
// Work out is $hairColour is valid. It must consist of a hash character
// followed by six hexadecimal characters. If it's valid, return 1, otherwise
// return 0.
// ============================================================================
function isValidHairColour($hairColour) {

    $hairColour = trim($hairColour);

    // test length
    if (strlen($hairColour) != 7) {
        return 0;
    }

    // check that string begins with a hash character
    if (substr($hairColour, 0, 1) != '#') {
        return 0;
    }

    // check that characters 2 to 7 are hexadecimal
    $hexCharacters = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                        'a', 'b', 'c', 'd', 'e', 'f'];
    for ($i = 1; $i < 7; ++$i) {
        $currentCharacter = substr($hairColour, $i, 1);
        if (!in_array($currentCharacter, $hexCharacters)) {
            return 0;
        }
    }

    // if we've fallen through, the hair colour string is valid
    return 1;
}
// ============================================================================
// Check if a passport ID is valid. To be valid, it must contain 9 digits.
// If the passport ID is valid, return 1, otherwise return 0.
// ============================================================================
function isValidPassportId($passportId) {

    $passportId = trim($passportId);

    // check length
    if (strlen($passportId) != 9) {
        return 0;
    }

    // check that $passportId only contains digits
    $digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    for ($i = 0; $i < 9; ++$i) {
        $currentCharacter = substr($passportId, $i, 1);
        if (!in_array($currentCharacter, $digits)) {
            return 0;
        }
    }

    // if we've fallen through, the passport is valid
    return 1;
}
// ============================================================================
