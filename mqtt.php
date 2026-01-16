<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

file_put_contents('/tmp/php_alive.log', "PHP CLI HIDUP\n", FILE_APPEND);

$line = fgets(STDIN);

file_put_contents('/tmp/mqtt_debug.log', "DAPAT: ".$line."\n", FILE_APPEND);
