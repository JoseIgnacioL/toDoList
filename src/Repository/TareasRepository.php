<?php

namespace App\Repository;

use App\Entity\Tareas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tareas>
 */
class TareasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tareas::class);
    }

    public function buscarTarea($query,$idUsuario)
    {
        $qb = $this->createQueryBuilder('t')
        ->innerJoin('t.tareasUsuarios', 'tu') 
        ->innerJoin('tu.idUsuario', 'u')
        ->where('u.id = :idUsuario')
        ->setParameter('idUsuario', $idUsuario);

    if ($query) {
        $qb->andWhere('t.nombre LIKE :query')
            ->setParameter('query', '%' . $query . '%');
    }

        return $qb->getQuery()->getResult();
    }



    //    /**
    //     * @return Tareas[] Returns an array of Tareas objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tareas
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}