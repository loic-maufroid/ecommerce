<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return: []
     */
    public function findThreeIdRandom(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT p.id FROM product p ORDER BY RAND() LIMIT 3';
        $result = $conn->query($sql);

        return $result->fetchAll();
    }


    /**
     * @return: Product[]
     */
    public function findThreeRandom(){
        $ids = $this->findThreeIdRandom();
        $tabid = array("id1" => intval($ids[0]["id"]),"id2" => intval($ids[1]["id"]),"id3" => intval($ids[2]["id"]));

        $query = $this->getEntityManager()->createQuery(
            'SELECT p FROM App\Entity\Product p WHERE p.id IN(:id1,:id2,:id3)'
        )
        ->setParameters($tabid);

        return $query->getResult();
    }

     /**
     * @return: []
     */
    public function findOneIdWithHeartRandom(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT p.id FROM product p WHERE p.heart=1 ORDER BY RAND() LIMIT 1';
        $result = $conn->query($sql);

        return $result->fetch();
    }

    /**
     * @return: Product
     */
    public function findOneRandomHeart(){
        $id = ($this->findOneIdWithHeartRandom())["id"];
        
        $query = $this->getEntityManager()->createQuery(
            'SELECT p FROM App\Entity\Product p WHERE p.id=:id'
        )->setParameter('id',$id);

        return $query->getResult();
    }

    /**
     * @return: Product[]
     */
    public function findFourLatest(){

        $query = $this->getEntityManager()->createQuery(
            'SELECT p FROM App\Entity\Product p ORDER BY p.creation_date DESC'
        )->setMaxResults(4);

        return $query->getResult();
    }

    /**
     * @return: Product[]
     */
    public function findByColors(array $colors){
        
        $query = $this->getEntityManager()->createQuery(
            'SELECT p FROM App\Entity\Product p WHERE p.colors=:colors'
        )->setParameter('colors',serialize($colors));

        return $query->getResult();
    }
}
