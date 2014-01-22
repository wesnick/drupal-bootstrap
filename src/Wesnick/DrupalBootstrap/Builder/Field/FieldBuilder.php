<?php

namespace Wesnick\DrupalBootstrap\Builder\Field;


/**
 * Class FieldWrapper
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class FieldBuilder
{

    protected $name;
    protected $type;


    function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public static function getFieldTypes($type = null)
    {
        return field_info_field_types($type);
    }

    public static function getFields($type = null)
    {
        return $type ? field_info_field($type) : field_info_fields();
    }

    public function build()
    {
        if ( ! $info = field_info_field($this->name)) {
            field_create_field(array(
                'field_name' => $this->name,
                'type' => $this->type,
                'module' => '',
            ));
        }
    }
}
