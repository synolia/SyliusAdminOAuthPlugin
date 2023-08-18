<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Factory;

use Synolia\SyliusAdminOauthPlugin\Model\MicrosoftUser;

final class MicrosoftUserFactory
{
    /**
     * @param array<string,string> $microsoftUser
     */
    public static function create(array $microsoftUser): MicrosoftUser
    {
        $microsoftUserModel = new MicrosoftUser();
        $microsoftUserModel->setId($microsoftUser['sub']);
        $microsoftUserModel->setEmail($microsoftUser['unique_name']);
        $microsoftUserModel->setEmailCanonical($microsoftUser['unique_name']);
        $microsoftUserModel->setUsername($microsoftUser['upn']);
        $microsoftUserModel->setFirstname($microsoftUser['given_name']);
        $microsoftUserModel->setLastname($microsoftUser['family_name']);
        $microsoftUserModel->setEnabled(true);
        $microsoftUserModel->setCreatedAt(new \DateTimeImmutable('now'));

        return $microsoftUserModel;
    }
}
