<?php

namespace Wesnick\DrupalBootstrap\Command;
use KzykHys\ClassGenerator\Builder\ClassBuilder;
use KzykHys\ClassGenerator\Builder\PropertyBuilder;
use KzykHys\ClassGenerator\Compiler\Compiler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wesnick\DrupalBootstrap\Definition\FieldBuilder;
use Wesnick\DrupalBootstrap\Definition\InstanceBuilder;
use Wesnick\DrupalBootstrap\Definition\WidgetBuilder;
use Wesnick\DrupalBootstrap\Writer\DrupalCodeCompiler;
use Wesnick\DrupalBootstrap\Writer\DrupalCodeWriter;


/**
 * Class SiteReaderCommand
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class SiteReaderCommand extends Command
{
    /**
     * @var DialogHelper
     */
    protected $dialog;

    /**
     * @var OutputInterface
     */
    protected $output;

    protected function configure()
    {
        return $this->setName('dr:read');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $info = entity_get_info();

        foreach ($info as $type => $def) {

            $subinfo = entity_get_all_property_info($type);
            $compiler = new Compiler();
            $class = new ClassBuilder();
            $class->setClass($def['base table']);
            $class->setDocblock(array($def['label']));
            foreach ($subinfo as $name => $sinfo) {
                $property = new PropertyBuilder();
                $property->setName($name);
                $var_type = isset($def['type']) ? $def['type'] : 'string';
                $property->setType($var_type);
                $property->setComments($def['description']);
                $property->setVisibility('protected');
                $property->addAccessor('get');
                $property->addAccessor('set');
                $class->addProperty($property);
            }

            $writer = $compiler->compile($class);
            $writer->save('/home/wes/www/drupal-bootstrap/bin/test/' . $class->getClass() . '.php');

        }

        return 0;
    }

}
