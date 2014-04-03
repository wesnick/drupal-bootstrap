<?php

namespace Wesnick\DrupalBootstrap\Builder\Entity;
use Wesnick\DrupalBootstrap\Builder\Field\FieldBuilder;
use Wesnick\DrupalBootstrap\Builder\Field\InstanceBuilder;


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

    /**
     * @var InstanceBuilder[]
     */
    protected $instances;

    function __construct($bundle, $label, $description, $instances = array())
    {
        $this->bundle = $bundle;
        $this->label = $label;
        $this->description = $description;
        $this->instances = $instances;
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


    public function addInstance(InstanceBuilder $instances)
    {
        $this->instances[$instances->getFieldName()] = $instances;
    }

    /**
     * @return \Wesnick\DrupalBootstrap\Builder\Field\InstanceBuilder[]
     */
    public function getInstances()
    {
        return $this->instances;
    }





} 
