<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Listener\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'sylius.menu.admin.main', method: 'addAdminMenuItems')]
final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $newSubmenu = $menu->getChild('configuration');

        $newSubmenu
            ?->addChild('authorized_domain', ['route' => 'synolia_admin_oauth_admin_authorized_domain_index'])
            ->setLabel('synolia.sylius_admin_oauth.ui.admin.menu.oauth_submenu_label')
            ->setLabelAttribute('icon', 'cube')
        ;
    }
}
