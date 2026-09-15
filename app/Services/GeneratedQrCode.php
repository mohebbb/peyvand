<?php

namespace App\Services;

final readonly class GeneratedQrCode
{
    public function __construct(
        public string $content,
        public string $contentType,
        public string $extension,
    ) {}
}
