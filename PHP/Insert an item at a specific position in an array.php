<?php 

//Insert an item at a specific position in an array


    $array = array(
        'b'  => 'blue',
        'r'   => 'red',
        'g'   => 'green'
    );
 
    $pos = 1;
    $val = array('y' => 'yellow');
 
    $result = array_slice($array, 0, $pos) + $val + array_slice($array, $pos);
    print_r($result);
 
    /* Output:
 
    Array
    (
        [b] => blue
        [y] => yellow
        [r] => red
        [g] => green
    )
 