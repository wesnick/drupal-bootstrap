<?php

namespace Wesnick\DrupalBootstrap\Builder;
use Wesnick\DrupalBootstrap\Writer\DrupalCodeWriter;


/**
 * Class VarBuilder
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class VarBuilder
{

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $name;

    function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function writeObjectLiteral(DrupalCodeWriter $writer)
    {
        $this->writeObjectLiteralRecursion($this->value, $writer);
    }

    private function writeObjectLiteralRecursion($value, DrupalCodeWriter $writer, $curried = 1)
    {
        switch (gettype($value)) {
            case 'int':
            case 'double':
                $writer->write($value);
                break;
            case 'bool':
                if ($value) {
                    $writer->write("true");
                } else {
                    $writer->write("false");
                }
                break;
            case 'string':
                $writer->write("'" . $value . "'");
                break;
            case 'array':
                $writer->writeLine('array(');

                $is_assoc = $this->isAssociative($value);
                foreach ($value as $key => $val) {
                    $writer->curriedIndent($curried);
                    if ( ! $is_assoc) {
                        $this->writeObjectLiteralRecursion($val, $writer, $curried);
                        $writer->writeLine(",");
                    } else {
                        $writer->write("'" . $key . "' => ");
                        $this->writeObjectLiteralRecursion($val, $writer, $curried + 1);
                        $writer->writeLine(",");
                    }

                }
                $writer
                    ->curriedIndent($curried - 1)
                    ->write(')')
                ;
                break;
            default:
                return null;
        }
    }

    private function isAssociative($arr)
    {
        return (bool) count(array_filter(array_keys($arr), 'is_string'));
    }

    public function hasValue()
    {
        return null !== $this->value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


} 
