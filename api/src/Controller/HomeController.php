<?php

declare(strict_types=1);

namespace App\Controller;

use Redis;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController
{
    #[Route('/home', name: 'home')]
    public function number(Request $request): Response
    {
        $columns = $request->get('columns');
        $groups = $request->get('groups');

        $redis = new Redis();
        $redis->connect('redis');

    }
}