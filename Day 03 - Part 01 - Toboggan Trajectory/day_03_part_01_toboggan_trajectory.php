<?php
// ============================================================================
// Advent of Code - Day 3 - Part 1 - Toboggan Trajectory
// ============================================================================
// get the puzzle input
$map = file_get_contents("puzzle_input.txt");

// write some test stuff to check that we've read the info correctly
echo "map size = ".strlen($map);
$lines = explode("\n", $map);
echo "\nTotal lines = ".count($lines);
echo "\nFirst line:\n".$lines[0]."\nLast line:\n".$lines[count($lines) - 1];

// get map width and height
$mapWidth = strlen($lines[0]);
echo "\nmap width = ".$mapWidth;
$mapHeight = count($lines);
echo "\nmap height = ".$mapHeight;

// set initial position
$x = 0;
$y = 0;

$totalTrees = 0;

// start loop
while ($y < $mapHeight) {
    // get character at current position on map
    $currentMapLine = $lines[$y];
    $currentCharacter = substr($currentMapLine, ($x % $mapWidth), 1);
    // if character represents a tree, increment total trees
    if ($currentCharacter == "#") {
        ++$totalTrees;
    }
    // print current position and the character at the current position

    // move the character to the next position (right 3, down 1)
    $x += 3;
    ++$y;
}

echo "\n\nTotal trees = ".$totalTrees;



// ============================================================================
// ============================================================================
// ============================================================================