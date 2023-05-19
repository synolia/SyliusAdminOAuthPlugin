<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()      // Automatically injects dependencies in your services.
        ->autoconfigure() // Automatically registers your services as commands, event subscribers, etc.
    ;

    $services->load('Synolia\SyliusAdminOauthPlugin\\', '../src/*')
             ->exclude('../src/{DependencyInjection,Entity,Kernel.php}')
             ->load('Synolia\SyliusAdminOauthPlugin\Controller\\', '../src/Controller')
             ->tag('controller.service_arguments')
    ;
};
