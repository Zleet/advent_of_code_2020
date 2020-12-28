<?php
// ============================================================================
// Advent of Code - Day 6 - Custom Customs - Part 1
// ============================================================================
// read puzzle input
$puzzleInput = file_get_contents("puzzle_input.txt");

// split the puzzle input into lines
$lines = explode("\n", $puzzleInput);

// loop through lines and build an array containing the indexes of every
// line that is empty or which contains only whitespace
$emptyLineIndices = [];
$totalLines = count($lines);
for ($i = 0; $i < $totalLines; ++$i) {
    $line = $lines[$i];
    $line = trim($line);
    if (strlen($line) === 0) {
        $emptyLineIndices[] = $i;
    }
}
echo "\nTotal empty lines = ".count($emptyLineIndices);

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Build an array containing pairs of indices. The pairs of indices will
// define the start and end of each group of answers for a single group.
// The group of answers for a single group will run from <first index value>
// to <second index value> and will be in the form of a two item array,
// that looks like this:
// [<first line index>, <last line index>]
// All of the pairs of line indices will be held in a container array named
// $groupLineIndices
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$groupLineIndices = [];

// build the two index array for the first group of lines
$firstEmptyLineIndex = $emptyLineIndices[0];
echo "\n\nFirst empty line index = ".$firstEmptyLineIndex;

// build the first pair of line indices for the first group, then append the
// two item array onto $groupLineIndices
$firstLineIndexPair = [0, $firstEmptyLineIndex - 1];
$groupLineIndices[] = $firstLineIndexPair;

// loop through the rest of the empty line indices and build line index pairs
// for the rest of the groups of lines (except for the last)
$totalEmptyLineIndices = count($emptyLineIndices);
for ($i = 1; $i < $totalEmptyLineIndices; ++$i) {
    $currentEmptyLineIndex = $emptyLineIndices[$i];
    $previousEmptyLineIndex = $emptyLineIndices[$i - 1];
    // build two item array containing the first and last line index for the
    // group of answer lines that run from the line immediately after
    // $previousEmptyLineIndex to the line immediately preceding
    // $currentEmptyLineIndex
    $startAndEndLine = [
                    $previousEmptyLineIndex + 1,
                    $currentEmptyLineIndex - 1
                        ];
    // push the two item array onto $groupLineIndices
    $groupLineIndices[] = $startAndEndLine;
}

// build the final two item array which contains the index of the first line
// and last line in the last answer group, then push it onto
// $groupLineIndices
$indexOfLastBlankLine = $emptyLineIndices[count($emptyLineIndices) - 1];
$indexOfLastLine = count($lines) - 1;
$lastAnswerGroupArray = [$indexOfLastBlankLine + 1, $indexOfLastLine];
$groupLineIndices[] = $lastAnswerGroupArray;

// test print
// echo "\n\ngroupLineIndices:\n\n";
// print_r($groupLineIndices);

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Loop through the answer groups and calculate how many unique answers have
// been answered by each group. Keep a running total of all the totals.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$grandTotalOfQuestionsAnswered = 0;
foreach ($groupLineIndices as $groupStartAndEndLineIndex) {
    // get start and end line indices
    $startLineIndex = $groupStartAndEndLineIndex[0];
    $endLineIndex = $groupStartAndEndLineIndex[1];
    // build an array containing all the lines for the current group
    $linesForCurrentGroup = [];
    for ($i = $startLineIndex; $i <= $endLineIndex; ++$i) {
        $currentLine = $lines[$i];
        $linesForCurrentGroup[] = $currentLine;
    }
    // calculate how many unique questions have been answered by the current
    // group
    $totalUniqueQuestionsAnswered = calculateTotalUniqueQuestionsAnswered(
                                                        $linesForCurrentGroup);
    $grandTotalOfQuestionsAnswered += $totalUniqueQuestionsAnswered;
}

// print the grand total
echo "\n\nGrand total of questions answered = ";
echo $grandTotalOfQuestionsAnswered;
// ============================================================================
// From the group of lines for a single group, work out how many unique
// questions have been answered by the group and return that total.
// ============================================================================
function calculateTotalUniqueQuestionsAnswered($linesForCurrentGroup) {
    
    // store all the letters for unique questions answered in this array,
    // including each letter only once
    $uniqueQuestionsAnswered = [];

    // loop through all the lines
    foreach ($linesForCurrentGroup as $line) {
        $line = trim($line);
        // loop through letters in line
        $totalLetters = strlen($line);
        for ($i = 0; $i < $totalLetters; ++$i) {
            $letter = substr($line, $i, 1);
            // if letter isn't on array, push it on there
            if (!in_array($letter, $uniqueQuestionsAnswered)) {
                $uniqueQuestionsAnswered[] = $letter;
            }
        }
    }
    
    return count($uniqueQuestionsAnswered);
}
// ============================================================================
