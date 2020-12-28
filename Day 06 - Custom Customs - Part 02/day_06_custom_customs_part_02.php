<?php
// ============================================================================
// Advent of Code - Day 6 - Custom Customs - Part 2
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
// Loop through the answer groups and calculate how many questions in each
// group have been answered yes to be all members of the same group.
// Keep a running total of all the totals.
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$grandTotalAllYesQuestions = 0;
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
    // calculate how many questions everyone in the current group
    // answered yes to
    $totalAllYesQuestions = calculateTotalAllYesQuestions(
                                                    $linesForCurrentGroup);
    $grandTotalAllYesQuestions += $totalAllYesQuestions;
}

// print the grand total
echo "\n\nGrand total of all yes questions = ".$grandTotalAllYesQuestions;
// ============================================================================
// From the group of lines for a single group, work out how many questions
// have been answered yes to by every member of the group.
// This is going to involve a process of whittling, where we set up an initial
// letter array, composed of the letters in the first line of
// $lineForCurrentGroup, then run through the remaining lines and remove
// letters than don't appear in succeeding lines.
// When we loop through all the remaining lines, we'll return the total number
// of letters that remain in the original letters array, after this whittling
// process is complete.
// ============================================================================
function calculateTotalAllYesQuestions($linesForCurrentGroup) {

    $firstLine = trim($linesForCurrentGroup[0]);
    
    // sanity test; if there's only a single line, return the length of
    // the trimmed first line
    if (count($linesForCurrentGroup) == 1) {
        return strlen($firstLine);
    }
    
    // if we've fallen through, there is more than one line, so...
    // trim the first line and build an array containing all of the letters
    // in the first line
    $remainingLetters = str_split($firstLine);

    // loop through the remaining lines and whittle the letters
    $totalLines = count($linesForCurrentGroup);
    for ($i = 1; $i < $totalLines; ++$i) {
        $currentLine = $linesForCurrentGroup[$i];
        $remainingLetters = whittleLetters($remainingLetters, $currentLine);
        // short circuit; no point in continuing if we've already reached zero
        if (count($remainingLetters) === 0) {
            return 0;
        }
    }
    
    // if we've fallen through, there was at least one question to which
    // every person in the group answered yes
    return count($remainingLetters);
}
// ============================================================================
// Build an array of letters that are contained in both the array
// $remainingLetters and the string $currentLine. Return this array.
// ============================================================================
function whittleLetters($remainingLetters, $currentLine) {
    
    $rebuiltRemainingLetters = [];
    
    // loop through the letters in $currentLine. If a letter in the string
    // $currentLine is also in the array $remainingLetters, push it onto
    // the array $rebuiltRemainingLetters
    $totalLettersInCurrentLine = strlen($currentLine);
    for ($i = 0; $i < $totalLettersInCurrentLine; ++$i) {
        $currentLetter = substr($currentLine, $i, 1);
        if (in_array($currentLetter, $remainingLetters)) {
            $rebuiltRemainingLetters[] = $currentLetter;
        }
    }
    
    return $rebuiltRemainingLetters;
}
// ============================================================================
