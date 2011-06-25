<?php

/**
 * Recursivley count all the keys in an array
 *
 * @param array The array to count the keys of
 * @return integer The total sum of all array keys
 */
function array_count_keys($data) {
    if(!is_array($data)) {
        return 0;
    }

    $count = 0;

    foreach($data as $key => $items) {
        if(is_array($items)) {
            $count += array_count_keys($items);
        }

        $count += $key;
    }

    return $count;
}