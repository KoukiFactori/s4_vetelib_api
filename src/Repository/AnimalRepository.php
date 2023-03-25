<?php

namespace App\Repository;

use App\Entity\Animal;
use App\Entity\Veterinaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animal>
 *
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function save(Animal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Animal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Client client dont on souhaite avoir les animaux
     *
     * @return Animal[] return an array de tout les animaux d'un client
     */
    public function getAllAnimalsByClient(string $clientId): array
    {
        $qb = $this->createQueryBuilder('a')
        ->innerJoin('a.client', 'c')
        ->where('c.id = :clientId')
        ->setParameter('clientId', $clientId)
        ->getQuery();

        return $qb->execute();
    }

    public function getAllAnimalByVeterinaire(Veterinaire $veterinaire)
    {
        $qb = $this->createQueryBuilder('a')
        ->innerJoin(
            'App\Entity\Event',
            'e',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'e.animal = a.id');

        return $qb->getQuery()->execute();
    }

//    /**
//     * @return Animal[] Returns an array of Animal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Animal
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
