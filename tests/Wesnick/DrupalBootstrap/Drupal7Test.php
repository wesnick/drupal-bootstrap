<?php
/**
 * @file Drupal6Test.php
 */

namespace Wesnick\DrupalBootstrap;


class Drupal7Test extends \PHPUnit_Framework_TestCase
{


    public function test__construct()
    {

        $drupal = new Drupal7(__DIR__ . "/../../fixtures/drupal7");
        $this->assertInstanceOf('Wesnick\DrupalBootstrap\Drupal7', $drupal);


    }
}
