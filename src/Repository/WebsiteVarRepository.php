<?php

namespace App\Repository;

use App\Entity\WebsiteVar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebsiteVar|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsiteVar|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsiteVar[]    findAll()
 * @method WebsiteVar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteVarRepository extends ServiceEntityRepository {
	public function __construct(ManagerRegistry $registry) {
		parent::__construct($registry, WebsiteVar::class);
	}

	/**
	 * @param $component
	 *
	 * @return WebsiteVar[] Returns an array of WebsiteVar objects
	 */
	public function findVarsByComponent($component) {
		return $this->createQueryBuilder('w')
			->andWhere('w.Component = :val')
			->setParameter('val', $component)
			->orderBy('w.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult();
	}
}


    /*
    public function findOneBySomeField($value): ?WebsiteVar
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
