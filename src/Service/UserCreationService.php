<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Service;

use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use Sylius\Component\Core\Model\AdminUser;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Synolia\SyliusAdminOauthPlugin\Factory\AdminUserFactory;
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

final class UserCreationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RepositoryInterface $adminUserRepository,
        private LocaleContextInterface $localeContext
    ) {
    }

    public function create(GoogleUser|AzureResourceOwner $user): AdminUser
    {
        if ($user instanceof GoogleUser) {
            /** @var AdminUser $existingUser */
            $existingUser = $this->adminUserRepository->findOneBy(['googleId' => $user->getId()]);
        } else {
            /** @var AdminUser $existingUser */
            $existingUser = $this->adminUserRepository->findOneBy(['microsoftId' => $user->getId()]);
        }
        // 1) have they logged in with Google before? Easy!
        if (null !== $existingUser) {
            return $existingUser;
        }
        // 2) do we have a matching user by email?
        if ($user instanceof GoogleUser) {
            /** @var AdminUser $userToReturn */
            $userToReturn = $this->adminUserRepository->findOneBy(['email' => $user->getEmail()]);
        } else {
            /** @var AdminUser $userToReturn */
            $userToReturn = $this->adminUserRepository->findOneBy(['email' => $user->getUpn()]);
        }
        // 3) register google user
        if (null === $userToReturn) {
            if ($user instanceof GoogleUser) {
                $userToReturn = AdminUserFactory::createByGoogleAccount($user);
            } else {
                $userToReturn = AdminUserFactory::createByMicrosoftAccount($user, $this->localeContext->getLocaleCode());
            }

            $this->entityManager->persist($userToReturn);
            $this->entityManager->flush();
        }

        /** @var AdminUser */
        return $userToReturn;
    }
}
