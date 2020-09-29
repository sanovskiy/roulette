<?php

namespace App\Strategy\Statistic;


class Activity extends AbstractTypeResolver
{
    const TYPE = 'activity';

    /**
     * @param array $params
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getStatistic($params = []): array
    {
        return $this->statisticService->getActivityStatistic();
    }
}
