<?php

    require('ppm');

    import('net.intellivoid.acm2');

    $acm = new \acm2\acm2('Acm Test');

    $schema = new \acm2\Objects\Schema();
    $schema->setName('Database');
    $schema->setDefinition('Driver', 'MySQL');
    $schema->setDefinition('Host', '127.0.0.1');
    $schema->setDefinition('Host2', '127.0.0.2');
    $schema->setDefinition('Port', 12345);
    $schema->setDefinition('AuthenticationMethods', [
        'foo' => 'bar',
        'username' => 'password'
    ]);

    $acm->defineSchema($schema);

    var_dump($acm->getConfiguration('Database'));