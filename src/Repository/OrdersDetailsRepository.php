<?php

namespace App\Repository;

use App\Entity\OrdersDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrdersDetails>
 *
 * @method OrdersDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdersDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdersDetails[]    findAll()
 * @method OrdersDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdersDetails::class);
    }
    public function findTopSoldBooks(): array
    {
        $startDate = new \DateTimeImmutable('2024-05-01');
        $endDate = new \DateTimeImmutable('2024-05-31');
        return $this->createQueryBuilder('od')
            ->select('IDENTITY(od.livres) as id, l.titre, SUM(od.quantity) as quantity')
            ->join('od.livres', 'l')
            ->join('od.orders', 'o')
            
            ->groupBy('od.livres')
            ->orderBy('quantity', 'DESC')
            ->where('o.created_at BETWEEN :start_date AND :end_date')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            
            ->setMaxResults(5) // Change this number according to your requirement
            ->getQuery()
            ->getResult();
    
    }
   

//    /**
//     * @return OrdersDetails[] Returns an array of OrdersDetails objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrdersDetails
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
