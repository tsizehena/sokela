<?php

namespace App\Dto;

class ListFilterDto
{
    public function __construct(
        public int $limit,
        public int $page,
        public ?string $query,
        public ?int $order_by,
    )
    {
    }
}