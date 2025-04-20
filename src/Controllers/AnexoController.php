<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Anexo;
use Monolog\Level;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

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

    public function ver(Request $request, Anexo $anexo): BinaryFileResponse|Response
    {
        $file = Anexo::uploadsDir($anexo->caminho);
        $filename = $anexo->nome_original;
        $contentType = $anexo->tipo;

        if (preg_match('/(application|image|text)\//', $contentType)) {
            $response = new BinaryFileResponse($file);

            $response->headers->set('Content-Type', $contentType);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename);

            return $response;
        }

        if (preg_match('/(audio|video)\//', $contentType)) {
            $size = filesize($file);
            $start = 0;
            $end = $size - 1;
            $range = $request->headers->get('Range');

            if ($range && preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                $end = isset($matches[2]) && $matches[2] !== '' ? intval($matches[2]) : $end;
            }

            $start = max(0, $start);
            $end = min($end, $size - 1);
            $length = $end - $start + 1;

            $response = new StreamedResponse(function () use ($file, $start, $length) {
                try {
                    $handle = fopen($file, 'rb');

                    if ($handle === false) {
                        return;
                    }

                    fseek($handle, $start);

                    $bufferSize = 8192; // 8kb
                    $bytesSent = 0;

                    while (! feof($handle) && $bytesSent < $length) {
                        $readLength = min($bufferSize, $length - $bytesSent);

                        echo fread($handle, $readLength);
                        flush();

                        $bytesSent += $readLength;
                    }

                    fclose($handle);
                } catch (Throwable $exception) {
                    return responseError(Response::HTTP_NO_CONTENT, $exception);
                }
            }, Response::HTTP_PARTIAL_CONTENT);

            $response->headers->set('Content-Disposition', "inline; filename=\"{$filename}\"");
            $response->headers->set('Content-Type', $contentType);
            $response->headers->set('Content-Length', $length);
            $response->headers->set('Content-Range', "bytes {$start}-{$end}/{$size}");
            $response->headers->set('Accept-Ranges', 'bytes');

            return $response;
        }

        return responseError(Response::HTTP_NO_CONTENT);
    }
}
