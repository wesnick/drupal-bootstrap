<?php

namespace Wesnick\DrupalBootstrap\Builder\Entity;


/**
 * Class NodeTypeBuilder
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class NodeTypeBuilder extends AbstractEntityTypeBuilder
{


    protected $base = 'node_content';
    protected $module = 'node';
    protected $help = "";
    protected $has_title = true;
    protected $title_label = "Title";
    protected $custom = true;
    protected $modified = true;
    protected $locked = false;
    protected $disabled = false;
    protected $orig_type = '';
    protected $disabled_changed = false;


    public function getEntityType()
    {
        return 'node';
    }

    public function build()
    {
        $vars = get_object_vars($this);
        $vars['type'] = $vars['bundle'];
        $vars['name'] = $vars['label'];
        unset($vars['bundle']);
        unset($vars['label']);

        node_type_save(
            (object) $vars
        );
    }

    public static function getTypes()
    {
        return node_type_get_types();
    }

    /**
     * @param boolean $has_title
     *
     * @return NodeTypeBuilder
     */
    public function setHasTitle($has_title)
    {
        $this->has_title = $has_title;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getHasTitle()
    {
        return $this->has_title;
    }

    /**
     * @param string $help
     *
     * @return NodeTypeBuilder
     */
    public function setHelp($help)
    {
        $this->help = $help;
        return $this;
    }

    /**
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }


}
