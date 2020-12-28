<?php
// ============================================================================
// Advent of Code - Day 3 - Part 2 - Toboggan Trajectory
// ============================================================================
// get the puzzle input
$map = file_get_contents("puzzle_input.txt");

// set up the map routes
$mapRoutes = [
    ["right" => 1, "down" => 1],
    ["right" => 3, "down" => 1],
    ["right" => 5, "down" => 1],
    ["right" => 7, "down" => 1],
    ["right" => 1, "down" => 2]
];

$treeTotalProduct = 1;

// Loop through the map routes and calculate how many trees the rider
// encounters for each route. Update the tree total product after
// calculating each route total.
foreach ($mapRoutes as $mapRoute) {
    $totalTreesInRoute = calculateTotalTreesInRoute($map, $mapRoute["right"],
                                                            $mapRoute["down"]);
    $treeTotalProduct *= $totalTreesInRoute;
    echo "\nTotal trees in route = ".$totalTreesInRoute;
}

echo "\nTree Total Product = ".$treeTotalProduct;
// ============================================================================
// For the map $map, using a route with x increment $xIncrement and y increment
// $yIncrement, calculate how many trees the rider encounters and return that
// total.
// ============================================================================
function calculateTotalTreesInRoute($map, $xIncrement, $yIncrement) {

    $lines = explode("\n", $map);

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
        $x += $xIncrement;
        $y += $yIncrement;
    }

    return $totalTrees;
}
// ============================================================================
