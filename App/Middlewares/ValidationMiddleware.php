<?php
declare(strict_types=1);


namespace App\Middlewares;


use Http\Request;
use Http\Response;

class ValidationMiddleware extends Middleware
{

    /**
     * @inheritDoc
     */
    public function __invoke(Request $request, Response $response)
    {
        $this->then($request, $response);
    }
}