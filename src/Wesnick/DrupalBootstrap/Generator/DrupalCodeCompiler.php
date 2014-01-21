<?php

namespace Wesnick\DrupalBootstrap\Writer;
use Wesnick\DrupalBootstrap\Builder\DrupalFileBuilder;
use Wesnick\DrupalBootstrap\Builder\MethodBuilder;

/**
 * Class DrupalCodeCompiler
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class DrupalCodeCompiler 
{
    private $options;

    /**
     * Constructor
     *
     * @param array $options Optionsements
     */
    public function __construct(array $options = array())
    {
        $this->options = array_merge(array(
            'indent_spaces' => '2',
            'lineFeed' => "\n"
        ), $options);
    }

    /**
     * Compiles a builder
     *
     * @param DrupalFileBuilder $builder
     *
     * @return DrupalCodeWriter
     */
    public function compile(DrupalFileBuilder $builder)
    {
        $writer = new DrupalCodeWriter($this->options);
        $writer->writeLine('<?php');
        $writer->writeLine();


        $this->compileRootVariables($builder, $writer);
        $this->compileMethodDefinitions($builder, $writer);

        return $writer;
    }


    protected function compileRootVariables(DrupalFileBuilder $builder, DrupalCodeWriter $writer)
    {
        $rootVars = $builder->getRootVariables();

        foreach ($rootVars as $var) {
//            $var->writeVariable($writer);
        }


    }

    /**
     * Compiles method definition
     *
     * @param DrupalFileBuilder $builder
     * @param DrupalCodeWriter $writer
     */
    protected function compileMethodDefinitions(DrupalFileBuilder $builder, DrupalCodeWriter $writer)
    {

        foreach ($builder->getMethods() as $method) {
            /* @var MethodBuilder $method */

            $writer->writeLine('/**');

            $comments = $method->getComments();
            if (count($comments)) {
                foreach ($comments as $comment) {
                    $comment = trim(ltrim($comment, '>'));
                    $writer->write(' * ')->writeLine($comment);
                }
                $writer->write(' * ')->writeLine();
            }

            $arguments = $method->getArguments();
            $argumentTypeMaxLen = 0;
            if (count($arguments)) {
                foreach ($arguments as $argument) {
                    $argumentTypeMaxLen = max($argumentTypeMaxLen, strlen($argument[1]));
                }
            }

            foreach ($arguments as $argument) {
                $writer
                    ->write(' * @param ' . sprintf('%-' . $argumentTypeMaxLen . 's', $argument[1]))
                    ->writeLine(' $' . $argument[0]);
            }

            if ($method->getType()) {
                $writer->writeLine(' *');
                $writer->writeLine(' * @return ' . $method->getType());
            }

            $writer->writeLine(' */');

            $writer
                ->write('function ')
                ->write($method->getName())
                ->write('(');

            $args = array();
            foreach ($arguments as $argument) {
                $item = '';
                $item .= '$'.$argument[0];
                $args[] = $item;
            }

            if (count($args)) {
                $writer->write(implode(', ', $args));
            }

            $writer->writeLine(') {');
            $writer->writeLine();

            foreach ($method->getCodeLines() as $line) {
                $writer->indent()->writeLine($line);
            }

            $writer
                ->writeLine()
                ->writeLine('}')
                ->writeLine()
            ;
        }
    }

    /**
     * Split long class name to namespace and class name
     *
     * @param string $className
     *
     * @throws \Exception
     *
     * @return array An array includes keys below [namespace, classname, fqcn]
     */
    public function parseClassName($className)
    {
        if (!preg_match('/^\\\\?(.*?)\\\\?([a-zA-Z0-9_]+)$/', $className, $matches)) {
            throw new \Exception('Invalid class name "' . $className . '" given');
        }

        return array(
            'namespace' => $matches[1],
            'classname' => $matches[2],
            'fqcn'      => $className
        );
    }

    /**
     * Normalize PHP type
     *
     * @param string $type PHP type
     *
     * @return array An array includes key below [name, hint]
     */
    public function getType($type)
    {
        $aliases = array(
            'int'    => 'integer',
            'bool'   => 'boolean',
            'double' => 'float',
            'object' => '\\stdClass'
        );
        $default = array(
            'integer', 'boolean', 'float', '\\stdClass', 'callable', 'string', 'resource', 'void', 'mixed'
        );
        $typeHintEnabled = false;

        if (isset($aliases[$type])) {
            $type = $aliases[$type];
        }

        if (!in_array($type, $default)) {
            $typeHintEnabled = true;
        }

        return array(
            'name' => $type,
            'hint' => $typeHintEnabled
        );
    }

} 
