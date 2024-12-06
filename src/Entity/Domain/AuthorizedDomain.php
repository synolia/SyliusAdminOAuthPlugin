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
    public const GRAPH = 'authorized_domain_graph';

    public const TRANSITION_ENABLE = 'enable';
    public const STATE_NOT_ENABLED = 'new';
    public const STATE_ENABLED = 'enabled';

    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;

    #[ORM\Column(name: 'is_enabled', type: 'boolean')]
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
    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }
}
