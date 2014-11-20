<?php

namespace Wesnick\DrupalBootstrap;
use Wesnick\DrupalBootstrap\Exception\BootstrapException;


/**
 * Drupal 7 core.
 */
class Drupal7 implements CoreInterface
{

    /**
     * @var string
     */
    private $drupalRoot;

    /**
     * @var string
     */
    private $uri;

    public function __construct($drupalRoot, $uri = 'default')
    {

        if ( ! file_exists($drupalRoot) || ! file_exists($drupalRoot . '/index.php')) {
            throw new \InvalidArgumentException(sprintf("Path %s does not appear to be a valid Drupal installation", $drupalRoot));
        }

        $this->drupalRoot = $drupalRoot;
        $this->uri = $uri;
    }

    /**
     * Implements CoreInterface::bootstrap().
     */
    public function doBootstrap()
    {
        // Validate, and prepare environment for Drupal bootstrap.
        if (!defined('DRUPAL_ROOT')) {
            define('DRUPAL_ROOT', $this->drupalRoot);
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            $this->validateDrupalSite();
        }

        // Bootstrap Drupal.
        $current_path = getcwd();
        chdir(DRUPAL_ROOT);
        drupal_bootstrap(DRUPAL_BOOTSTRAP_CONFIGURATION);
        if (empty($GLOBALS['databases'])) {
            throw new BootstrapException('Missing database setting, verify the database configuration in settings.php.');
        }
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        chdir($current_path);
    }

    /**
     * Implements CoreInterface::clearCache().
     */
    public function clearCache() {
        // Need to change into the Drupal root directory or the registry explodes.
        $current_path = getcwd();
        chdir(DRUPAL_ROOT);
        drupal_flush_all_caches();
        chdir($current_path);
    }


    /**
     * Implements CoreInterface::runCron().
     */
    public function runCron() {
        return drupal_cron_run();
    }


    /**
     * Impelements CoreInterface::validateDrupalSite().
     */
    public function validateDrupalSite()
    {
        if ('default' !== $this->uri) {
            // Fake the necessary HTTP headers that Drupal needs:
            $drupal_base_url = parse_url($this->uri);
            // If there's no url scheme set, add http:// and re-parse the url
            // so the host and path values are set accurately.
            if (!array_key_exists('scheme', $drupal_base_url)) {
                $drush_uri = 'http://' . $this->uri;
                $drupal_base_url = parse_url($this->uri);
            }
            // Fill in defaults.
            $drupal_base_url += array(
                'path' => NULL,
                'host' => NULL,
                'port' => NULL,
            );
            $_SERVER['HTTP_HOST'] = $drupal_base_url['host'];

            if ($drupal_base_url['port']) {
                $_SERVER['HTTP_HOST'] .= ':' . $drupal_base_url['port'];
            }
            $_SERVER['SERVER_PORT'] = $drupal_base_url['port'];

            if (array_key_exists('path', $drupal_base_url)) {
                $_SERVER['PHP_SELF'] = $drupal_base_url['path'] . '/index.php';
            }
            else {
                $_SERVER['PHP_SELF'] = '/index.php';
            }
        }
        else {
            $_SERVER['HTTP_HOST'] = 'default';
            $_SERVER['PHP_SELF'] = '/index.php';
        }

        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'];
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['REQUEST_METHOD']  = NULL;

        $_SERVER['SERVER_SOFTWARE'] = NULL;
        $_SERVER['HTTP_USER_AGENT'] = NULL;

        $conf_path = conf_path(TRUE, TRUE);
        $conf_file = $this->drupalRoot . "/$conf_path/settings.php";
        if (!file_exists($conf_file)) {
            throw new BootstrapException(sprintf('Could not find a Drupal settings.php file at "%s"', $conf_file));
        }
    }


    /**
     * @return array
     */
    public function getDatabaseSettings()
    {
        $conf_file = $this->drupalRoot . "/sites/default/settings.php";
        global $databases;
        include $conf_file;
        return $databases['default'];
    }


}
