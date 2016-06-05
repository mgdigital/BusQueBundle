<?php

namespace MGDigital\BusQueBundle\Features\Context;

use Behat\Symfony2Extension\Context\KernelAwareContext;
use MGDigital\BusQue\Features\Context\AbstractFeatureContext;
use MGDigital\BusQue\Implementation;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext extends AbstractFeatureContext implements KernelAwareContext
{

    const SERVICE_ID = 'busque.implementation';

    private $kernel;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function getKernel(): KernelInterface
    {
        return $this->kernel;
    }

    protected function getImplementation(): Implementation
    {
        return $this->getKernel()->getContainer()->get(self::SERVICE_ID);
    }
}
