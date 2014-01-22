<?php

namespace Wesnick\DrupalBootstrap\Wrapper;
use EntityStructureWrapper;
use Wesnick\DrupalBootstrap\Definition\FieldBuilder;


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
     * @var FieldBuilder[]
     */
    private $fields;

    public function __construct($type, $data = NULL, $info = array())
    {
        parent::__construct($type, $data, $info);

    }

} 
