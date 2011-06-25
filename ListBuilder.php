<?php

/**
 * Iteratively build a list
 *
 * This function allows you to iterativley build a structured list of items
 * from an Array or any object implementing the Traversable interface
 *
 * @param array|Traversable The items to build the list from
 * @param string The html root tag to wrap all items in
 * @param string The HTML child tag to wrap single items in
 * @return string The rendered list
 * @see ListBuilderTest
 */
function BuildList($items, $tag = 'ul', $innerTag = 'li') {
    if(!is_array($items) && !($items instanceof Traversable)) {
        return '';
    }
    $output = sprintf('<%s>', $tag);

    foreach($items as $key => $item) {
        $data = is_array($item) ? $key . BuildList($item, $tag, $innerTag) : $key . ' = ' . $item;
        $output .= sprintf('<%s>%s</%s>', $innerTag, $data, $innerTag);
    }

    $output .= sprintf('</%s>', $tag);

    return $output;
}
