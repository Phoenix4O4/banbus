<?php

$settings = require __DIR__ . '/defaults.php';

//Check for a development environment config file, or fallback to the production
//file, if it exists.
if (isset($_ENV['APP_ENV'])) {
    if (file_exists(__DIR__ . '/conf/' . strtolower($_ENV['APP_ENV']) . '.php')) {
        require_once __DIR__ . '/conf/' . strtolower($_ENV['APP_ENV']) . '.php';
    }
} else {
    if (file_exists(__DIR__ . '/conf/prod.php')) {
        require_once __DIR__ . '/conf/prod.php';
    }
}

return $settings;
