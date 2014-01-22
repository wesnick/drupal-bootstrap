<?php

namespace Wesnick\DrupalBootstrap\Definition;


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
