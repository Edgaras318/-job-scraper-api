<?php

namespace App\DTO;

class JobDTO
{
    public function __construct(
        public array $urls,
        public array $selectors
    ) {}
}
