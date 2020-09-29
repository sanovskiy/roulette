<?php

namespace App\Service;

use App\Entity\Log;
use App\Entity\Round;
use App\Entity\User;
use App\Repository\LogsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class LogService
{
    /** @var LogsRepository */
    protected $logsRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(LogsRepository $logsRepository, EntityManagerInterface $em)
    {
        $this->logsRepository = $logsRepository;
        $this->em = $em;
    }


    /**
     * @param User $user
     * @param Round $round
     * @param array $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user, Round $round, array $data): void
    {
        $log = new Log();
        $log->setUser($user)
            ->setRound($round)
            ->setCreatedAt(new DateTime())
            ->setData($data);
        $this->logsRepository->save($log);
    }
}
