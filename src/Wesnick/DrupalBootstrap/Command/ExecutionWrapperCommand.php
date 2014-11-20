<?php

namespace Wesnick\DrupalBootstrap\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class ExecutionWrapperCommand
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class ExecutionWrapperCommand extends Command
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
            ->setName('exec')
            ->addArgument(
                'src', InputArgument::REQUIRED,
                '/path/to/your/drupal',
                null
            )
            ->addArgument(
                'code', InputArgument::OPTIONAL,
                'php code to execute',
                null
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dialog = $this->getHelper('dialog');
        $this->output = $output;

        if ( ! $command = $input->getArgument('code')) {
            // run interactive
            $command = $this->requestCode();
        }

        $path = $input->getArgument('src');
        $drupal = $this->getHelper('drupal-bootstrap');
        $drupal->boot($path);

        eval($command);

        return 0;
    }



    protected function requestCode()
    {

        $action = $this->dialog->ask(
            $this->output,
            'drupal> ',
            null
        );

        return $action;
    }

} 
