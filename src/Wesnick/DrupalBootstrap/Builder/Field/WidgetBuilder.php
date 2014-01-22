<?php

namespace Wesnick\DrupalBootstrap\Builder\Field;


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
     * @var array
     */
    protected $settings;

    protected $type;

    function __construct($type, $settings = array())
    {
        $this->type = $type;
        $this->settings = $settings;
    }


    public function getDefinition()
    {
        return array(
            'type' => $this->type,
        );

    }



} 
