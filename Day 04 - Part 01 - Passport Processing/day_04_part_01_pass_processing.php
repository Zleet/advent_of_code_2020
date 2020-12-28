<?php
// ============================================================================
// Advent of Code - Day 4 - Part 1 - Passport Processing
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

echo "\n\nTotal valid passports = ".$totalValidPassports;
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

    if (count($requiredFieldsRemaining) > 0) {
        return 0;   // invalid passport
    }

    // if we've fallen through, the passport is valid
    return 1;
}
// ============================================================================
