<?php

namespace Wesnick\DrupalBootstrap\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Wesnick\DrupalBootstrap\Builder\SiteBuilder;


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
                'target', InputArgument::REQUIRED,
                '/path/to/your/drupal',
                null
            )
            ->addArgument(
                'output', InputArgument::OPTIONAL,
                'output destination for site definition yaml file',
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
        $builder = new SiteBuilder();
        $builder->readSiteProperties();

        if ($file = $input->getArgument('output')) {
            $builder->dumpToYamlFile($file);
        } else {
            $output->writeln("no export so dumping to console...");
        }

        return 0;
    }


}
