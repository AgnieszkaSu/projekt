<?php

namespace App\Repository;

use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Type|null find($id, $lockMode = null, $lockVersion = null)
 * @method Type|null findOneBy(array $criteria, array $orderBy = null)
 * @method Type[]    findAll()
 * @method Type[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRepository extends ServiceEntityRepository
{
    /**
     * TypeRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Type::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('type.id', 'DESC');
    }

    /**
     * Query by category.
     *
     * @param int $id Id
     *
     * @return QueryBuilder Query builder
     */
    public function queryByCategory(int $id): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->where('type.category = '.$id)
            ->orderBy('type.id', 'DESC');
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('type');
    }

    /**
     * Save record.
     *
     * @param Type $type Type entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Type $type): void
    {
        $this->_em->persist($type);
        $this->_em->flush($type);
    }

    /**
     * Delete record.
     *
     * @param Type $type Type entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Type $type): void
    {
        $this->_em->remove($type);
        $this->_em->flush($type);
    }
}
