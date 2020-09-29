<?php

namespace App\Controller;

use App\Service\RouletteService;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class RoundsController extends AbstractController
{
    /**
     * @var RouletteService
     */
    private $rouletteService;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(RouletteService $rouletteService, SerializerInterface $serializer)
    {
        $this->rouletteService = $rouletteService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/roulette", name="roulette", methods={"GET","HEAD"})
     * @Template
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Doctrine\ORM\ORMException
     */
    public function index()
    {
        return [
            'round' => $this->rouletteService->getActiveRound(),
        ];
    }

    /**
     * @Route("/roulette/rotate", name="roulette_rotate", methods={"POST"})
     * @Template
     * @return JsonResponse
     */
    public function rotate()
    {
        try {
            $data = $this->rouletteService->rotate($this->getUser());
            $json = $this->serializer->serialize($data, 'json');
            return new JsonResponse($json, 200, [], true);
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'err' => $e->getCode(),
                    'msg' => $e->getMessage(),
                ], 500
            );
        }
    }
}
