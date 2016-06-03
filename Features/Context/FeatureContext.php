<?php

namespace MGDigital\BusQueBundle\Features\Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use MGDigital\BusQue\ClockInterface;
use MGDigital\BusQue\CommandBusAdapterInterface;
use MGDigital\BusQue\CommandHandler;
use MGDigital\BusQue\CommandIdGeneratorInterface;
use MGDigital\BusQue\Exception\TimeoutException;
use MGDigital\BusQue\Implementation;
use MGDigital\BusQue\QueuedCommand;
use MGDigital\BusQue\QueueNameResolverInterface;
use MGDigital\BusQue\QueueWorker;
use MGDigital\BusQue\ScheduledCommand;
use MGDigital\BusQue\SchedulerWorker;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext implements SnippetAcceptingContext, KernelAwareContext
{

    const SERVICE_ID = 'busque.implementation';

    /**
     * @var Implementation
     */
    private $implementation;

    private $kernel;

    private $prophet;
    private $commandBus;
    private $commandIdGenerator;
    private $queueNameResolver;
    private $clock;

    /**
     * @BeforeScenario
     */
    public function setup()
    {
        $this->prophet = new Prophet();
        $this->commandBus = $this->prophet->prophesize(CommandBusAdapterInterface::class);
        $this->commandIdGenerator = $this->prophet->prophesize(CommandIdGeneratorInterface::class);
        $this->commandIdGenerator->generateId(Argument::any())->willReturn('test_command_id');
        $this->queueNameResolver = $this->prophet->prophesize(QueueNameResolverInterface::class);
        $this->queueNameResolver->resolveQueueName(Argument::any())->willReturn('test_queue');
        $this->clock = $this->prophet->prophesize(ClockInterface::class);
        $this->implementation = $this->getKernel()->getContainer()->get(self::SERVICE_ID)
            ->setCommandBusAdapter($this->commandBus->reveal())
            ->setCommandIdGenerator($this->commandIdGenerator->reveal())
            ->setQueueNameResolver($this->queueNameResolver->reveal())
            ->setClock($this->clock->reveal());
    }

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getKernel(): KernelInterface
    {
        return $this->kernel;
    }

    /**
     * @Given the queue has been emptied
     */
    public function theQueueHasBeenEmptied()
    {
        $this->implementation->getQueueAdapter()->emptyQueue('test_queue');
    }

    /**
     * @Then there should be :arg1 commands in the queue
     */
    public function thereShouldBeNCommandsInTheQueue(int $arg1)
    {
        $count = $this->implementation->getQueueAdapter()->getQueuedCount('test_queue');
        \PHPUnit_Framework_Assert::assertEquals($count, $arg1);
    }

    /**
     * @Given I queue :command
     */
    public function iQueueACommand($command)
    {
        $this->queueCommand($command);
    }

    /**
     * @Given I queue :command with ID :id
     */
    public function iQueueACommandWithId($command, string $id)
    {
        $this->queueCommand($command, $id);
    }

    private function queueCommand($command, string $id = null)
    {
        $handler = new CommandHandler($this->implementation);
        $handler->handleQueued(new QueuedCommand($command, $id ?? 'test_command_id'));
    }

    /**
     * @When I run the queue worker
     */
    public function iRunTheQueueWorker()
    {
        $worker = new QueueWorker($this->implementation);
        try {
            $worker->work('test_queue', 1, 1);
        } catch (TimeoutException $e) {}
    }

    /**
     * @Then the command should have run
     */
    public function theCommandShouldHaveRun()
    {
        $this->commandBus->handle('test_command')->shouldHaveBeenCalled();
    }

    /**
     * @Then the command should have a status of :arg1
     */
    public function theCommandShouldHaveAStatusOf($arg1)
    {
        $status = $this->implementation->getQueueAdapter()->getCommandStatus('test_queue', 'test_command_id');
        \PHPUnit_Framework_Assert::assertEquals($status, $arg1);
    }

    /**
     * @Given I schedule :command to run at :arg1::arg2
     */
    public function iScheduleACommandToRunAt($command, $arg1, $arg2)
    {
        $this->iScheduleACommandWithIdToRunAt($command, 'test_command_id', $arg1, $arg2);
    }

    /**
     * @Given I schedule :command with ID :id to run at :arg1::arg2
     */
    public function iScheduleACommandWithIdToRunAt($command, $id, $arg1, $arg2)
    {
        $time = new \DateTime('@' . mktime($arg1, $arg2));
        $this->scheduleCommand($command, $time, $id);
    }

    private function scheduleCommand($command, \DateTime $dateTime, string $id)
    {
        $hander = new CommandHandler($this->implementation);
        $hander->handleScheduled(new ScheduledCommand($command, $dateTime, $id));
    }

    /**
     * @Given the time is :arg1::arg2
     */
    public function theTimeIs($arg1, $arg2)
    {
        $this->clock->getTime()->willReturn(new \DateTime('@' . mktime($arg1, $arg2)));
    }

    /**
     * @When I run the scheduler worker
     */
    public function iRunTheSchedulerWorker()
    {
        $worker = new SchedulerWorker($this->implementation);
        try {
            $worker->work(1, 1);
        } catch (TimeoutException $e) {}
    }
}