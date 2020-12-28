<?php
// ============================================================================
// Advent of Code - Day 5 - Binary Boarding - Part 2
// ============================================================================
// read puzzle input
$puzzleInput = file_get_contents("puzzle_input.txt");

// split puzzle input into passport ids
$boardingPasses = explode("\n", $puzzleInput);

echo "Total boarding passes = ".count($boardingPasses)."\n";

// Loop through the boarding passes and build an array containing the numbers
// of all the occupied seats. Also keep a note of the lowest and greatest
// seat number
$lowestSeatNumber = 1000000;
$highestSeatNumber = 0;
$occupiedSeats = [];
foreach ($boardingPasses as $boardingPass) {
    $seatId = calculateSeatId($boardingPass);
    $occupiedSeats[] = $seatId;
    if ($seatId < $lowestSeatNumber) {
        $lowestSeatNumber = $seatId;
    }
    if ($seatId > $highestSeatNumber) {
        $highestSeatNumber = $seatId;
    }
}

echo "\nLowest Seat Number = ".$lowestSeatNumber."\n";
echo "Highest Seat Number = ".$highestSeatNumber."\n";
echo "Total occupied seats = ".count($occupiedSeats);

// loop through all the seat numbers and find out which one is not in the list
for ($i = $lowestSeatNumber; $i <= $highestSeatNumber; ++$i) {
    if (!in_array($i, $occupiedSeats)) {
        echo "\n\nEmpty seat number is ".$i;
        break;
    }
}
// ============================================================================
// For a boarding pass in the format "BBFBFFFRRR", calculate and return the
// seat ID.
// ============================================================================
function calculateSeatId($boardingPass) {

    // initialise minRow and maxRow
    $minRow = 0;
    $maxRow = 127;

    // loop through the first seven characters in $boardingPass to determine
    // what row the seat is in
    for ($i = 0; $i < 7; ++$i) {
        $currentCharacter = substr($boardingPass, $i, 1);
        // calculate new range
        if ($currentCharacter == 'F') { // lower half of group
            $newMinRow = $minRow;
            $newMaxRow = ($minRow + $maxRow - 1) / 2;
        } else {    // upper half of group
            $newMinRow = (($minRow + $maxRow - 1) / 2) + 1;
            $newMaxRow = $maxRow;
        }
        // set new range
        $minRow = $newMinRow;
        $maxRow = $newMaxRow;
    }

    $row = $minRow;

    // set up minCol and maxCol
    $minCol = 0;
    $maxCol = 7;

    // loop through the last three characters in $boardingPass and determine
    // what column the seat is in
    for ($i = 7; $i < 10; ++$i) {
        $currentCharacter = substr($boardingPass, $i, 1);
        // calculate new range
        if ($currentCharacter == 'L') { // lower half of group
            $newMinCol = $minCol;
            $newMaxCol = ($minCol + $maxCol - 1) / 2;
        } else {    // upper half of group
            $newMinCol = (($minCol + $maxCol - 1) / 2) + 1;
            $newMaxCol = $maxCol;
        }
        // set new range
        $minCol = $newMinCol;
        $maxCol = $newMaxCol;
    }

    $column = $minCol;

    // calculate seat ID
    $seatId = ($row * 8) + $column;

    return $seatId;
}
// ============================================================================
