<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

   public function getByStyle()
   {
       // - Récuperer le query builder (car c'est le query builder
       //qui permet de faire la requête SQL

       $queryBuilder = $this->createQueryBuilder('b');

       // - Construire la requête façon SQL, mais en PHP
       // - Traduire la requête e, véritable requête SQL

       $query = $queryBuilder->select('b')
            ->where('b.style = :genre')
            ->setParameter('genre','Roman')
            ->getQuery();

       $books = $query->getArrayResult();
       return $books;

       // - Executer la requete sql en base de données pour récuprérer les bons livres



   }
}
