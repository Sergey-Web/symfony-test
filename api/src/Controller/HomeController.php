<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Universal;
use Redis;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController
{
    public function  __construct(
        private Redis $redis,
        private readonly Universal $universal,
    ){

    }
    #[Route('/home', name: 'home')]
    public function number(Request $request): Response
    {
        $columns = $request->get('columns', []) ;
        $groups = $request->get('groups', []);

        if (!empty($columns)) {
            $columns = explode(',', $columns);
        }

        if (!empty($groups)) {
            $groups = explode(',', $groups);
        }

        var_dump($this->universal->getDataStatistic($columns, $groups));die;

        $redis = new Redis();
        $redis->connect('redis');

    }
}