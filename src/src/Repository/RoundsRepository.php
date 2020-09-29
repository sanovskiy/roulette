<?php

namespace App\Repository;

use App\Entity\Round;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Round|null find($id, $lockMode = null, $lockVersion = null)
 * @method Round|null findOneBy(array $criteria, array $orderBy = null)
 * @method Round[]    findAll()
 * @method Round[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoundsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Round::class);
    }

    /**
     * @param int $lockMode
     * @return Round|null
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getActiveRound(int $lockMode = 0)
    {
        $round = $this->findOneActive($lockMode);
        if (!$round || $round->getFinished()) {
            $round = new Round();
            $round->setFinished(false)
                ->setUpdatedAt(new \DateTime())
                ->setCreatedAt(new \DateTime())
                ->setState(0);
            $this->save($round);
            return $this->findOneActive($lockMode);
        }
        return $round;
    }

    /**
     * @param Round $round
     * @param int $state
     * @return Round
     * @throws Exception
     */
    public function rotate(Round $round, int $state): Round
    {
        if (!$round) {
            throw new Exception('Round not found');
        }
        if ($round->getFinished()) {
            throw new Exception('Round already finished');
        }

        $round->setUpdatedAt(new \DateTime())
            ->setState($state);

        $this->save($round);

        return $round;
    }

    public function finish(Round $round): Round
    {
        $round->setUpdatedAt(new \DateTime())
            ->setFinished(true);
        $this->save($round);
        return $round;
    }

    /**
     * @param int $lockLevel
     * @return Round|null
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function findOneActive(int $lockLevel = 0): ?Round
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.finished = false')
            ->setMaxResults(1)
            ->getQuery();
        if ($lockLevel) {
            $query->setLockMode($lockLevel);
        }
        $result = $query->getResult();
        return empty($result) ? null : $result[0];
    }

    /**
     * @param $round
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($round)
    {
        $this->getEntityManager()->persist($round);
        $this->getEntityManager()->flush();
    }

}
