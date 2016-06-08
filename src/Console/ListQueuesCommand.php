<?php

namespace MGDigital\BusQue\Bundle\Console;

use MGDigital\BusQue\BusQue;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListQueuesCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('busque:list_queues');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders([
            'Queue name',
            'Queued',
            'In progress',
        ]);
        $busQue = new BusQue($this->getImplementation());
        foreach ($busQue->listQueues() as $queueName) {
            $table->addRow([
                $queueName,
                $busQue->getQueuedCount($queueName),
                $busQue->getInProgressCount($queueName)
            ]);
        }
        $table->render();
    }
}
