<?php

namespace App\Statistic;

use App\Strategy\Statistic\StatisticInterface;
use InvalidArgumentException;

class StatisticContext
{
    /**
     * @var array
     */
    private $providers = [];

    /**
     * @param StatisticInterface $provider
     */
    public function addProvider(StatisticInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @param string $type
     * @param array $params
     * @return array
     */
    public function handleStat(string $type, array $params = []): array
    {
        /** @var StatisticInterface $provider */
        foreach ($this->providers as $provider) {
            if ($provider->canProcessed($type)) {
                return $provider->getStatistic($params);
            }
        }
        throw new InvalidArgumentException('Statistic type not found.');
    }
}
