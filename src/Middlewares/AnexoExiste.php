<?php

namespace App\Middlewares;

use App\Models\Anexo;
use Monolog\Level;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class AnexoExiste
{
    public function __invoke(Anexo $anexo): bool|Response
    {
        $filename = Anexo::uploadsDir($anexo->caminho);

        if (! file_exists($filename)) {
            logAppend(new FileNotFoundException($filename), level: Level::Emergency);

            return responseError(Response::HTTP_NOT_FOUND);
        }

        return true;
    }
}
