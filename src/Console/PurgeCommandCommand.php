<?php

namespace MGDigital\BusQue\Bundle\Console;

use MGDigital\BusQue\BusQue;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeCommandCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('busque:purge_command')
            ->addArgument('queue', InputArgument::REQUIRED, 'The queue name')
            ->addArgument('id', InputArgument::REQUIRED, 'The command ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = $input->getArgument('queue');
        $id = $input->getArgument('id');
        $busQue = new BusQue($this->getImplementation());
        $output->writeln(sprintf(
            'Purging command ID "%s" on queue "%s" (current status: %s)',
            $id,
            $queueName,
            $busQue->getCommandStatus($queueName, $id)
        ));
        $busQue->purgeCommand($queueName, $id);
    }
}