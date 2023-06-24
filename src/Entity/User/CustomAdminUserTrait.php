<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Entity\User;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait CustomAdminUserTrait
{
    /**
     * @ORM\Column(name="google_id", type="string", nullable=true)
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $googleId = null;

    /**
     * @ORM\Column(name="hosted_domain", type="string", nullable=true)
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
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
}
