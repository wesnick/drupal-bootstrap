<?php

namespace Wesnick\DrupalBootstrap;
use Wesnick\DrupalBootstrap\Exception\BootstrapException;

/**
 * Drupal core interface.
 */
interface CoreInterface {

    /**
     * @param string $drupalRoot
     *
     * @param string $uri
     *   URI that is accessing Drupal. Defaults to 'default'.
     */
    public function __construct($drupalRoot, $uri = 'default');

    /**
     * Bootstrap Drupal.
     */
    public function doBootstrap();

    /**
     * Clear caches.
     */
    public function clearCache();

    /**
     * Run cron.
     *
     * @return boolean
     *   True if cron runs, otherwise false.
     */
    public function runCron();

    /**
     * Validate, and prepare environment for Drupal bootstrap.
     *
     * @throws BootstrapException
     *
     * @see _drush_bootstrap_drupal_site_validate()
     */
    public function validateDrupalSite();

    /**
     * @return array
     */
    public function getPDO();

}
