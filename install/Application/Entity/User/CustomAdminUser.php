<?php

declare(strict_types=1);

//namespace Synolia\SyliusAdminOauthPlugin\Entity\User;
namespace App\Entity\User;


use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_admin_user")
 */
#[ORM\Entity]
#[ORM\Table(name: "sylius_admin_user")]
class CustomAdminUser extends BaseAdminUser
{
    #[ORM\Column(type: 'string')]
    private ?string $googleId = null;
    #[ORM\Column(type: 'string')]
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
     * @return $this
     */
    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHostedDomain(): ?string
    {
        return $this->hostedDomain;
    }


    public function setHostedDomain(?string $hostedDomain): self
    {
        $this->hostedDomain = $hostedDomain;

        return $this;
    }
}
