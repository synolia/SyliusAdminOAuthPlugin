<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Factory;

use App\Entity\User\AdminUser as customAdminUser;
use League\OAuth2\Client\Provider\GoogleUser;
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

final class AdminUserFactory
{
    public static function createByGoogleAccount(GoogleUser $googleUser): customAdminUser
    {
        $user = new customAdminUser();
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

    public static function createByMicrosoftAccount(AzureResourceOwner $microsoftUser, string $locale): customAdminUser
    {
        $user = new customAdminUser();
        $user->setEmail($microsoftUser->getUpn());
        $user->setEmailCanonical($microsoftUser->getUpn());
        $user->setUsername($microsoftUser->getFirstName() . '_' . $microsoftUser->getLastName() . '_' . random_int(1, 100));
        $user->setFirstName($microsoftUser->getFirstname());
        $user->setLastName($microsoftUser->getLastname());
        $user->setLocaleCode($locale);
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        /** @var string|null $microsoftId */
        $microsoftId = $microsoftUser->getId();
        $user->setMicrosoftId($microsoftId);

        return $user;
    }
}
