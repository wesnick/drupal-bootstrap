<?php
/**
 * @file
 * bootstrap.php
 */

// Composer
if (!file_exists(__DIR__.'/../vendor/autoload.php')) {
    throw new \RuntimeException('Could not find vendor/autoload.php, make sure you ran composer.');
}

require_once __DIR__.'/../vendor/autoload.php';

$drupal = new \Wesnick\DrupalBootstrap\Drupal7(__DIR__ . '/../drupal/');
$drupal->doBootstrap();
