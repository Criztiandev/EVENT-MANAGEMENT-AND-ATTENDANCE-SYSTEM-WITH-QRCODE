<?php
function dd($value)
{
    if ($_ENV["DEV_ENV"] !== "development")
        return;

    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}