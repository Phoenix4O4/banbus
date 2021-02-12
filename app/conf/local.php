<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('xdebug.var_display_max_depth', 4);
ini_set('xdebug.var_display_max_data', -1);
// ini_set('xdebug.var_display_max_children', -1);
$settings['app']['name'] = 'Atlanta Ned Dot Space';
$settings['debug'] = true;
$settings['twig']['options']['debug'] = true;
$settings['error']['display_error_details'] = true;
$settings['db']['username'] = 'root';
$settings['db']['password'] = '123';
$settings['db']['database'] = 'tgdb';

$settings['results_per_page'] = 4;

$settings['modules']['public_bans'] = false;
// $settings['modules']['personal_bans'] = false;
