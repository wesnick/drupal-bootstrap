<?php

namespace Wesnick\DrupalBootstrap\Builder\Field;


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

    function __construct($field_name, $label, WidgetBuilder $widgetDefinition)
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
     * @param WidgetBuilder $widget
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @return \Wesnick\DrupalBootstrap\Definition\WidgetBuilder
     */
    public function getWidget()
    {
        return $this->widget;
    }


}
