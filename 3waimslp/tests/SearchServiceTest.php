<?php

declare(strict_types=1);

namespace Tests;

use App\Service\SearchService;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{

    private SearchService $search;

    public function testSearchForMusicAlgorithmWorks()
    {
        $this->search = new SearchService();
        $results = $this->search->searchForMusic("Cembalo", 2);
        fwrite(STDERR, print_r($results, TRUE));
        $this->assertIsArray($results);
    }

    public function testSearchForComposerAlgorithmWorks()
    {
        $this->search = new SearchService();
        $results = $this->search->searchForComposer("Andreini", 3);
        fwrite(STDERR, print_r($results, TRUE));
        $this->assertIsArray($results);
    }
}
