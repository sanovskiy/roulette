<?php

namespace App\Service;

use App\Entity\Round;
use App\Repository\RoundsRepository;
use Doctrine\DBAL\LockMode;
use Exception;

class RouletteRotator
{
    const MAX_NUM = 10;

    /** @var string */
    private $state;

    /** @var int */
    private $generatedValue;

    /** @var int[] */
    private $weights = [20, 100, 45, 70, 15, 140, 20, 20, 140, 45];

    /** @var RoundsRepository */
    private $roundsRepository;

    /** @var Round */
    private $round;

    /**
     * RouletteRotator constructor.
     *
     * @param RoundsRepository $roundsRepository
     * @throws Exception
     */
    public function __construct(RoundsRepository $roundsRepository)
    {
        $this->roundsRepository = $roundsRepository;
    }

    /**
     * @throws Exception
     */
    public function init()
    {
        $this->round = $this->roundsRepository->getActiveRound(LockMode::PESSIMISTIC_WRITE);

        if (count($this->weights) != self::MAX_NUM) {
            throw new Exception(
                sprintf('Weight has %s elements, must be %s', count($this->weights), self::MAX_NUM)
            );
        }

        $this->state = str_pad(
            decbin($this->round->getState()),
            self::MAX_NUM,
            '0',
            STR_PAD_LEFT
        );

        $this->initWeights();
    }

    /**
     * @return  string Generated value
     * @throws Exception
     */
    public function rotate(): string
    {
        if ($this->getRound()->getState() + 1 >= pow(2, self::MAX_NUM)) {
            $this->round = $this->roundsRepository->finish($this->round);
            return 'joker';
        }

        $this->generatedValue = $this->getRandomWeightedElement();

        $this->state[$this->generatedValue] = '1';

        $this->getRound()
            ->setUpdatedAt(new \DateTime())
            ->setState(bindec($this->state));

        $this->roundsRepository->save($this->getRound());

        return (string)($this->getGeneratedValue() + 1);
    }

    /**
     * @return Round
     */
    public function getRound(): Round
    {
        return $this->round;
    }

    /**
     * Update weights according to round state.
     */
    private function initWeights()
    {
        array_walk(
            $this->weights,
            function (&$val, $key) {
                $val = $this->state[$key] === '1' ? 0 : $val;
            }
        );
    }

    /**
     * Return random based
     * @return int
     */
    private function getRandomWeightedElement()
    {
        $rand = mt_rand(1, (int)array_sum($this->weights));
        foreach ($this->weights as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
        return 0;
    }

    /**
     * @return int
     */
    private function getGeneratedValue(): int
    {
        return $this->generatedValue;
    }
}
