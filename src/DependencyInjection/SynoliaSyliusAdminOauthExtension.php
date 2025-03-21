<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Synolia\SyliusAdminOauthPlugin\Entity\Domain\AuthorizedDomain;

final class SynoliaSyliusAdminOauthExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $this->registerResources(
            'synolia_admin_oauth',
            $config['driver'],
            $config['resources'],
            $container
        );
        $loader = new YamlFileLoader($container, new FileLocator(\dirname(__DIR__, 2) . '/config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->setParameter('synolia_admin_oauth.model.authorized_domain.class', AuthorizedDomain::class);
        $this->prependDoctrineMigrations($container);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'Synolia\SyliusAdminOauthPlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@SynoliaSyliusAdminOauthPlugin/migrations';
    }

    /**
     * @return array<string>
     */
    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }
}
