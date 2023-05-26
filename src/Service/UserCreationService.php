<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Service;

use App\Entity\User\AdminUser;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\UserRepository;
use Synolia\SyliusAdminOauthPlugin\Factory\AdminUserFactory;

final class UserCreationService
{
    /**
     * @param EntityManagerInterface $entityManager
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function createByGoogleAccount(GoogleUser $googleUser): AdminUser
    {
        /** @var UserRepository $userRepo */
        $userRepo = $this->entityManager->getRepository(AdminUser::class);
        /** @var AdminUser $existingUser */
        $existingUser = $userRepo->findOneBy(['googleId' => $googleUser->getId()]);
        // 1) have they logged in with Google before? Easy!
        if (null !== $existingUser->getCreatedAt()) {
            return $existingUser;
        }
        // 2) do we have a matching user by email?
        $user = $this->entityManager->getRepository(AdminUser::class)->findOneBy(['email' => $googleUser->getEmail()]);
        // 3) register google user
        if (null === $user) {
            $user = AdminUserFactory::createByGoogleAccount($googleUser);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user;
    }
}
