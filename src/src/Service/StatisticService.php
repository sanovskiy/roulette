<?php

namespace App\Service;

use App\Repository\LogsRepository;
use App\Repository\RoundsRepository;

class StatisticService
{
    /** @var RoundsRepository */
    protected $logsRepository;

    public function __construct(LogsRepository $logsRepository)
    {
        $this->logsRepository = $logsRepository;
    }

    /**
     * @param array $params
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getCommonStatistic(array $params = []): array
    {
        return $this->logsRepository->commonRoundStat($params);
    }

    /**
     * @param array $params
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getActivityStatistic(array $params = []): array
    {
        return $this->logsRepository->topActiveUsersStat($params);
    }
}
