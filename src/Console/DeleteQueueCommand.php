<?php

namespace MGDigital\BusQue\Bundle\Console;

use MGDigital\BusQue\BusQue;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteQueueCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('busque:delete_queue')
            ->addArgument('queue', InputArgument::REQUIRED, 'The queue name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = $input->getArgument('queue');
        $busQue = new BusQue($this->getImplementation());
        $output->writeln(sprintf(
            'Deleting queue "%s" (%d items queued)',
            $queueName,
            $busQue->getQueuedCount($queueName)
        ));
        $busQue->deleteQueue($queueName);
    }
}
