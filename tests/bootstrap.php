<?php
/**
 * @file
 * bootstrap.php
 */

// Composer
if (!file_exists(__DIR__.'/../vendor/autoload.php')) {
    throw new \RuntimeException('Could not find vendor/autoload.php, make sure you have run composer.');
}

require_once __DIR__.'/../vendor/autoload.php';
