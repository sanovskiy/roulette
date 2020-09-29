<?php

namespace App\Strategy\Statistic;

interface StatisticInterface
{
    public function getStatistic($params = []): array;

    public function canProcessed(string $type): bool;
}
