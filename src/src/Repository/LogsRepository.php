<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    /**
     * @param $round
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($round): void
    {
        $this->getEntityManager()->persist($round);
        $this->getEntityManager()->flush();
    }


    /**
     * Какое количество людей участвовало в рулетке (таблица, где в строке первый элемент
     * это номер раунда рулетки, второй элемент количество пользователей.
     *
     * @param array $params
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function commonRoundStat(array $params = [])
    {
        $sql = 'select
                 l.round_id,
                 count(distinct l.user_id)
                from logs l
                group by l.round_id';

        return $this->getEntityManager()
            ->getConnection()
            ->query($sql)
            ->fetchAll();
    }

    /**
     * Список самых активных пользователей.
     *
     * @param array $params
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function topActiveUsersStat(array $params = [])
    {
        $sql = 'select 
                   l.user_id,
                   count(distinct l.round_id) as totalRounds,
                   stat.num as avgRotatesPerRound
            from logs l,
                 (select s.user_id, avg(s.num) as num
                  from (select l.user_id,
                               l.round_id,
                               count(l.round_id) as num
                        from logs l
                        group by l.user_id, l.round_id) as s
                  group by s.user_id) as stat
            where stat.user_id = l.user_id
            group by l.user_id, stat.num';

        return $this->getEntityManager()
            ->getConnection()
            ->query($sql)
            ->fetchAll();
    }
}
