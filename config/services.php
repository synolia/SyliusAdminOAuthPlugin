<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()      // Automatically injects dependencies in your services.
        ->autoconfigure(); // Automatically registers your services as commands, event subscribers, etc.

    $services
        ->load('Synolia\SyliusAdminOauthPlugin\\', '../src/*')
        ->exclude([
            '../src/DependencyInjection',
            '../src/Entity',
            '../src/SynoliaSyliusAdminOauthPlugin.php',
        ])
        ->load('Synolia\SyliusAdminOauthPlugin\Controller\\', '../src/Controller')
        ->tag('controller.service_arguments')
        ->load('Synolia\SyliusAdminOauthPlugin\Listener\Menu\\', '../src/Listener/Menu')
        ->tag('kernel.event_listener', [
            'event' => 'sylius.menu.admin.main',
            'method' => 'addAdminMenuItems'
        ])
        ->load('Synolia\SyliusAdminOauthPlugin\Form\Type\\', "../src/Form/Type")
        ->args([
            'Synolia\SyliusAdminOauthPlugin\Entity\Domain\AuthorizedDomain',
            ['sylius', 'default']
        ])
        ->tag('type', [
            'name' => 'form.type'
        ])
        ->load('Synolia\SyliusAdminOauthPlugin\Security\Authenticator\\', '../src/Security/Authenticator')
        ->args([
            '$googleClientId' => '%env(default::SYNOLIA_ADMIN_OAUTH_GOOGLE_CLIENT_ID)%',
            '$microsoftClientId' => '%env(default::SYNOLIA_ADMIN_OAUTH_MICROSOFT_CLIENT_ID)%',
        ]);
};
