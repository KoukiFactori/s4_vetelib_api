<?php

namespace App\Repository;

use App\Entity\Veterinaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Veterinaire>
 *
 * @method Veterinaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Veterinaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Veterinaire[]    findAll()
 * @method Veterinaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VeterinaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Veterinaire::class);
    }

    public function save(Veterinaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Veterinaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getEventsOn(\DateTimeInterface $date): array
    {
        $events = $this->createQueryBuilder('veto')
            ->addSelect('event')
            ->leftJoin('veto.events', 'event')
            ->where('event.date >= :start')
            ->andWhere('event.date <= :end')
            ->orderBy('event.date')
            ->setParameter('start', $date->format('Y-m-d 00:00:00'))
            ->setParameter('end', $date->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getResult()
        ;

        return $events;
    }

    public function findByStartingTimeAndVeterinaire(\DateTimeInterface $date, Veterinaire $veterinaire): array
    {
        $events = $this->createQueryBuilder('veto')
            ->addSelect('event')
            ->leftJoin('veto.events', 'event')
            ->where('event.date = :start')
            ->andWhere('veto.id = :veto')
            ->orderBy('event.date')
            ->setParameter('start', $date->format('Y-m-d H:i:s'))
            ->setParameter('veto', $veterinaire->getId())
            ->getQuery()
            ->getResult();

        return $events;
    }
    public function getAvailableSlots(Veterinaire $veto): array
    {
        $interval = new \DateInterval('PT30M'); // intervalle de 30 minutes
        $slots = [];

        // pour chaque vétérinaire
      
            $events = $veto->getEvents(); // récupère les rendez-vous pour le vétérinaire en cours
            $start = new \DateTime('8:00'); // début à 8h
            $end = new \DateTime('18:30'); // fin à 18h30
            $slotStart = clone $start; // on utilise une copie de l'heure de début pour parcourir les créneaux
            $slotEnd = clone $slotStart;
            $slotEnd->add($interval); // l'heure de fin du créneau est l'heure de début du créneau + 30 minutes
            $slots[$veto->getId()] = []; // initialise le tableau de créneaux pour le vétérinaire en cours

            // tant que l'heure de début du créneau est avant l'heure de fin
            while ($slotStart < $end) {
                $available = true; // on suppose que le créneau est disponible
                // vérifie s'il y a un rendez-vous à la même heure que le créneau
                foreach ($events as $event) {
                    if ($event->getDate() >= $slotStart && $event->getDate() < $slotEnd) {
                        $available = false; // le créneau n'est pas disponible s'il y a un rendez-vous à la même heure
                        break;
                    }
                }

                if ($available) {
                    // si le créneau est disponible, on l'ajoute au tableau de créneaux
                    $slots[$veto->getId()][] = [
                        'start' => $slotStart->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                    ];
                }

                // passe au créneau suivant
                $slotStart->add($interval);
                $slotEnd->add($interval);
            }
        return $slots;
    }

    public function retrieveAllClientRelatedToVeterinaire(int $vetId): array
    {
        $clients = $this->createQueryBuilder('veto')
            ->select('client')
            ->where('veto.id = :id')
            ->innerJoin('veto.events', 'event')
            ->innerJoin('event.animal', 'animal')
            ->innerJoin('animal.client', 'client')
            ->setParameter('id', $vetId)
            ->getQuery()->execute();

        return $clients;
    }

//    /**
//     * @return Veterinaire[] Returns an array of Veterinaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Veterinaire
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
