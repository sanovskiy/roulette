<?php

namespace App\Service;

use App\Entity\Round;
use App\Entity\User;
use App\Repository\RoundsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class RouletteService
{
    /** @var RoundsRepository */
    protected $roundsRepository;

    /** @var RouletteRotator */
    protected $rotator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LogService
     */
    private $logService;

    public function __construct(
        RoundsRepository $roundsRepository,
        LogService $logService,
        RouletteRotator $rotator,
        EntityManagerInterface $em
    ) {
        $this->em = $em;
        $this->roundsRepository = $roundsRepository;
        $this->logService = $logService;
        $this->rotator = $rotator;
    }

    /**
     * @return Round
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getActiveRound(): Round
    {
        return $this->roundsRepository->getActiveRound();
    }

    /**
     * @param User $user
     * @return array
     * @throws Exception
     */
    public function rotate(User $user): array
    {
        $this->em->beginTransaction();
        try {
            $this->rotator->init();
            $rotatorResult = $this->rotator->rotate();

            // write stat:
            $this->logService->save($user, $this->rotator->getRound(), ['value' => $rotatorResult]);

            $this->em->commit();
            return [
                'round' => $this->rotator->getRound(),
                'result' => $rotatorResult,
            ];
        } catch (Exception $exception) {
            $this->em->rollback();
            throw $exception;
        }
    }
}