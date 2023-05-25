<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @SuppressWarnings(UnusedLocalVariable)
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        return new TreeBuilder('synolia_sylius_admin_oauth_plugin');
    }
}
