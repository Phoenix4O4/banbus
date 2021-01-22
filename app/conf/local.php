<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_children', -1);
$settings['debug'] = true;
$settings['twig']['options']['debug'] = true;
$settings['error']['display_error_details'] = true;
$settings['db']['username'] = 'root';
$settings['db']['password'] = '123';
$settings['db']['database'] = 'tgdb';
