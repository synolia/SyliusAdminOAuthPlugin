<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Listener\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $newSubmenu = $menu
            ->addChild('authorized_domain')
            ->setLabel('Oauth')
        ;

        $newSubmenu
            ->addChild('authorized_domain', ['route' => 'app_admin_authorized_domain_index'])
            ->setLabel('sylius.ui.admin.menu.oauth_submenu_label')
        ;
    }
}
