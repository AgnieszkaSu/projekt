<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Order $order Order entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Order $order): void
    {
        $this->_em->persist($order);
        $this->_em->flush($order);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Order $order Order entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Order $order): void
    {
        $this->_em->remove($order);
        $this->_em->flush($order);
    }
}
