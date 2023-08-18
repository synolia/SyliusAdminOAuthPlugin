<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Factory;

use App\Entity\User\AdminUser;
use League\OAuth2\Client\Provider\GoogleUser;
use Synolia\SyliusAdminOauthPlugin\Model\MicrosoftUser;

final class AdminUserFactory
{
    public static function createByGoogleAccount(GoogleUser $googleUser): AdminUser
    {
        $user = new AdminUser();
        $user->setEmail($googleUser->getEmail());
        $user->setEmailCanonical($googleUser->getEmail());
        $user->setUsername($googleUser->getName());
        $user->setFirstName($googleUser->getFirstName());
        $user->setLastName($googleUser->getLastName());
        $user->setHostedDomain($googleUser->getHostedDomain());
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setLocaleCode($googleUser->getLocale());
        /** @var string|null $googleId */
        $googleId = $googleUser->getId();
        $user->setGoogleId($googleId);

        return $user;
    }

    public static function createByMicrosoftAccount(MicrosoftUser $microsoftUser): AdminUser
    {
        $user = new AdminUser();
        $user->setEmail($microsoftUser->getEmail());
        $user->setEmailCanonical($microsoftUser->getEmailCanonical());
        $user->setUsername($microsoftUser->getUsername());
        $user->setFirstName($microsoftUser->getFirstname());
        $user->setLastName($microsoftUser->getLastname());
        //        TODO: get user's locale code
        $user->setLocaleCode('fr_FR');
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        /** @var string|null $googleId */
        $microsoftId = $microsoftUser->getId();
        $user->setMicrosoftId($microsoftId);

        return $user;
    }
}
