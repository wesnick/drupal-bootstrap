<?php

namespace Wesnick\DrupalBootstrap\Definition;


/**
 * Class NodeTypeBuilder
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class NodeTypeBuilder
{

    protected $type;
    protected $name;
    protected $base = 'node_content';
    protected $module = 'node';
    protected $description = "Node Type Description";
    protected $help = "";
    protected $has_title = true;
    protected $title_label = "Title";
    protected $custom = true;
    protected $modified = true;
    protected $locked = false;
    protected $disabled = false;
    protected $orig_type = '';
    protected $disabled_changed = false;

    function __construct($type, $name)
    {
        $this->type = $type;
        $this->name = $name;
    }


    public function build()
    {
        node_type_save(
            (object) get_object_vars($this)
        );
    }


}
