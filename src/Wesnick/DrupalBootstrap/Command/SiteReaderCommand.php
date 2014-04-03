<?php

namespace Wesnick\DrupalBootstrap\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Wesnick\DrupalBootstrap\Builder\Entity\AbstractEntityTypeBuilder;
use Wesnick\DrupalBootstrap\Builder\SiteBuilder;
use Wesnick\DrupalBootstrap\Console\DrupalBootstrapHelper;


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
        return $this
            ->setName('dr:read')
            ->addArgument(
                'src', InputArgument::REQUIRED,
                '/path/to/your/drupal_root',
                null
            )
            ->addOption(
                'output',
                null,
                InputOption::VALUE_OPTIONAL,
                'output destination for site definition yaml file'
            )
            ->addOption(
                'ver',
                null,
                InputOption::VALUE_OPTIONAL,
                "Drupal Version: 6 or 7", 7
            )
            ->addOption(
                'uri',
                null,
                InputOption::VALUE_OPTIONAL,
                "Drupal Version: 6 or 7",
                'default'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->dialog = $this->getHelper('dialog');
        $this->output = $output;

        $path = $input->getArgument('src');

        $version = $input->getOption('ver');
        $uri = $input->getOption('uri');

        /** @var $drupal DrupalBootstrapHelper */
        $drupal = $this->getHelper('drupal-bootstrap');

        $drupal->boot($path, $version, $uri);
        $builder = new SiteBuilder();

        $builder->dumpToConsole($output, $this->getHelper('table'));

    }


}
