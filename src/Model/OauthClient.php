<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Model;

final class OauthClient
{
    private string|int $id;

    private string $name;

    private string $checkPathName;

    private string $providerName;

    private string $authenticationFailureMessage;

    /**
     * @return int|string
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     */
    public function setId(int|string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCheckPathName(): string
    {
        return $this->checkPathName;
    }

    /**
     * @param string $checkPathName
     */
    public function setCheckPathName(string $checkPathName): void
    {
        $this->checkPathName = $checkPathName;
    }

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return $this->providerName;
    }

    /**
     * @param string $providerName
     */
    public function setProviderName(string $providerName): void
    {
        $this->providerName = $providerName;
    }

    /**
     * @return string
     */
    public function getAuthenticationFailureMessage(): string
    {
        return $this->authenticationFailureMessage;
    }

    /**
     * @param string $authenticationFailureMessage
     */
    public function setAuthenticationFailureMessage(string $authenticationFailureMessage): void
    {
        $this->authenticationFailureMessage = $authenticationFailureMessage;
    }
}
