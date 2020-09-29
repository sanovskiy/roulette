<?php

namespace App\Strategy\Statistic;


class Common extends AbstractTypeResolver
{
    const TYPE = 'common';

    /**
     * @param array $params
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getStatistic($params = []): array
    {
        return $this->statisticService->getCommonStatistic($params);
    }
}
