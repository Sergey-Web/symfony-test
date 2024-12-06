<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Layer;
use App\Service\Universal;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoiController
{
    public function  __construct(
        private readonly Universal $universal,
        private Layer $layer,
    ){
    }

    #[Route('/roi', name: 'roi')]
    public function generateData(Request $request): Response
    {
        $this->universal->getDataStatistic();

        return new Response('test');
    }

    #[Route('/roi/layer/{userId}/{layer}', name: 'roi_layer')]
    public function getLayer(string $userId, string $layer): Response
    {
        $data = $this->layer->getLayer($userId, $layer);

        return new JsonResponse(iterator_to_array($data));
    }

    #[Route('/roi/record/{userId}/{layer}/{id}', name: 'roi_record')]
    public function getRecord(string $userId, string $layer, string $id): Response
    {
        $data = $this->layer->getRecord($userId, $layer, $id);

        return new JsonResponse(iterator_to_array($data));
    }
}