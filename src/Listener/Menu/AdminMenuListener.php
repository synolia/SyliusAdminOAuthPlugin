<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Listener\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $newSubmenu = $menu->getChild('configuration');

        if (null !== $newSubmenu) {
            $newSubmenu
                        ->addChild('authorized_domain', ['route' => 'app_admin_authorized_domain_index'])
                        ->setLabel('sylius.ui.authorized_domains.menu_label')
                        ->setLabelAttribute('icon', 'cube');
        }
    }
}
