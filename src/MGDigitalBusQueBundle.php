<?php

namespace MGDigital\BusQue\Bundle;

use MGDigital\BusQue\Bundle\DependencyInjection\MGDigitalBusQueExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MGDigitalBusQueBundle extends Bundle
{

    public function getContainerExtension()
    {
        return new MGDigitalBusQueExtension();
    }
}
