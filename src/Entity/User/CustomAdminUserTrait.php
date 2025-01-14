<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Entity\User;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;

trait CustomAdminUserTrait
{
    #[Column(name: 'google_id', type: Types::STRING, nullable: true)]
    private ?string $googleId = null;

    #[Column(name: 'microsoft_id', type: Types::STRING, nullable: true)]
    private ?string $microsoftId = null;

    #[Column(name: 'hosted_domain', type: Types::STRING, nullable: true)]
    private ?string $hostedDomain = null;

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): void
    {
        $this->googleId = $googleId;
    }

    public function getHostedDomain(): ?string
    {
        return $this->hostedDomain;
    }

    public function setHostedDomain(?string $hostedDomain): void
    {
        $this->hostedDomain = $hostedDomain;
    }

    public function getMicrosoftId(): ?string
    {
        return $this->microsoftId;
    }

    public function setMicrosoftId(?string $microsoftId): void
    {
        $this->microsoftId = $microsoftId;
    }
}
