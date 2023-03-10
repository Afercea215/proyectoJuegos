<?php

namespace App\Repository;

use App\Entity\Evento;
use App\Entity\Juego;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evento>
 *
 * @method Evento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evento[]    findAll()
 * @method Evento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evento::class);
    }

    public function save(Evento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function setJuegos(Array $juegos, int $idEvento)
    {
        try {
            //
            dd($idEvento);
            $sql='delete from xocasycia.juego_evento where evento_id='.$idEvento;
            $stmt = $this->parent()->getConnection()->prepare($sql)->execute();

            
            $sql='insert into juego_evento("juego_id","evento_id") values ';
            for ($i=0; $i <sizeof($juegos) ; $i++) { 
                if ($i<sizeof($juegos)) {
                    $sql.='('.$juegos[$i]->getId().','.$idEvento.'),';
                } else {
                    $sql.='('.$juegos[$i]->getId().','.$idEvento.')';
                }
                
            }
            $stmt = $this->parent()->getConnection()->prepare($sql);
            $result = $stmt->execute();

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function getEventosUser(User $user = null)
    {
        $qb = $this->createQueryBuilder('r')
            ->join('r.participas','p')
            ->where('p.user ='.$user->getId());

        return $qb->getQuery()->execute();
    }

//    /**
//     * @return Evento[] Returns an array of Evento objects
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

//    public function findOneBySomeField($value): ?Evento
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
