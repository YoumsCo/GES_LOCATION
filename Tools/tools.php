<?php
function parse($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);

    return $data;
}

function current_page($link) {
    $current_page = strtolower($_SERVER["PHP_SELF"]);
    if(strpos($current_page, strtolower($link))) {
        return "current_page";
    }
}