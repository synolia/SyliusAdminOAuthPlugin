<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Model;

final class OauthClient
{
    private int|string $id;

    private string $name;

    private string $checkPathName;

    private string $providerName;

    private string $authenticationFailureMessage;

    public function getId(): int|string
    {
        return $this->id;
    }

    public function setId(int|string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCheckPathName(): string
    {
        return $this->checkPathName;
    }

    public function setCheckPathName(string $checkPathName): void
    {
        $this->checkPathName = $checkPathName;
    }

    public function getProviderName(): string
    {
        return $this->providerName;
    }

    public function setProviderName(string $providerName): void
    {
        $this->providerName = $providerName;
    }

    public function getAuthenticationFailureMessage(): string
    {
        return $this->authenticationFailureMessage;
    }

    public function setAuthenticationFailureMessage(string $authenticationFailureMessage): void
    {
        $this->authenticationFailureMessage = $authenticationFailureMessage;
    }
}
