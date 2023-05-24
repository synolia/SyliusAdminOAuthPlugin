<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Factory;

use App\Entity\User\AdminUser;
use League\OAuth2\Client\Provider\GoogleUser;

final class AdminUserFactory
{
    public static function createByGoogleAccount(GoogleUser $googleUser): AdminUser
    {
        $user = new AdminUser();
        $user->setEmail($googleUser->getEmail());
        $user->setEmailCanonical($googleUser->getEmail());
        $user->setUsername($googleUser->getName());
        $user->setGoogleId($googleUser->getId());
        $user->setFirstName($googleUser->getFirstName());
        $user->setLastName($googleUser->getLastName());
        $user->setHostedDomain($googleUser->getHostedDomain());
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setLocaleCode($googleUser->getLocale());
        /** @var string|null $googleId */
        $googleId = $googleUser->getId();
        $user->setGoogleId($googleId);
        $user->setCreatedAt(new \DateTimeImmutable("now"));
        $user->setLocaleCode($googleUser->getLocale());

        return $user;
    }
}
