<?php

namespace Wesnick\DrupalBootstrap\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Wesnick\DrupalBootstrap\Builder\Field\FieldBuilder;
use Wesnick\DrupalBootstrap\Builder\Field\InstanceBuilder;
use Wesnick\DrupalBootstrap\Builder\Field\WidgetBuilder;
use Wesnick\DrupalBootstrap\Builder\SiteBuilder;


/**
 * Class SiteBuilderCommand
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class SiteBuilderCommand extends Command
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
        return $this
            ->setName('dr:build')
            ->addArgument(
                'target', InputArgument::REQUIRED,
                '/path/to/your/drupal',
                null
            )
            ->addArgument(
                'import', InputArgument::OPTIONAL,
                'your site definition yaml file',
                null
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dialog = $this->getHelper('dialog');
        $this->output = $output;

        $path = $input->getArgument('target');
        $drupal = $this->getHelper('drupal-bootstrap');
        $drupal->boot($path);

        if ($file = $input->getArgument('import')) {
            $builder = new SiteBuilder();
            $builder->importFromYamlFile($file);
            $builder->buildFromDefinition();

        } else {
            // run interactive
            $this->mainQuestion();
        }

        return 0;
    }



    protected function mainQuestion()
    {
        $actions = array(
            'n' => 'Create a Node Type',
            'f' => 'Create a Field',
            'i' => 'Add instance to entity',
            'e' => 'Exit (press enter)'
        );

        $action = $this->dialog->select(
            $this->output,
            'Execute command',
            $actions,
            'e'
        );

        switch ($action) {
            case 'n':
                $this->createEntityType();
                break;
            case 'f':
                $this->createField();
                break;
            case 'i':
                $this->createInstance();
                break;
            default:
            case 'e':
                return 0;

        }
    }

    protected function createEntityType()
    {

        $type = $this->dialog->ask(
            $this->output,
            'Node Type machine name'
        );

        $name = $this->dialog->ask(
            $this->output,
            'Node Type pretty name'
        );



        $this->output->writeln("$name and $type");

        $this->mainQuestion();

    }

    protected function createField()
    {


        $name = $this->dialog->ask(
            $this->output,
            'Field machine name: field_'
        );

        $field_types = FieldBuilder::getFieldTypes();

        $list = array();
        foreach ($field_types as $field => $definition) {
            $list[$field] = sprintf("%s (%s)", $definition['label'], $field);
        }

        $values = array_keys($list);

        $type = $this->dialog->select(
            $this->output,
            'Field Type',
            array_values($list),
            0
        );
        $type = $values[$type];

        $fieldBuilder = new FieldBuilder('field_' . $name, $type);
        $fieldBuilder->build();

        $this->output->writeln("Added Field $name ($type)");

        $this->mainQuestion();

    }


    protected function createInstance()
    {

        $entities = entity_get_info();
        $entityList = array_keys($entities);

        $entity = $this->dialog->select(
            $this->output,
            'What entity? ',
            $entityList
        );
        $entity = $entityList[$entity];

        $bundles = field_info_bundles($entity);
        $bundleList = array_keys($bundles);

        $bundle = $this->dialog->select(
            $this->output,
            'What bundle? ',
            $bundleList
        );
        $bundle = $bundleList[$bundle];

        $field_types = FieldBuilder::getFields();
        $fieldList = array();
        foreach ($field_types as $field => $definition) {
            $fieldList[$field] = sprintf("%s (%s)", $definition['label'], $field);
        }

        $fieldValues = array_keys($field_types);
        $field = $this->dialog->select(
            $this->output,
            'Field To Attach',
            array_values($fieldList)
        );
        $field = $fieldValues[$field];

        $widget_types = WidgetBuilder::getTypesForFieldType($field_types[$field]['type']);
        $widgetList = array_keys($widget_types);
        $widget = $this->dialog->select(
            $this->output,
            'Widget to Use',
            array_values($widget_types)
        );
        $widget = $widgetList[$widget];

        $label = $this->dialog->ask(
            $this->output,
            'What label for this instance? '
        );

        $widgetDefinition = new WidgetBuilder($widget, $label);

        $builder = new InstanceBuilder($field, $label, $widgetDefinition);
        $builder->build($entity, $bundle);

        $this->output->writeln("Created instance of field $field with label <info>$label</info> on $entity:$bundle");

        $this->mainQuestion();
    }
} 
