<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Factory;

use Synolia\SyliusAdminOauthPlugin\Model\OauthClient;

final class OauthClientFactory
{
    public static function create(
        int|string $id,
        string $name,
        string $checkPathName,
        string $providerName,
        string $authenticationFailureMessage,
    ): OauthClient {
        $oauthClient = new OauthClient();

        $oauthClient->setId($id);
        $oauthClient->setName($name);
        $oauthClient->setCheckPathName($checkPathName);
        $oauthClient->setProviderName($providerName);
        $oauthClient->setAuthenticationFailureMessage($authenticationFailureMessage);

        return $oauthClient;
    }

    public static function createGoogleOauthClient(string $googleClientId): OauthClient
    {
        return self::create(
            $googleClientId,
            'google_admin',
            'connect_admin_google_check',
            'google',
            'synolia.sylius_admin_oauth.google_authentication.authentication_failure'
        );
    }

    public static function createMicrosoftOauthClient(string $microsoftClientId): OauthClient
    {
        return self::create(
            $microsoftClientId,
            'azure_admin',
            'connect_admin_microsoft_check',
            'microsoft',
            'synolia.sylius_admin_oauth.microsoft_authentication.authentication_failure'
        );
    }
}
