<?php

namespace Wesnick\DrupalBootstrap\Writer;


/**
 * Class DrupalTemplateWriter
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class DrupalTemplateWriter
{

    protected $name;

    protected $basePath;

    protected $replacements;

    function __construct($name, $basePath, $replacements)
    {
        $this->name = $name;
        $this->basePath = $basePath;
        $this->replacements = $replacements;
    }

    public function writeTemplate($file, $target)
    {
        $template = file_get_contents($this->basePath . '/examples/test_pane/' . $file);
        file_put_contents($this->basePath . '/widgets/' . $this->name . '/' . $target, strtr($template, $this->replacements));
    }


} 
