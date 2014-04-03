<?php

namespace Wesnick\DrupalBootstrap\Builder;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Output\OutputInterface;
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
     * @var array
     */
    protected $definition;

    protected $version;

    function __construct()
    {
        $this->readSiteProperties();
    }


    private function getClassForEntity($entityType)
    {
        switch ($entityType) {
            case 'node':
                return '\\Wesnick\\DrupalBootstrap\\Builder\\Entity\\NodeTypeBuilder';
                break;
            case 'taxonomy_vocabulary':
                return '\\Wesnick\\DrupalBootstrap\\Builder\\Entity\\VocabularyBuilder';
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

    /**
     * @return array
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    public function loadSampleNodesOfType($type)
    {
        $result = db_query("SELECT nid FROM {node} WHERE type = '%s' AND status = 1 LIMIT 20", $type);
        while ($r = db_fetch_array($result)) {
            $rows[$r['nid']] = node_load($r['nid']);
        }
        return $rows;
    }
    public function loadNodesCounts($type)
    {
        $result = db_query("SELECT count(nid) as count FROM {node} WHERE type = '%s' AND status = 1", $type);
        return db_result($result);

    }

    public function readSiteProperties($version = 7)
    {
        if ($version == 6) {
            $this->readDrupal6SiteProperties();
        } else {
            $this->readDrupal7SiteProperties();
        }
    }

    /**
     * Drupal 6
     */
    protected function readDrupal7SiteProperties()
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
                            $node_type = node_type_load($bundle);
                            $this->entityTypes[] = new NodeTypeBuilder($bundle, $bundleInfo['label'], $node_type->description);
                            break;
                        case 'taxonomy_term':
                            $vocabulary = taxonomy_vocabulary_machine_name_load($bundle);
                            $this->entityTypes[] = new VocabularyBuilder($bundle, $bundleInfo['label'], $vocabulary->description);
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
    /**
     * Drupal 6
     */
    protected function readDrupal6SiteProperties()
    {
        $node_types = node_get_types();
        $fields = content_fields();
        foreach ($node_types as $bundle => $entity_info) {

            $field_types = content_types($bundle);
            $instances = array();
            foreach ($field_types['fields'] as $fieldName => $fieldInfo) {
                $instance = array(
                    'entity_type' => 'node',
                    'bundle' => $bundle,
                    'field_name' => $fieldName,
                    'label' => $fieldInfo['widget']['label'],
                    'description' => $fieldInfo['widget']['description'],
                    'widget' => array('type' => $fieldInfo['widget']['type'], 'settings' => array()),
                );
                $instances[$fieldName] = $this->processInstance($instance);
            }
            $this->entityTypes[] = new NodeTypeBuilder($bundle, $entity_info->name, $entity_info->description, $instances);
        }

        $vocs = taxonomy_get_vocabularies();

        foreach ($vocs as $bundle => $entity_info) {
            $this->entityTypes[] = new VocabularyBuilder($entity_info->vid, $entity_info->name, $entity_info->description);
        }

        foreach ($fields as $fieldName => $fieldInfo) {
            $this->fields[] = new FieldBuilder($fieldName, $fieldInfo['type']);
        }

        $this->parseDefinition();

    }



    protected function processInstance($instance)
    {
        $widget = new WidgetBuilder($instance['widget']['type'], $instance['widget']['settings']);
        $instanceBuilder = new AttachedInstanceBuilder($instance['field_name'], $instance['label'], $widget);
        $instanceBuilder->setBundle($instance['bundle']);
        $instanceBuilder->setDescription($instance['description']);
        $instanceBuilder->setEntityType($instance['entity_type']);
        return $instanceBuilder;
    }

    protected function parseDefinition()
    {
        // Node Types
        /** @var $node NodeTypeBuilder */
        foreach (array_filter($this->entityTypes, array($this, 'isNodeBuilder')) as $node) {
            $this->definition['types']['node'][$node->getBundle()] = $node;
        }
        /** @var $voc VocabularyBuilder */
        foreach (array_filter($this->entityTypes, array($this, 'isVocabularyBuilder')) as $voc) {
            $this->definition['types']['taxonomy_vocabulary'][$voc->getBundle()] = $voc;
        }

        /** @var $field FieldBuilder */
        foreach ($this->fields as $field) {
            $this->definition['fields'][$field->getName()] = $field->getType();
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

    public function dumpToMarkdown($base)
    {
        $this->buffer = '';
        $this->csv = array();
        /** @var $entity AbstractEntityTypeBuilder */
        foreach ($this->definition['types'] as $entityType => $entityBuilders) {

            $this->writeln('# ' . $entityType);
            $this->writeln();

            foreach ($entityBuilders as $entity) {

                $this->writeln('## ' . $entity->getLabel());
                $this->writeln();

                $this->writeln('**Machine Name**: ' . $entity->getBundle());
                $this->writeln();
                $this->writeln('**Description**: ' . $entity->getDescription());
                $this->writeln();


                if ($entity->getInstances()) {

                    $this->writeln('**Structure**:');

                    $this->writeln();
                    $this->writeln('|Field Label|Field Name|Field Type|Notes|');
                    $this->writeln('|-|-|-|-|');

                    foreach ($entity->getInstances() as $instance) {
                        $this->csv[] = array($entity->getEntityType(), $entity->getBundle(), trim($instance->getLabel()), trim($instance->getFieldName()), trim($instance->getWidget()->getType()), trim($instance->getDescription()));
                        $tableRow = sprintf('|%s|%s|%s|%s', trim($instance->getLabel()), trim($instance->getFieldName()), trim($instance->getWidget()->getType()), trim($instance->getDescription()));
                        $tableRow = str_replace("\n", "", $tableRow);
                        $this->writeln($tableRow);

                    }
                }
            }

        }

        file_put_contents($base . '/markdown.md', $this->buffer);
        $handle = fopen($base . '/type.csv', 'w');
        foreach ($this->csv as $line) {
            fputcsv($handle, $line);
        }

        fclose($handle);


    }

    public function write($str)
    {
        $this->buffer .= $str . "\n";
        return $this;
    }

    public function writeLn($str = '')
    {
        $this->buffer .= $str . "\n";
        return $this;
    }

    public function dumpToConsole(OutputInterface $output, TableHelper $table)
    {
        $table->setHeaders(array('Entity Type', 'Bundle', 'Label', 'Description'));
        /** @var $node AbstractEntityTypeBuilder */
        foreach ($this->entityTypes as $entity) {
            $rows[] = array($entity->getEntityType(), $entity->getBundle(), $entity->getLabel(), $entity->getDescription());
        }

        $table->setRows($rows);
        $rows = array();

        $table->render($output);

        $table->setHeaders(array('Field Name', 'Field Type'));
        /** @var $field FieldBuilder */
        foreach ($this->fields as $field) {
            $rows[] = array($field->getName(), $field->getType());
        }

        $table->setRows($rows);

        $table->render($output);


        $rows = array();

        $table->setHeaders(array('Entity', 'Field', 'Label', 'Widget'));
        /** @var $instance AttachedInstanceBuilder */
        foreach ($this->instances as $instance) {
            $rows[] = array($instance->getEntityType() . ':' . $instance->getBundle(), $instance->getFieldName(), $instance->getLabel(), $instance->getWidget()->getType());
        }

        $table->setRows($rows);

        $table->render($output);



    }

} 
