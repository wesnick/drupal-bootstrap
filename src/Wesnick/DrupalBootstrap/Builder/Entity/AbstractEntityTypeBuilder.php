<?php

namespace Wesnick\DrupalBootstrap\Builder\Entity;


/**
 * Class AbstractEntityTypeBuilder
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
abstract class AbstractEntityTypeBuilder implements EntityTypeBuilder
{
    protected $bundle;
    protected $label;
    protected $description;

    function __construct($bundle, $label, $description)
    {
        $this->bundle = $bundle;
        $this->label = $label;
        $this->description = $description;
    }

    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }


} 
