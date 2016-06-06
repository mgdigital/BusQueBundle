<?php

namespace MGDigital\BusQueBundle\Console;

use MGDigital\BusQue\SchedulerWorker;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SchedulerWorkerCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('busque:scheduler_worker')
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'The number of scheduled commands to queue.', null)
            ->addOption('time', null, InputOption::VALUE_OPTIONAL, 'The time in seconds to run the worker', null)
            ->addOption(
                'throttle',
                null,
                InputOption::VALUE_OPTIONAL,
                'Maximum scheduled commands to queue at a time.',
                SchedulerWorker::DEFAULT_THROTTLE
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getOption('limit');
        $throttle = $input->getOption('throttle');
        $time = $input->getOption('time');
        $worker = new SchedulerWorker($this->getImplementation());
        $output->writeln('Awaiting scheduled commands...');
        $worker->work($limit, $throttle, $time);
    }
}
