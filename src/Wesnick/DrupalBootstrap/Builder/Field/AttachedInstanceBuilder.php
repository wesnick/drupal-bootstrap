<?php

namespace Wesnick\DrupalBootstrap\Builder\Field;


/**
 * Class AttachedInstanceBuilder
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class AttachedInstanceBuilder extends InstanceBuilder
{

    protected $entityType;
    protected $bundle;

    /**
     * @param mixed $bundle
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * @return mixed
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param mixed $entityType
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
    }

    /**
     * @return mixed
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    public function build()
    {
        parent::build($this->entityType, $this->getBundle());
    }


}
