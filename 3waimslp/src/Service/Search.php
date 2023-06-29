<?php

declare(strict_types=1);

namespace App\Service;

interface Search
{
    public function getByIndex(int $index): array;
    public function search(string $searchTerm, int $iterations, int $numOfResponses): array;    
}
