<?php

namespace App\Strategy\Statistic;


use App\Service\StatisticService;

abstract class AbstractTypeResolver implements StatisticInterface
{
    const TYPE = '';

    /**
     * @var StatisticService
     */
    protected $statisticService;

    public function __construct(StatisticService $statisticService)
    {
        $this->statisticService = $statisticService;
    }

    public function canProcessed(string $type): bool
    {
        return static::TYPE === $type;
    }
}
