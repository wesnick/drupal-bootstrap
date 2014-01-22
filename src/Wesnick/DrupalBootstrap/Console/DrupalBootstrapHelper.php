<?php

namespace Wesnick\DrupalBootstrap\Console;
use Symfony\Component\Console\Helper\Helper;
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
    public function boot($path, $uri = 'default')
    {
        $drupal = new Drupal7($path, $uri);
        $drupal->doBootstrap();
    }


} 
