<?php

namespace App\Controller\Exception;

use Symfony\Component\HttpFoundation\Response;

class UnauthorizeException extends \Exception implements HttpCompliantExceptionInterface
{
    public function getHttpCode():int
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    public function getHttpResponseBody():string
    {
        return 'Unauthorized';
    }
}
