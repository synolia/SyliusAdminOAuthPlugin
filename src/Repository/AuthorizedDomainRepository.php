<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Synolia\SyliusAdminOauthPlugin\Entity\Domain\AuthorizedDomain;

/**
 * @method AuthorizedDomain|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthorizedDomain|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthorizedDomain[] findAll()
 * @method AuthorizedDomain[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class AuthorizedDomainRepository extends EntityRepository
{
    public function save(AuthorizedDomain $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
