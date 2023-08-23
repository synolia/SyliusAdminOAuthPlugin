<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Security;

final class Providers
{
    /**
     * @var array|string[]
     */
    public array $availableProvidersAndControllers = [
        'google' => 'GoogleController',
        'microsoft' => 'MicrosoftController',
    ];
}
