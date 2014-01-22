<?php

namespace Wesnick\DrupalBootstrap\Builder\Feature;
use Wesnick\DrupalBootstrap\Builder\Primitive\DrupalFileBuilder;
use Wesnick\DrupalBootstrap\Builder\Primitive\MethodBuilder;


/**
 * Class WidgetBuilder
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class CtoolsContentTypeBuilder extends DrupalFileBuilder
{

    protected $replacements;

    /**
     * @var MethodBuilder[]
     */
    protected $methods = array();

    function __construct($name, $title, $category, $description)
    {
        parent::__construct($name, $title, $category, $description);

        $this->replacements = array(
            '{{name}}' => $this->getName(),
            '{{category}}' => $this->getCategory(),
            '{{title}}' => $this->getTitle(),
            '{{description}}' => $this->getDescription(),
            '{{css_name}}' => $this->getCssName(),
            '{{js_name}}' => $this->getJsName(),
        );

        $this->replacements['{{plugin_definition}}'] = $this->addPluginDefinition($this->replacements);
    }

    private function addPluginDefinition($replace)
    {
        $lines = <<<EOF
  // These settings control where the widget appears in the Panels "Add New Content" menu.
  'title' => '{{title}}',
  'description' => '{{description}}',
  'category' => array('{{category}}', 0),
  'icon' => '{{name}}.png',

  //  'required context' => array(
  //    new ctools_context_required(t('Node'), 'node'),
  //  ),

  'render callback' => 'tdm_widgets_{{name}}_render',
  'edit form' => 'tdm_widgets_{{name}}_edit_form',
  'admin info' => 'tdm_widgets_{{name}}_admin_info',

  // default data for configuration
  'defaults' => array(
    'setting_1' => 'Default Value',
  ),

  'tdm admin css' => '{{name}}_admin.css',
  'tdm admin js' => '{{name}}_admin.js',

  // 'single' means not to be sub-typed
  'single' => TRUE,
EOF;

        return strtr($lines, $replace);
    }

    public function addRenderMethod()
    {
        $method = new MethodBuilder();
        $method->setName('tdm_widgets_' . $this->name . '_render');
        $method->addArgument(array('subtype', 'string'));
        $this->methods[] = $method;
    }

    /**
     * @return MethodBuilder[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
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
    public function getTitle()
    {
        return $this->title;
    }


    public function getCssName()
    {
        return str_replace("_", "-", $this->getName());
    }

    public function getJsName()
    {
        $string = explode(" ", str_replace("_", " ", $this->getName()));

        $name = strtolower(array_shift($string));

        foreach ($string as $s) {
            $name .= ucfirst(strtolower($s));
        }

        return $name;
    }

    /**
     * @return array
     */
    public function getReplacements()
    {
        return $this->replacements;
    }

} 
