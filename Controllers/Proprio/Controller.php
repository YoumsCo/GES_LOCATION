<?php

namespace Controllers\Proprio;

class Controller
{

    function get_images(string $rep, string $email)
    {
        if (!is_dir($rep)) {
            mkdir($rep, 0744, true);
        } else {
            $dir = scandir($rep);
            $tab = [];

            foreach ($dir as $file) {
                if ($file !== "." && $file !== "..") {
                    [$file_email, $file_time, $file_other] = explode("_", $file);
                    [$file_id, $file_ext] = explode(".", $file_other);

                    if ($file_email == $email) {
                        array_push($tab, $file);
                    }
                }
            }

            return $tab;
        }
    }

    function images(string $rep)
    {
        if (!is_dir($rep)) {
            mkdir($rep, 0744, true);
        } else {
            $dir = scandir($rep);
            $tab = [];

            foreach ($dir as $file) {
                if($file !== "." && $file !== "..") {
                    array_push($tab, $file);
                }
            }

            return $tab;
        }
    }

}