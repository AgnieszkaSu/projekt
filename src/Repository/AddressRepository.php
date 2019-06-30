<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    /**
     * AddressRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Address::class);
    }

    /**
     * Save record.
     *
     * @param Address $address Address entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Address $address): void
    {
        $this->_em->persist($address);
        $this->_em->flush($address);
    }

    /**
     * Delete record.
     *
     * @param Address $address Address entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Address $address): void
    {
        $this->_em->remove($address);
        $this->_em->flush($address);
    }
}
