<?php

$grid = [];
$grid_to_solve = [];
$origin_grid = [];
$size;
$start = [];
$finish = [];
$times = 1;

$raw = file($argv[1], FILE_IGNORE_NEW_LINES);

$count_test = 0;

parse_grid();
grid_size();
build_grid();
$tree[0][0] = 0;
array_push($tree[0], $start);
$path = path($times);
print_grid($path);
//movement(1,2);

function path($times) {
    global $tree;
    global $grid_to_solve;
    $count = 0;

    foreach ($tree as $key_branch => $branch) {
        if ($count < $times) {
            if ($branch[0] != "-1") {
                $square = end($branch);
                $grid_to_solve[$square[0]][$square[1]] = "#";
                $movements = movement($square[0], $square[1]);
                if ($movements == false) {
                    $branch[0] = "-1";
                } elseif ($movements == "finish") {
                    return $branch;
                }else {
                    update_tree($movements, $key_branch, $branch);
                }
            }
            $count ++;
        }
    }
    $times = sizeof($tree);
    return(path($times));
}

function update_tree($movements, $key_branch, $branch) {
    global $tree;
    if (sizeof($movements) == 1) {
        array_push($tree[$key_branch], $movements[0]);
        $tree[$key_branch][0]++;

    } else {
        array_push($tree[$key_branch], $movements[0]);
        $tree[$key_branch][0]++;
        foreach ($movements as $key_add => $add) {
            if ($key_add > 0) {
                $new_branch = $branch;
                array_push($new_branch, $add);
                $new_branch[0]++;
                array_push($tree, $new_branch);
            }
        }
    }
}

function movement($square_row, $square_col) {
    global $grid_to_solve;
    $possible = [];
    if (check_finish($square_row, $square_col) == false){
        if ($grid_to_solve[$square_row-1][$square_col] == " ") {
            array_push($possible, array($square_row-1, $square_col));
            $grid_to_solve[$square_row-1][$square_col] = "0";
        }
        if ($grid_to_solve[$square_row][$square_col+1] == " ") {
            array_push($possible, array($square_row, $square_col+1));
            $grid_to_solve[$square_row][$square_col+1] = "0";
        }
        if ($grid_to_solve[$square_row+1][$square_col] == " ") {
            array_push($possible, array($square_row+1, $square_col));
            $grid_to_solve[$square_row+1][$square_col] = "0";
        }
        if ($grid_to_solve[$square_row][$square_col-1] == " ") {
            array_push($possible, array($square_row, $square_col-1));
            $grid_to_solve[$square_row][$square_col-1] = "0";
        }

        if ($possible == null) {
            return false;
        } else {
            return $possible;
        }
    } else {
        return "finish";
    }
}

function check_finish ($square_row, $square_col) {
    global $finish;
    if (array($square_row-1, $square_col) == $finish) {
        return true;
    } elseif (array($square_row+1, $square_col) == $finish) {
        return true;
    } elseif (array($square_row, $square_col-1) == $finish) {
        return true;
    }  elseif (array($square_row, $square_col+1) == $finish) {
        return true;
    } else {
        return false;
    }
}

function parse_grid () {
    global $grid;
    global $raw;
    $count = 0;
    foreach ($raw as $element) {
        $grid[$count] = str_split($element);
        $count++;
    }
}

function grid_size() {
    global $grid;
    global $size;
    $count = 0;
    $grid_length = sizeof($grid[0]);

    foreach ($grid as $element) {
        if ($element[0] == "#") {
            $count++;
        }
    }
    $grid_height = $count;
    $size = array ($grid_length, $grid_height);
}

function build_grid() {
    global $grid;
    global $size;
    global $grid_to_solve;
    global $origin_grid;
    global $start;
    global $finish;

    for ($count_row = 0; $count_row < $size[1]; $count_row++) {
        $count_column = 0;
        while ($count_column < $size[0]) {
            $grid_to_solve[$count_row][$count_column] = $grid[$count_row][$count_column];
            $origin_grid[$count_row][$count_column] = $grid[$count_row][$count_column];
            if ($grid[$count_row][$count_column] == "0") {
                $start = array($count_row, $count_column);
            } elseif ($grid[$count_row][$count_column] == "1") {
                $finish = array($count_row, $count_column);
            }
            $count_column++;
        }
    }
}

function print_grid($path) {
    global $origin_grid;
    global $size;
    for ($count_row = 0; $count_row < $size[1]; $count_row++) {
        $count_column = 0;
        while ($count_column < $size[0]) {
            if ( in_array(array($count_row, $count_column), $path) && $origin_grid[$count_row][$count_column] != "0" ) {
                $origin_grid[$count_row][$count_column] = ".";
            }
            echo $origin_grid[$count_row][$count_column];
            $count_column++;
        }
        echo "\n";
    }
}


//var_dump($size);
//var_dump($raw);
//var_dump($grid_to_solve);
//var_dump($start);
//var_dump($finish);