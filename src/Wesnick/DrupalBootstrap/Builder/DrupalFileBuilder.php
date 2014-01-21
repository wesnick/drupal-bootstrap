<?php

namespace Wesnick\DrupalBootstrap\Builder;
use Wesnick\DrupalBootstrap\Writer\DrupalCodeWriter;


/**
 * Class DrupalFileBuilder
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
abstract class DrupalFileBuilder
{


    protected $name;
    protected $extension;
    protected $title;
    protected $category;
    protected $description;

    /**
     * @var VarBuilder[]
     */
    protected $rootVariables;

    /**
     * @var MethodBuilder[]
     */
    protected $methods = array();

    function __construct($name, $title, $category, $description)
    {
        $this->name = $name;
        $this->title = $title;
        $this->category = $category;
        $this->description = $description;
        $this->extension = 'module';
        $this->rootVariables = array();
    }

    /**
     * @return VarBuilder[]
     */
    public function getRootVariables()
    {
        return $this->rootVariables;
    }

    /**
     * @param VarBuilder $rootVar
     */
    public function addRootVariable(VarBuilder $rootVar)
    {
        $this->rootVariables[$rootVar->getName()] = $rootVar;
    }

}
