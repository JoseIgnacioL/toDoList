<?php

namespace App\Repository;

use App\Entity\TareasUsuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Tareas;

/**
 * @extends ServiceEntityRepository<TareasUsuario>
 */
class TareasUsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TareasUsuario::class);
    }

    public function findTareasByUsuario(int $usuarioId)
{
    return $this->createQueryBuilder('t')
        ->innerJoin('t.tareasUsuarios', 'tu')  // Asumiendo que 'tareasUsuarios' es la relación correcta
        ->where('tu.idUsuario = :usuarioId')  // Asegúrate de que 'idUsuario' sea la clave de la relación
        ->setParameter('usuarioId', $usuarioId)
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return TareasUsuario[] Returns an array of TareasUsuario objects
    //     */

 // src/Repository/TareasUsuarioRepository.php



 



    //    public function findOneBySomeField($value): ?TareasUsuario
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
