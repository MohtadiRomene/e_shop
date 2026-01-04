<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * Recherche de produits par nom
     * @return Produit[]
     */
    public function search(string $query, ?string $type = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.nomProduit LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.nomProduit', 'ASC');

        if ($type === 'vetement') {
            $qb->andWhere('p INSTANCE OF App\Entity\Vetement');
        } elseif ($type === 'chaussure') {
            $qb->andWhere('p INSTANCE OF App\Entity\Chaussure');
        } elseif ($type === 'accessoire') {
            $qb->andWhere('p INSTANCE OF App\Entity\Accessoire');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Recherche avancée avec filtres de prix
     * @return Produit[]
     */
    public function searchAdvanced(string $query, ?float $minPrice = null, ?float $maxPrice = null, ?string $type = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.nomProduit LIKE :query')
            ->setParameter('query', '%' . $query . '%');

        if ($minPrice !== null) {
            $qb->andWhere('p.prix >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('p.prix <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        if ($type === 'vetement') {
            $qb->andWhere('p INSTANCE OF App\Entity\Vetement');
        } elseif ($type === 'chaussure') {
            $qb->andWhere('p INSTANCE OF App\Entity\Chaussure');
        } elseif ($type === 'accessoire') {
            $qb->andWhere('p INSTANCE OF App\Entity\Accessoire');
        }

        return $qb->orderBy('p.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
