<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Factory;

use League\OAuth2\Client\Provider\GoogleUser;
use Sylius\Component\Core\Model\AdminUser;
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

final class AdminUserFactory
{
    public static function createByGoogleAccount(GoogleUser $googleUser, string $locale): AdminUser
    {
        $user = new AdminUser();
        $user->setEmail($googleUser->getEmail());
        $user->setEmailCanonical($googleUser->getEmail());
        $user->setUsername(self::setUsername($googleUser->getFirstName(), $googleUser->getLastName()));
        $user->setFirstName($googleUser->getFirstName());
        $user->setLastName($googleUser->getLastName());
        $user->setHostedDomain($googleUser->getHostedDomain());
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setLocaleCode($locale);
        /** @var string|null $googleId */
        $googleId = $googleUser->getId();
        $user->setGoogleId($googleId);

        return $user;
    }

    public static function createByMicrosoftAccount(AzureResourceOwner $microsoftUser, string $locale): AdminUser
    {
        $user = new AdminUser();
        $user->setEmail($microsoftUser->getUpn());
        $user->setEmailCanonical($microsoftUser->getUpn());
        $user->setUsername(self::setUsername($microsoftUser->getFirstName(), $microsoftUser->getLastName()));
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

    public static function setUsername(string $firstname, string $lastname): string
    {
        return $firstname . '_' . $lastname . '_' . random_int(1, 100);
    }
}
