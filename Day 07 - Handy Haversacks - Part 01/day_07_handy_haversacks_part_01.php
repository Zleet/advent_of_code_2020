<?php
// ============================================================================
// Advent of Code - Day 7 - Handy Haversacks - Part 1
// ============================================================================
// read puzzle input
$puzzleInput = file_get_contents("puzzle_input.txt");

// split the puzzle input into lines
$lines = explode("\n", $puzzleInput);

echo "Total lines = ".count($lines);

// loop through lines. For each line, extract the strings that represent each
// bag (in the singular). Build a big array containing all the unique bag names
$uniqueBagNames = [];
foreach ($lines as $line) {
    $bagNamesFromSingleLine = parseBagNamesFromLine($line);
    // loop through $bagNamesFromSingleLine and, if a bag name isn't already
    // in $uniqueBagNames, push it on there
    foreach ($bagNamesFromSingleLine as $bagName) {
        if (!in_array($bagName, $uniqueBagNames)) {
            $uniqueBagNames[] = $bagName;
        }
    }
}

sort($uniqueBagNames);

echo "\n\nTotal unique bag names = ".count($uniqueBagNames)."\n\n";
print_r($uniqueBagNames);

// let's assign a unique key to each unique bag name by building an array
// with key/value pairs in the format <bag name>/<unique id>
// CODE HERE
// BOOKMARK (28/12/20 AT 2319)




// ============================================================================
// Parse all bag names (in the singular) from $line and return them in an
// array.
// ============================================================================
function parseBagNamesFromLine($line) {
    
    // array to hold bag names
    $bagNames = [];
    
    $containsLocation = strpos($line, " contain ");
    $firstBagName = substr($line, 0, $containsLocation);
    $firstBagName = str_replace("bags", "", $firstBagName);
    $firstBagName = trim($firstBagName);
    $bagNames[] = $firstBagName;

    // get the rest of the line, after the word contains
    $restOfLine = substr($line, $containsLocation + 8);

    // if the rest of the sentence contains the substring "no other bags",
    // it doesn't contains any other bags, so return $bagNames.
    if (strpos($restOfLine, "no other bags") !== false) {
        return $bagNames;
    }

    // If we've fallen through, the rest of the sentence contains bag names.
    // Parse them, then add them to the array $bagNames
    $elements = explode(",", $restOfLine);
    
    // loop through elements and parse each bag name
    foreach($elements as $element) {
        $element = trim($element);
        // find first space
        $firstSpaceLocation = strpos($element, " ");
        // remove everything before the first space
        $element = substr($element, $firstSpaceLocation + 1);
        // trim the element
        $element = trim($element);
        // remove the substrings "bags", "bag" and "."
        $element = str_replace("bags", "", $element);
        $element = str_replace("bag", "", $element);
        $element = str_replace(".", "", $element);
        // trim the element again
        $element = trim($element);
        // add the element to $bagNames
        $bagNames[] = $element;
    }
    
    return $bagNames;
}
// ============================================================================
// ============================================================================
// ============================================================================
// ============================================================================
// ============================================================================
// ============================================================================
// ============================================================================
// ============================================================================
