<?php

namespace App\Middlewares;

use App\Http\Request;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpFoundation\Response;

class ValidateCsrfToken
{
    public function __invoke(Request $request): bool|Response
    {
        if (in_array($request->getMethod(), [
            BaseRequest::METHOD_DELETE,
            BaseRequest::METHOD_PATCH,
            BaseRequest::METHOD_POST,
            BaseRequest::METHOD_PUT,
        ])) {
            return $request->verifyCsrfToken() ?: responseError(Response::HTTP_BAD_REQUEST);
        }

        return true;
    }
}
