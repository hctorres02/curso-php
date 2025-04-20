<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Anexo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AnexoController
{
    public function index(Request $request): Response
    {
        $data = Anexo::toSearch([
            'periodo_id' => $request->get('periodo_id'),
            'disciplina_id' => $request->get('disciplina_id'),
        ]);

        return response('anexos/index.twig', $data);
    }

    public function ver(Anexo $anexo): BinaryFileResponse|Response
    {
        $file = Anexo::uploadsDir($anexo->caminho);
        $filename = $anexo->nome_original;
        $contentType = $anexo->tipo;

        if (preg_match('/(application|image|text)\//', $contentType)) {
            $response = new BinaryFileResponse($file);

            $response->headers->set('content-type', $contentType);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename);

            return $response;
        }

        return responseError(Response::HTTP_NO_CONTENT);
    }
}
