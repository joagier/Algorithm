<?php

$raw =  file($argv[1]);

$grid = [];
$count_row = 0;
$count_row2 = 0;
$count_column;
$count_column2 = 0;
$grid_to_solve = [];
$value_row = 1;
$origin_grid = [];
$count_test = 0;


//function to retrieve data from the starter grid
foreach($raw as $element){
    $grid[$count_row] = str_split($element);
    $count_row++;
}

//function to order the starter grid to a 2D array
for ($count_row = 1; $count_row < 10; $count_row++) {
    $count_column = 2;
    $count_column2 = 0;
    while ($count_column < 19) {
        $grid_to_solve[$count_row2][$count_column2] = intval($grid[$count_row][$count_column]);
        $origin_grid[$count_row2][$count_column2] = intval($grid[$count_row][$count_column]);
        $count_column = $count_column +2;
        $count_column2++;
    }
    $count_row2++;
}

solve_grid();
solution();

function solution() {
    global $grid_to_solve;
    echo "|";
    for ($i = 0; $i <18; $i++) {
        echo"-";
    }
    echo "|\n";

    foreach ($grid_to_solve as $key => $index) {
        echo "|";
        foreach ($grid_to_solve[$key] as $element) {
            echo " ";
            echo $element;
        }
        echo "|\n";
    }
    echo "|";
    for ($i = 0; $i <18; $i++) {
        echo"-";
    }
    echo "|\n";
}


function solve_grid() {
    $count_row = 0;
    $count_col = 0;
    global $grid_to_solve;
    while ($count_row < 9) {
        while ($count_col < 9) {
            if (check_origin_grid($count_row, $count_col)) {
                $count_col++;
            } else {
                $value = put_number($grid_to_solve[$count_row][$count_col], $count_row, $count_col);
                if ($value != false) {
                    $grid_to_solve[$count_row][$count_col] = $value;
                    $count_col++;
                } else {
                    $grid_to_solve[$count_row][$count_col] = 0;
                    $index = backward($count_row, $count_col);
                    $count_row = $index[0];
                    $count_col = $index[1];
                    $count_col++;
                }
            }
        }
        $count_row++;
        $count_col = 0;
    }
}



function backward ($row, $col) {
    global $grid_to_solve;
    if ($col == 0) {
        $row--;
        $col = 8;
    } else {
        $col--;
    }
    if (check_origin_grid($row, $col)) {
        return backward($row, $col);
    } else {
        $value = put_number($grid_to_solve[$row][$col], $row, $col);
        if ($value != false) {
            $grid_to_solve[$row][$col] = $value;
            return $array = array($row, $col);
        } else {
            $grid_to_solve[$row][$col] = 0;
            return backward($row, $col);
        }

    }

}
//var_dump(backward(0, 3, $origin_grid));


function check_origin_grid($position_row, $position_column) {
    global $origin_grid;
    if ($origin_grid[$position_row][$position_column] != 0) {
        return true;
    } else {
        return false;
    }
}

function put_number($value, $position_row, $position_column) {
    if ($value == 0) {
        $value++;
    }
    while ($value < 10) {
        if (check_row($value, $position_row) && check_column($value, $position_column)
        && check_square($value, $position_row, $position_column)) {
            return $value;
        } else {
            $value++;
        }
    }
    return false;
}

function check_row($value, $position_row) {
    global $grid_to_solve;
    $count = 0;
    foreach ($grid_to_solve[$position_row] as $key => $element) {
        if ($value == $grid_to_solve[$position_row][$count]) {
            return false;
        }
        $count++;
    }
    return true;
}

function check_column($value, $position_col) {
    global $grid_to_solve;
    for ($count = 0; $count<9; $count++) {
        if ($value == $grid_to_solve[$count][$position_col]) {
            return false;
        }
    }
    return true;
}

function check_square ($value, $position_row, $position_column) {
    global $grid_to_solve;
    if ($position_column < 3) {
        $col_min = 0;
        $col_max = 3;
    } elseif ($position_column > 2 && $position_column < 6) {
        $col_min = 3;
        $col_max = 6;
    } elseif ($position_column > 5 && $position_column < 9) {
        $col_min = 6;
        $col_max = 9;
    }

    if ($position_row < 3) {
        $row_min = 0;
        $row_max = 3;
    } elseif ($position_row > 2 && $position_row < 6) {
        $row_min = 3;
        $row_max = 6;
    } elseif ($position_row > 5 && $position_row < 9) {
        $row_min = 6;
        $row_max = 9;
    }

    for ($i = $row_min; $i < $row_max; $i++) {
        for ($j = $col_min; $j < $col_max; $j++) {
            if ($value == $grid_to_solve[$i][$j]) {
                return false;
            }
        }
    }
    return true;
}

//var_dump(check_row(1, 0, $grid_to_solve));
//var_dump(check_column(4, 6, $grid_to_solve));
//var_dump(check_square(1, 0, 1, $grid_to_solve));

//var_dump($origin_grid);