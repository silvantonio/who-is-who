<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends FOSRestController
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
