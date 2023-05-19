<?php

//namespace Synolia\SyliusAdminOauthPlugin\Factory;
namespace Synolia\SyliusAdminOauthPlugin\Factory;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Model\UserOAuth;

class UserOauthFactory
{
    public static function create(AdminUserInterface $user): UserOAuth
    {
        $oauthUser = new UserOAuth();
        $oauthUser->setUser($user);
        $oauthUser->setProvider('google_main');
        $oauthUser->setIdentifier($user->getUserIdentifier());
//        $oauthUser->setAccessToken($user->getOAuthAccount('google_main')->getAccessToken());
        return $oauthUser;
    }
}
