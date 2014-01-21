<?php

namespace Wesnick\DrupalBootstrap\Wrapper;
use EntityStructureWrapper;


/**
 * Class EntityWrapper
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class EntityWrapper extends \EntityDrupalWrapper
{

    const TYPE_NODE         = 'node';
    const TYPE_USER         = 'user';
    const TYPE_VOCABULARY   = 'vocabulary';
    const TYPE_TERM         = 'taxonomy_term';
    const TYPE_FILE         = 'file';
    const TYPE_COLLECTION   = 'field_collection';

    /**
     * @var FieldWrapper[]
     */
    private $fields;

    public function __construct($type, $data = NULL, $info = array())
    {
        parent::__construct($type, $data, $info);

        $info = $this->propertyInfo['bundles'][$this->getBundle()]['properties'];

        $types = field_info_widget_types();

        foreach ($info as $name => $field) {
            $wrapper = new FieldWrapper($name, $field['type'], $field['label'], (! $field['field']));
            $instance = field_info_instance($type, $name, $this->getBundle());
            $wrapper->setWidgetDefinition($instance['widget']);
            $this->fields[$name] = $wrapper;
        }



        $x = 'y';

    }

} 
