<?php

namespace App\Dto;

class ProverbDto
{
    public function __construct(
        public string $content,
        public array $tags,
        public int $topic = 1,
    )
    {

    }
}