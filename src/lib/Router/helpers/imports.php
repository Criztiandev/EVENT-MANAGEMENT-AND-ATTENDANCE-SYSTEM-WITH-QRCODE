<?php


function from($path)
{
    $fullPath = BASE_PATH . ENTRY_POINT . "/" . $path;
    if (!file_exists($fullPath)) {
        throw new Exception("File not found: $fullPath");
    }
    return $fullPath;
}

function display($path, $attributes = [])
{
    $fullPath = BASE_PATH . ENTRY_POINT . "/" . $path;
    if (!file_exists($fullPath)) {
        throw new Exception("File not found: $fullPath");
    }

    extract($attributes);
    require $fullPath;
}
