<?php

namespace Wesnick\DrupalBootstrap\Wrapper;


/**
 * Class WidgetWrapper
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class WidgetWrapper
{

    /**
     * @var string
     */
    protected $label;

    /**
     * @var FieldWrapper[]
     */
    protected $fieldTypes;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var array
     */
    protected $behaviors;

    function __construct($label, $settings, $fieldTypes, $behaviors = array())
    {
        $this->behaviors = $behaviors;
        $this->fieldTypes = $fieldTypes;
        $this->label = $label;
        $this->settings = $settings;
    }

    public function toArray()
    {
        return array_filter(array(
            'label' => $this->label,
            'field types' => $this->fieldTypes,
            'behaviors' => $this->behaviors,
            'settings' => $this->settings,
        ));
    }


    public function createInstance($entityType, $bundle, $field)
    {

        if ( ! $info = field_info_instance($entityType, $field, $bundle)) {
            field_create_instance(array(
                'field_name' => $field,
                'entity_type' => $entityType,
                'bundle' => $bundle,
                'label' => $this->label,
                'widget' => array('type' => $this->toArray())
            ));
        }
    }
} 
