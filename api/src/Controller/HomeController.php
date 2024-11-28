<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController
{
    #[Route('/', name: 'app_lucky_number')]
    public function number(): Response
    {
        return new Response(
            '<html><body>hello!</body></html>'
        );
    }
}