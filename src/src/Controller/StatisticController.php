<?php

namespace App\Controller;

use App\Repository\LogsRepository;
use App\Statistic\StatisticContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class StatisticController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var LogsRepository
     */
    private $logsRepository;
    /**
     * @var StatisticContext
     */
    private $statisticContext;

    public function __construct(
        StatisticContext $statisticContext,
        SerializerInterface $serializer,
        LogsRepository $logsRepository
    ) {
        $this->serializer = $serializer;
        $this->logsRepository = $logsRepository;
        $this->statisticContext = $statisticContext;
    }

    /**
     * @Route("/roulette/stat/{type}", name="roulette_stat", methods={"GET"})
     * @param $type
     * @return JsonResponse
     */
    public function stat(string $type)
    {
        $result = $this->statisticContext->handleStat($type);

        return new JsonResponse($result);
    }
}
