<?php

namespace Wesnick\DrupalBootstrap\Console;
use Symfony\Component\Console\Helper\Helper;
use Wesnick\DrupalBootstrap\Drupal6;
use Wesnick\DrupalBootstrap\Drupal7;


/**
 * Class DrupalBootstrapHelper
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class DrupalBootstrapHelper extends Helper
{
    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     *
     * @api
     */
    public function getName()
    {
        return 'drupal-bootstrap';
    }

    /**
     * Boot Drupal
     */
    public function boot($path, $version = 7, $uri = 'default')
    {
        if ($version == 7) {
            $drupal = new Drupal7($path, $uri);
        } else {
            $drupal = new Drupal6($path, $uri);
        }

        $drupal->doBootstrap();
    }

    /**
     * Boot Drupal
     */
    public function boot7($path, $uri = 'default')
    {
        $drupal = new Drupal7($path, $uri);
        $drupal->doBootstrap();
    }

    /**
     * Boot Drupal
     */
    public function boot6($path, $uri = 'default')
    {
        $drupal = new Drupal6($path, $uri);
        $drupal->doBootstrap();
    }

} 
