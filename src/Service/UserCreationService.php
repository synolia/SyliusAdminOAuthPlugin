<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Service;

use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use Sylius\Component\Core\Model\AdminUser;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Synolia\SyliusAdminOauthPlugin\Factory\AdminUserFactory;

final class UserCreationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RepositoryInterface $adminUserRepository
    ) {
    }

    public function createByGoogleAccount(GoogleUser $googleUser): AdminUser
    {
        /** @var AdminUser $existingUser */
        $existingUser = $this->adminUserRepository->findOneBy(['googleId' => $googleUser->getId()]);
        // 1) have they logged in with Google before? Easy!

        if (null !== $existingUser) {
            return $existingUser;
        }
        // 2) do we have a matching user by email?
        /** @var AdminUser $user */
        $user = $this->adminUserRepository->findOneBy(['email' => $googleUser->getEmail()]);
        // 3) register google user
        if (null === $user) {
            /** @var AdminUser $user */
            $user = AdminUserFactory::createByGoogleAccount($googleUser);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user;
    }
}
