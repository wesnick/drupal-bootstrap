<?php

namespace Wesnick\DrupalBootstrap\Definition;


/**
 * Class FieldWrapper
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class InstanceBuilder
{

    protected $field_name;
    protected $label;
    protected $description = '';
    protected $required = false;

    /**
     * @var WidgetBuilder
     */
    protected $widget;
    protected $display;

    function __construct($field_name, $label, $widgetDefinition)
    {
        $this->field_name = $field_name;
        $this->label = $label;
        $this->widget = $widgetDefinition;
    }

    /**
     * @param boolean $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @param \Wesnick\DrupalBootstrap\Definition\WidgetBuilder $widget
     */
    public function setWidget($widget)
    {
        $this->widget = $widget;
    }

    /**
     * @param mixed $field_name
     */
    public function setFieldName($field_name)
    {
        $this->field_name = $field_name;
    }

    /**
     * @param mixed $display
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }


    public static function getTypes($type = null)
    {
        return field_info_instances($type);
    }


    public function build($entityType, $bundleName)
    {
        if ( ! $info = field_info_instance($entityType, $this->field_name, $bundleName)) {
            field_create_instance(array(
                'entity_type' => $entityType,
                'bundle' => $bundleName,
                'field_name' => $this->field_name,
                'label' => $this->label,
                'description' => $this->description,
                'required' => $this->required,
                'settings' => array(),
                'widget' => $this->widget->getDefinition(),
                'display' => array(),
            ));
        }
    }
}
