<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\TypeEvent;
use App\Entity\Veterinaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use app\Entity\Animal;
use app\Entity\Client;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findEventByAnimal(Animal $animal)
{
    $qb = $this->createQueryBuilder('a');

    $qb
        ->where('a.animal = :animal')
        ->orderBy("a.date", 'ASC')
        ->setParameter('animal', $animal)
    ;

    return $qb->getQuery()->getResult();
}

public function getAllEventByClient(Client $client)
{
    $qb = $this->createQueryBuilder('a')
    ->innerJoin('a.animal', 'animal')
    ->innerJoin('animal.client', 'client')
    ->where('client = :client')
    ->andWhere(' a.date > :now')
    ->setParameter('client', $client )
    ->setParameter('now', new \DateTime());

    return $qb->getQuery()->getResult();
}

public function getAllEventByTypeAndVeterinaire(TypeEvent $typeEvent , Veterinaire $veterinaire)
{
    $qb = $this->createQueryBuilder('a')
    ->innerJoin('a.typeEvent', 'typeEvent')
    ->innerJoin('a.veterinaire', 'veterinaire')
    ->where('typeEvent = :typeEvent')
    ->andWhere('veterinaire = :veterinaire')
    ->setParameter('typeEvent', $typeEvent )
    ->setParameter('veterinaire', $veterinaire );

    return $qb->getQuery()->getResult();
}
public function getAllEventByVeterinaire(Veterinaire $veterinaire)
{
    $qb = $this->createQueryBuilder('a')
    ->innerJoin('a.veterinaire', 'veterinaire')
    ->where('veterinaire = :veterinaire')
    ->setParameter('veterinaire', $veterinaire );

    return $qb->getQuery()->getResult();
}


    /**
     * Récupère tous les events entre deux dates
     * 
     * @return Event[]
     */
    public function getVeterinaireEventsBetween(int $vetoId, \DateTimeInterface $start, \DateTimeInterface $end): array
    {
        return $this->createQueryBuilder('event')
        ->where('event.date BETWEEN :start and :end')
        ->andWhere('event.veterinaire = :vetoId')
        ->setParameter('start', $start->format('Y-m-d H:i:s'))
        ->setParameter('end', $end->format('Y-m-d H:i:s'))
        ->setParameter('vetoId', $vetoId)
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
