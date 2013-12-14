<?php
/**
 * @file Drupal6Test.php
 */

namespace Wesnick\DrupalBootstrap;


class Drupal6Test extends \PHPUnit_Framework_TestCase
{


    public function test__construct()
    {
        $drupal = new Drupal6(__DIR__ . "/../../fixtures/drupal6");
        $this->assertInstanceOf('Wesnick\DrupalBootstrap\Drupal6', $drupal);

    }



}
