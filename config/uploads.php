<?php

return [
    'directory' => PROJECT_ROOT.'/uploads',
    'mimes' => [
        // documentos
        'application/msword' => ['doc'],
        'application/pdf' => ['pdf'],
        'application/rtf' => ['rtf'],
        'application/vnd.ms-excel' => ['xls'],
        'application/vnd.ms-powerpoint' => ['ppt'],
        'application/vnd.oasis.opendocument.text' => ['odt'],
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => ['pptx'],
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['xlsx'],
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
        'text/plain' => ['txt'],

        // imagens
        'image/bmp' => ['bmp'],
        'image/gif' => ['gif'],
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/svg+xml' => ['svg'],
        'image/tiff' => ['tif', 'tiff'],
        'image/webp' => ['webp'],

        // áudios
        'audio/aac' => ['aac'],
        'audio/flac' => ['flac'],
        'audio/midi' => ['midi', 'mid'],
        'audio/mpeg' => ['mp3'],
        'audio/ogg' => ['ogg'],
        'audio/wav' => ['wav'],
        'audio/webm' => ['weba'],
        'audio/x-ms-wma' => ['wma'],

        // vídeos
        'video/mp4' => ['mp4'],
        'video/quicktime' => ['mov'],
        'video/webm' => ['webm'],
        'video/x-matroska' => ['mkv'],
        'video/x-msvideo' => ['avi'],
    ]
];
