MGDigitalBusQueBundle
=====================

[![Build Status](https://travis-ci.org/mgdigital/BusQueBundle.svg?branch=master)](https://travis-ci.org/mgdigital/BusQueBundle)

Provides a Symfony bundle for [BusQue](https://github.com/mgdigital/BusQue), a Command Queue and Scheduler for PHP7.


Installation
------------

Install with composer:

    composer require mgdigital/busque-bundle


Configuration
-------------

The default configuration is as follows:

```yaml
busque:
    implementation:
        queue_name_resolver: busque.queue_name_resolver.classname
        command_serializer: busque.command_serializer.php
        command_id_generator: busque.command_id_generator.object_hash
        queue_adapter: busque.queue_adapter.predis
        predis_client: snc_redis.busque_client
        scheduler_adapter: busque.scheduler_adapter.predis
        clock: busque.system_clock
        commandbus_adapter: busque.commandbus_adapter.tactician
        error_handler: busque.error_handler
```

Configure the Redis client:

```yaml
snc_redis:
    clients:
        busque:
            type: predis
            alias: busque
            dsn: 'redis://localhost'
            logging: false
            options:
                prefix: 'busque'
```


Usage
-----

Refer to the [BusQue](https://github.com/mgdigital/BusQue) README.


Tests
-----

[![Build Status](https://travis-ci.org/mgdigital/BusQueBundle.svg?branch=master)](https://travis-ci.org/mgdigital/BusQueBundle)

Run the Behat suite:

    bin/behat
