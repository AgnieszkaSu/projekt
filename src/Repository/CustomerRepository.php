<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    /**
     * CustomerRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Customer::class);
    }


    /**
     * Save record.
     *
     * @param Customer $customer Customer entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Customer $customer): void
    {
        $this->_em->persist($customer);
        $this->_em->flush($customer);
    }

    /**
     * Delete record.
     *
     * @param Customer $customer Customer entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Customer $customer): void
    {
        $this->_em->remove($customer);
        $this->_em->flush($customer);
    }
}
