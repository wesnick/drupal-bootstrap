<?php
/**
 * @file test.php
 */ 


require_once 'vendor/autoload.php';

$drupal = new \Wesnick\DrupalBootstrap\Drupal7("/home/wes/www/surex");

$drupal->doBootstrap();

echo function_exists('node_load');
