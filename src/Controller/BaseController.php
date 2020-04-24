<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends AbstractController
{
    protected function success(array $data = []): JsonResponse
    {
        return $this->json([
                'reponse' => 'success',
            ] + $data);
    }

    protected function failed(string $message, int $code): JsonResponse
    {
        return $this->json([
            'reponse' => 'failed',
            'message' => $message,
        ], $code);
    }
}
