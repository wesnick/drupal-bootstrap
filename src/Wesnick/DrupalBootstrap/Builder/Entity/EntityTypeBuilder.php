<?php
/**
 * @file EntityTypeBuilder.php
 */
namespace Wesnick\DrupalBootstrap\Builder\Entity;


/**
 * Class NodeTypeBuilder
 *
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
interface EntityTypeBuilder
{
    public function getEntityType();
    public function getBundle();
    public static function getTypes();
    public function build();
}
