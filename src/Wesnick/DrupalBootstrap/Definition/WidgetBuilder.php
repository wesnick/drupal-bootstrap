<?php

namespace Wesnick\DrupalBootstrap\Definition;


/**
 * Class WidgetWrapper
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class WidgetBuilder
{

    public static function getTypes($type = null)
    {
        return field_info_widget_types($type);
    }

    public static function getTypesForFieldType($type)
    {
        $return = array();
        foreach (self::getTypes() as $typeName => $def) {
            if (in_array($type, $def['field types'])) {
                $return[$typeName] = $def['label'];
            }

        }
        return $return;
    }

    /**
     * @var string
     */
    protected $label;

    /**
     * @var array
     */
    protected $settings;

    protected $type;

    function __construct($type, $label, $settings = array())
    {
        $this->type = $type;
        $this->label = $label;
    }


    public function getDefinition()
    {
        return array(
            'type' => $this->type,
        );

    }



} 
