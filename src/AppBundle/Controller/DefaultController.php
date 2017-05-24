<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function pingAction(): JsonResponse
    {
        return new JsonResponse([
            'ping' => 'pong',
        ]);
    }
}
