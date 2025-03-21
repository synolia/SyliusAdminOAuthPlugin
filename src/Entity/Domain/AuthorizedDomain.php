<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Entity\Domain;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Synolia\SyliusAdminOauthPlugin\Repository\AuthorizedDomainRepository;

#[ORM\Table(name: 'synolia_authorized_domain')]
#[ORM\Entity(repositoryClass: AuthorizedDomainRepository::class)]
class AuthorizedDomain implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'is_enabled', type: Types::BOOLEAN)]
    private bool $isEnabled = false;

    public function getId(): ?int
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}
