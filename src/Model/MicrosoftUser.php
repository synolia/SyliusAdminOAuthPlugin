<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Model;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

final class MicrosoftUser implements ResourceOwnerInterface
{
    private string|int $id;
    private string $email;
    private string $emailCanonical;
    private string $username;
    private string $firstname;
    private string $lastname;
    private bool $isEnabled;
    private \DateTimeImmutable $createdAt;
    private string $localeCode;
    private array $response;

    /**
     * Get user data as an array.
     *
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return $this->response;
    }

    /**
     * @return int|string
     */
    public function getId(): int|string
    {
        return $this->response['sub'];
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmailCanonical(): string
    {
        return $this->emailCanonical;
    }

    /**
     * @param string $emailCanonical
     */
    public function setEmailCanonical(string $emailCanonical): void
    {
        $this->emailCanonical = $emailCanonical;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     */
    public function setEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getLocaleCode(): string
    {
        return $this->localeCode;
    }

    /**
     * @param string $localeCode
     */
    public function setLocaleCode(string $localeCode): void
    {
        $this->localeCode = $localeCode;
    }
}
