<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Factory;

use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\User\Model\UserOAuth;

final class UserOauthFactory
{
    public static function create(AdminUserInterface $user): UserOAuth
    {
        $oauthUser = new UserOAuth();
        $oauthUser->setUser($user);
        $oauthUser->setProvider('google_main');
        $oauthUser->setIdentifier($user->getUserIdentifier()); /** @phpstan-ignore-line */
        //        $oauthUser->setAccessToken($user->getOAuthAccount('google_main')->getAccessToken());
        return $oauthUser;
    }
}
