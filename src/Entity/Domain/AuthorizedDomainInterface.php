<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Entity\Domain;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface AuthorizedDomainInterface extends ResourceInterface, ToggleableInterface
{
    public function getId(): int;

    public function setId(int $id): self;

    public function getName(): string;

    public function setName(string $name): self;
}
