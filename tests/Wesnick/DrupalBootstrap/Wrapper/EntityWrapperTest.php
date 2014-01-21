<?php
/**
 * @file EntityWrapperTest.php
 */

namespace Wesnick\DrupalBootstrap\Wrapper;


use Wesnick\DrupalBootstrap\Drupal7;

class EntityWrapperTest extends \PHPUnit_Framework_TestCase
{

    private static $drupalGlobal = false;

    public function setUp()
    {
        if ( ! self::$drupalGlobal) {
            $drupal = new Drupal7('/home/wes/');
            $drupal->doBootstrap();
            self::$drupalGlobal = true;
        }
    }

    public function testGetter()
    {

    }

}
