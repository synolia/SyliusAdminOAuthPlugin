<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Service;

use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use Sylius\Component\Core\Model\AdminUser;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Synolia\SyliusAdminOauthPlugin\Security\Resolver\DomainInformationsResolver;
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

final readonly class UserCreationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RepositoryInterface $adminUserRepository,
        private DomainInformationsResolver $domainInformationsResolver,
    ) {
    }

    public function create(AzureResourceOwner|GoogleUser $user): AdminUser
    {
        $domainInformation = $this->domainInformationsResolver->getDomainInformations($user);
        $existingUser = $this->getExistingUser($domainInformation, $user);

        // 1) have they logged in before ?
        if ($existingUser instanceof AdminUser) {
            return $existingUser;
        }

        // 2) do we have a matching user by email?
        $userToReturn = $this->getEmailMatchingUser($domainInformation, $user);

        // 3) register user if its email doesn't exists
        if (!$userToReturn instanceof AdminUser) {
            /** @var AdminUser */
            return $this->registerUser($domainInformation, $user);
        }

        return $userToReturn;
    }

    /**
     * @param array<string, array<string, AdminUser|string|null>> $domainInformation
     */
    private function getExistingUser(array $domainInformation, AzureResourceOwner|GoogleUser $user): ?AdminUser
    {
        foreach ($domainInformation as $class => $properties) {
            if ($user instanceof $class && \is_string($properties['propertyName'])) {
                /** @var AdminUser|null */
                return $this->adminUserRepository->findOneBy([$properties['propertyName'] => $user->getId()]);
            }
        }

        return null;
    }

    /**
     * @param array<string, array<string, AdminUser|string|null>> $domainInformation
     */
    private function getEmailMatchingUser(array $domainInformation, AzureResourceOwner|GoogleUser $user): ?AdminUser
    {
        foreach ($domainInformation as $class => $properties) {
            if ($user instanceof $class) {
                /** @var AdminUser|null */
                return $this->adminUserRepository->findOneBy(['email' => $properties['userEmail']]);
            }
        }

        return null;
    }

    /**
     * @param array<string, array<string, AdminUser|string|null>> $domainInformation
     */
    private function registerUser(array $domainInformation, AzureResourceOwner|GoogleUser $user): ?AdminUser
    {
        $userToReturn = null;

        foreach ($domainInformation as $class => $properties) {
            if ($user instanceof $class) {
                /** @var AdminUser $userToReturn */
                $userToReturn = $properties['newUser'];
                $this->entityManager->persist($userToReturn);
                $this->entityManager->flush();
            }
        }

        return $userToReturn;
    }
}
