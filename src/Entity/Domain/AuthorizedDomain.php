<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Entity\Domain;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Synolia\SyliusAdminOauthPlugin\Repository\AuthorizedDomainRepository;

#[ORM\Table(name: 'synolia_authorized_domain')]
#[ORM\Entity(repositoryClass: AuthorizedDomainRepository::class)]
class AuthorizedDomain implements ResourceInterface
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;

    #[ORM\Column(type: 'boolean')]
    private bool $isEnabled = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return AuthorizedDomain
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return AuthorizedDomain
     */
    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}
