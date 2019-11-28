<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }


    public function getByBio($word)
    {

        $queryBuilder = $this->createQueryBuilder('a');
        $query = $queryBuilder->select('a')
            ->where('a.biography LIKE :word')
            ->setParameter('word', '%' . $word . '%')
            ->getQuery();
        $authors = $query->getResult();
        return $authors;
    }
}
