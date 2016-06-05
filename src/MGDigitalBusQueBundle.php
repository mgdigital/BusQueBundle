<?php

namespace MGDigital\BusQueBundle;

use MGDigital\BusQueBundle\DependencyInjection\MGDigitalBusQueExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MGDigitalBusQueBundle extends Bundle
{

    public function getContainerExtension()
    {
        return new MGDigitalBusQueExtension();
    }
}
