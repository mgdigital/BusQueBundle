<?php

namespace MGDigital\BusQue\Bundle\Console;

use MGDigital\BusQue\BusQue;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowQueueCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('busque:show_queue')
            ->addArgument('queue', InputArgument::REQUIRED, 'The queue to work on.')
            ->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'The time in seconds to run the worker', 0)
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'The number of commands to show.', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $busQue = new BusQue($this->getImplementation());
        $queueName = $input->getArgument('queue');
        $offset = (int) $input->getOption('offset');
        $limit = (int) $input->getOption('limit');
        $ids = $busQue->listQueuedIds($queueName, $offset, $limit);
        $totalCount = $busQue->getQueuedCount($queueName);
        $output->writeln(sprintf(
            'Showing commands queued on "%s" (%d - %d of %d)',
            $queueName,
            $offset + 1,
            $offset + 1 + count($ids),
            $totalCount
        ));
        foreach ($ids as $id) {
            $command = $busQue->getCommand($queueName, $id);
            $serialized = $busQue->serializeCommand($command);
            $table = new Table($output);
            $table
                ->addRow(['id', $id])
                ->addRow(['class', get_class($command)])
                ->addRow(['serialized', $serialized]);
            $table->render();
        }
    }
}
