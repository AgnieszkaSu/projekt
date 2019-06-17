<?php

namespace App\Repository;

use App\Entity\Colour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Colour|null find($id, $lockMode = null, $lockVersion = null)
 * @method Colour|null findOneBy(array $criteria, array $orderBy = null)
 * @method Colour[]    findAll()
 * @method Colour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColourRepository extends ServiceEntityRepository
{
    /**
     * ColourRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Colour::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('colour.name', 'ASC');
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('colour');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Colour $colour Colour entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Colour $colour): void
    {
        $this->_em->persist($colour);
        $this->_em->flush($colour);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Colour $colour Colour entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Colour $colour): void
    {
        $this->_em->remove($colour);
        $this->_em->flush($colour);
    }
}
