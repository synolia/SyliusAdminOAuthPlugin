<?php

//namespace Synolia\SyliusAdminOauthPlugin\Factory;
namespace App\Factory;

use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Model\UserOAuth;

class UserOauthFactory
{
    public static function create(UserInterface | AdminUserInterface $user): UserOAuth
    {
        $oauthUser = new UserOAuth();
        $oauthUser->setUser($user);
        $oauthUser->setProvider('google');
        $oauthUser->setIdentifier($user->getUserIdentifier());
        $oauthUser->setAccessToken($user->getOAuthAccount('google')->getAccessToken());
        return $oauthUser;
    }
}
