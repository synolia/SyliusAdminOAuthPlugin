<?php

//namespace Synolia\SyliusAdminOauthPlugin\Factory;
namespace App\Factory;



use App\Entity\User\CustomAdminUser;
use League\OAuth2\Client\Provider\GoogleUser;

class UserFactory
{
    public static function createByGoogleAccount(GoogleUser $googleUser): CustomAdminUser
    {
        $user = new CustomAdminUser();
        $user->setEmail($googleUser->getEmail());
        $user->setEmailCanonical($googleUser->getEmail());
        $user->setUsername($googleUser->getName());
        $user->setGoogleId($googleUser->getId());
        $user->setFirstName($googleUser->getFirstName());
        $user->setLastName($googleUser->getLastName());
        $user->setHostedDomain($googleUser->getHostedDomain());
        $user->setAvatar($googleUser->getAvatar());
        $user->setCreatedAt(new \DateTimeImmutable("now"));

        // Todo: Voir si cette méthode dans un service n'est pas mieux
//        /** @var AdminUserInterface $admin */
//        $admin = $this->container->get('sylius.factory.admin_user')->createNew();
//
//        $this->container->get('sylius.repository.admin_user')->add($admin);

        return $user;
    }
}
