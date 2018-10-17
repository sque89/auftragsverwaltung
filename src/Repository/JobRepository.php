<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Job::class);
    }

    public function findLatest() {
        return $this->createQueryBuilder('j')
                        ->orderBy('j.id', 'DESC')
                        ->setMaxResults(1)
                        ->getQuery()
                        ->getOneOrNullResult();
    }

    public function findByTimespan(\DateTime $from, \DateTime $to) {
        return $this->createQueryBuilder('j')
                        ->orderBy('j.id', 'DESC')
                        ->where('j.dateIncoming >= :from')
                        ->andWhere('j.dateIncoming <= :to')
                        ->setParameter('from', $from)
                        ->setParameter('to', $to)
                        ->getQuery()
                        ->getResult();
    }

}
