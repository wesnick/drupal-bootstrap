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

        $type = self::guessType($this->type);

        if ( ! $info = field_info_field($this->name)) {
            field_create_field(array(
                'field_name' => $this->name,
                'type' => $type,
                'module' => '',
            ));
        }
    }

    public static function guessType($type)
    {
        $types = self::getFieldTypes();

        if (isset($types[$type])) {
            return $type;
        }
        else {
            switch ($type) {
                case 'filefield':
                    return 'file';
                break;
                case 'nodereference':
                case 'userreference':
                    return 'entityreference';
                    break;
                case 'link':
                    return 'link_field';
                    break;
                case 'content_taxonomy':
                    return 'taxonomy_term_reference';
                    break;
                case 'tdm_ingredient':
                    return 'text';
                    break;
                default:
                    return 'text';
            }
            $x = 'y';
        }

    }
}
