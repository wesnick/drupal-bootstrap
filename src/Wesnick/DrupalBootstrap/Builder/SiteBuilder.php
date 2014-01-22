<?php

namespace Wesnick\DrupalBootstrap\Builder;
use Symfony\Component\Yaml\Yaml;
use Wesnick\DrupalBootstrap\Builder\Entity\AbstractEntityTypeBuilder;
use Wesnick\DrupalBootstrap\Builder\Entity\NodeTypeBuilder;
use Wesnick\DrupalBootstrap\Builder\Entity\VocabularyBuilder;
use Wesnick\DrupalBootstrap\Builder\Field\AttachedInstanceBuilder;
use Wesnick\DrupalBootstrap\Builder\Field\FieldBuilder;
use Wesnick\DrupalBootstrap\Builder\Field\InstanceBuilder;
use Wesnick\DrupalBootstrap\Builder\Field\WidgetBuilder;


/**
 * Class SiteBuilder
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class SiteBuilder
{

    /**
     * @var AbstractEntityTypeBuilder[]
     */
    protected $entityTypes;

    /**
     * @var VocabularyBuilder[]
     */
    protected $vocabularies;

    protected $groups;

    /**
     * @var FieldBuilder[]
     */
    protected $fields;

    /**
     * @var InstanceBuilder[]
     */
    protected $instances;

    /**
     * @var array
     */
    protected $definition;

    private function getClassForEntity($entityType)
    {
        switch ($entityType) {
            case 'node':
                return '\\Wesnick\\DrupalBootstrap\\Definition\\NodeTypeBuilder';
                break;
            case 'taxonomy_vocabulary':
                return '\\Wesnick\\DrupalBootstrap\\Definition\\VocabularyBuilder';
                break;
            default:
                throw new \Exception("Unhandled Entity Type: %s", $entityType);
        }
    }


    public function buildFromDefinition()
    {
        foreach ($this->definition['types'] as $entityType => $bundles) {
            foreach ($bundles as $bundleName => $info) {
                $class = $this->getClassForEntity($entityType);
                /** @var $builder AbstractEntityTypeBuilder */
                $builder = new $class($bundleName, $info['label'], $info['description']);
                $builder->build();
            }
        }

        foreach ($this->definition['fields'] as $fieldName => $fieldType) {
            $builder = new FieldBuilder($fieldName, $fieldType);
            $builder->build();
        }

        foreach ($this->definition['instances'] as $entityType => $bundles) {
            foreach ($bundles as $bundleName => $fieldInfo) {
                foreach ($fieldInfo as $fieldName => $widgetType) {
                    $instanceBuilder = new AttachedInstanceBuilder($fieldName, $fieldName, new WidgetBuilder($widgetType));
                    $instanceBuilder->setEntityType($entityType);
                    $instanceBuilder->setBundle($bundleName);
                    $instanceBuilder->build();
                }
            }

        }
    }


    public function readSiteProperties()
    {
        $entityInfo = entity_get_info();
        $this->entityTypes = array();
        foreach ($entityInfo as $entity_type => $entity_info) {
            if (in_array($entity_type, array('node', 'taxonomy_term'))) {
                foreach ($entity_info['bundles'] as $bundle => $bundleInfo) {


                    if ($instanceInfo = field_info_instances($entity_type, $bundle)) {
                        foreach ($instanceInfo as $instance) {
                            $this->instances[] = $this->processInstance($instance);
                        }
                    }

                    switch ($entity_type) {
                        case 'node':
                            $this->entityTypes[] = new NodeTypeBuilder($bundle, $bundleInfo['label'], $bundleInfo['description']);
                            break;
                        case 'taxonomy_term':
                            $this->entityTypes[] = new VocabularyBuilder($bundle, $bundleInfo['label'], $bundleInfo['description']);
                            break;
                    }

                }
            }
        }

        $fieldInfo = field_info_fields();
        foreach ($fieldInfo as $fieldName => $info) {
            $this->fields[$fieldName] = new FieldBuilder($fieldName, $info['type']);
        }

        $this->parseDefinition();

    }

    protected function processInstance($instance)
    {
        $widget = new WidgetBuilder($instance['widget']['type']);
        $instanceBuilder = new AttachedInstanceBuilder($instance['field_name'], $instance['label'], $widget);
        $instanceBuilder->setBundle($instance['bundle']);
        $instanceBuilder->setEntityType($instance['entity_type']);
        return $instanceBuilder;
    }

    protected function parseDefinition()
    {
        // Node Types
        /** @var $node NodeTypeBuilder */
        foreach (array_filter($this->entityTypes, array($this, 'isNodeBuilder')) as $node) {
            $this->definition['types']['node'][$node->getBundle()]['label'] = $node->getLabel();
            $this->definition['types']['node'][$node->getBundle()]['description'] = $node->getDescription();
        }

        /** @var $voc VocabularyBuilder */
        foreach (array_filter($this->entityTypes, array($this, 'isVocabularyBuilder')) as $voc) {
            $this->definition['types']['taxonomy_vocabulary'][$voc->getBundle()]['label'] = $voc->getLabel();
            $this->definition['types']['taxonomy_vocabulary'][$voc->getBundle()]['description'] = $voc->getDescription();
        }

        /** @var $field FieldBuilder */
        foreach ($this->fields as $field) {
            $this->definition['fields'][$field->getName()] = $field->getType();
        }

        /** @var $instance AttachedInstanceBuilder */
        foreach ($this->instances as $instance) {
            $this->definition['instances'][$instance->getEntityType()][$instance->getBundle()][$instance->getFieldName()] = $instance->getWidget()->getDefinition()['type'];
        }

    }

    public function dumpToYamlFile($file)
    {
        $yaml = new Yaml();
        file_put_contents($file, $yaml->dump($this->definition, 5));
    }

    public function importFromYamlFile($file)
    {
        $yaml = new Yaml();
        $this->definition = $yaml->parse(file_get_contents($file));
    }

    public static function isNodeBuilder(AbstractEntityTypeBuilder $a)
    {
        return $a instanceof NodeTypeBuilder;
    }

    public static function isVocabularyBuilder(AbstractEntityTypeBuilder $a)
    {
        return $a instanceof VocabularyBuilder;
    }

} 
