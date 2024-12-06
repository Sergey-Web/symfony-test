<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Layer;
use App\Service\Universal;
use Redis;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController
{
    public function  __construct(
        private readonly Universal $universal,
    ){
    }

    #[Route('/home', name: 'home')]
    public function number(Request $request): Response
    {
        var_dump($this->universal->getDataStatistic());die;

//        $redis = new Redis();
//        $redis->connect('redis');

    }
}