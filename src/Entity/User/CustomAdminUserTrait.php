<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Entity\User;

use Doctrine\ORM\Mapping\Column;

trait CustomAdminUserTrait
{
    /**
     * @Column(name="google_id", type="string", nullable=true)
     */
    #[Column(name: 'google_id', type: 'string', nullable: true)]
    private ?string $googleId = null;

    /**
     * @Column(name="microsoft_id", type="string", nullable=true)
     */
    #[Column(name: 'microsoft_id', type: 'string', nullable: true)]
    private ?string $microsoftId = null;

    /**
     * @Column(name="hosted_domain", type="string", nullable=true)
     */
    #[Column(name: 'hosted_domain', type: 'string', nullable: true)]
    private ?string $hostedDomain = null;

    /**
     * @return string|null
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * @param string|null $googleId
     */
    public function setGoogleId(?string $googleId): void
    {
        $this->googleId = $googleId;
    }

    /**
     * @return string|null
     */
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
