<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

        public function SortiesByCampus(Campus $campus)
        {
            $queryBuilder = $this->createQueryBuilder('s');
            $queryBuilder->join('s.etat', 'e')
                ->addSelect('e');
            $queryBuilder->leftJoin('s.participants', 'p')
                ->addSelect('p');
            $queryBuilder->andWhere('s.campus = :campus');
            $queryBuilder->setParameter(':campus', $campus->getId());
            $queryBuilder->orderBy('s.dateHeureDebut', 'ASC');
            $query = $queryBuilder->getQuery();
            return $query->getResult();
        }

        public function FiltreSorties($data, User $userConnecte)
        {
            $queryBuilder = $this->createQueryBuilder('s');
            $queryBuilder->join('s.etat', 'e')
                ->addSelect('e');
            $queryBuilder->leftJoin('s.participants', 'p')
                ->addSelect('p');
            $queryBuilder->andWhere($queryBuilder->expr()->notIn('e.libelle', ':historic'));
            $queryBuilder->setParameter(':historic', 'Historisée');
            if ('campus'){
                $queryBuilder->andWhere('s.campus = :campus');
                $queryBuilder->setParameter(':campus', 'campus');
            }
            if('nom'){
                $queryBuilder->andWhere('s.nom LIKE :nameSortie');
                $queryBuilder->setParameter(':nameSortie', 'nom' );
            }
            if('dateDebut'){
                $queryBuilder->andWhere('s.dateHeureDebut >= :dateForm');
                $queryBuilder->setParameter(':dateForm', 'dateDebut' );
            }
            if ('dateFin'){
                $queryBuilder->andWhere('s.dateHeureDebut <= :dateTo');
                $queryBuilder->setParameter(':dateTo', 'dateFin');
            }
            if('sortiesOrganises'){
                $queryBuilder->andWhere('s.organisateur = :organisator');
                $queryBuilder->setParameter(':organisator', $userConnecte);
            }
            if('sortiesInscrites'){
                $queryBuilder->andWhere(':userInscrit member of s.participants');
                $queryBuilder->setParameter(':userInscrit', $userConnecte);
            }
            if ('sortiesNonInscrites'){
                $queryBuilder->andWhere(':userNonInscrit not member of s.participants');
                $queryBuilder->setParameter(':userNonInscrit', $userConnecte);
            }
            if ('sortiesPassees'){
                $queryBuilder->andWhere('e.libelle = :etat');
                $queryBuilder->setParameter(':etat', 'Passée');
            }

            $queryBuilder->orderBy('s.dateHeureDebut', 'ASC');
            $query = $queryBuilder->getQuery();
            return $query->getResult();
        }

        public function AllSortiesFromUserCampus(User $userConnecte)
        {
            $queryBuilder = $this->createQueryBuilder('s');
            $queryBuilder->join('s.etat', 'e')
                ->addSelect('e');
            $queryBuilder->leftJoin('s.participants', 'p')
                ->addSelect('p');
            $queryBuilder->andWhere('s.campus = '.$userConnecte->getCampus()->getId());
            $queryBuilder->orderBy('s.dateHeureDebut', 'ASC');
            $query = $queryBuilder->getQuery();
            return $query->getResult();
        }
}
