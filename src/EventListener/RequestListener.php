<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RequestListener
{
    #[AsEventListener]


    public function onKernelRequest(
        RequestEvent $event,

    ): void {

        $request = $event->getRequest();
        // print_r($request);
        // die;
        // apply only for API routes
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }
        // example: API key check
        $apiKey = $request->headers->get('X-API-KEY');
        if ($apiKey !== '123456') {
            $event->setResponse(new JsonResponse([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401));
        }
    }
}
