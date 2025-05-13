<?php

function get_profiles(string $rep, string $email, int|null $id): array
{
    if (!is_dir($rep)) {
        mkdir($rep, 0744, true);
    }

    $dir = scandir($rep);
    $file_tab = [];
    foreach ($dir as $file) {
        if ($file !== "." && $file !== "..") {
            [$file_email, $time, $other] = explode("_", $file);
            [$file_id, $other] = explode(".", $other);
            if ($file_email == $email && $file_id == $id) {
                array_push($file_tab, $file);
            }
        }
    }
    return $file_tab;
}

function get_all(string $rep): array
{
    if (!is_dir($rep)) {
        mkdir($rep, 0744, true);
    }

    $dir = scandir($rep);
    $file_tab = [];
    foreach ($dir as $file) {
        if ($file !== "." && $file !== "..") {
            array_push($file_tab, $file);
        }
    }
    return $file_tab;
}

