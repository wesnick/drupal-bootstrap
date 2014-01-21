<?php

namespace Wesnick\DrupalBootstrap\Wrapper;


/**
 * Class FieldWrapper
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class FieldWrapper
{

    protected $name;
    protected $label;
    protected $type;

    /**
     * @var bool
     */
    protected $isDrupalField;


    protected $widgetDefinition;

    function __construct($name, $type, $label, $isDrupalField = true)
    {
        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->isDrupalField = false;
    }


    public function createField()
    {
        if ( ! $info = field_info_field($this->name)) {
            field_create_field(array(
                'field_name' => $this->name,
                'type' => $this->type,
            ));
        }
    }



    /**
     * @param mixed $widgetDefinition
     */
    public function setWidgetDefinition($widgetDefinition)
    {
        $this->widgetDefinition = $widgetDefinition;
    }



} 
