<?php

declare(strict_types=1);

namespace Tests;

use App\Service\SearchService;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{

    private SearchService $search;

    public function testSearchForMusicAlgorithm()
    {
        $this->search = new SearchService();
        $results = $this->search->searchForMusic("Cembalo", 2);
        fwrite(STDERR, print_r($results, TRUE));
        $this->assertIsArray($results);
    }

    public function testSearchForComposerAlgorithm()
    {
        $this->search = new SearchService();
        $results = $this->search->searchForComposer("Andreini", 3);
        fwrite(STDERR, print_r($results, TRUE));
        $this->assertIsArray($results);
    }

    public function testGetMusicByIndex() 
    {
        $this->search = new SearchService();
        $results = $this->search->getMusicByIndex(378);
        fwrite(STDERR, print_r($results, TRUE));
        $this->assertEquals("10 Deutsche Tänze mit Coda (Vocet, Ignác)", $results["id"]);
    }

    public function testGetComposerByIndex() 
    {
        $this->search = new SearchService();
        $results = $this->search->getComposerByIndex(378);
        fwrite(STDERR, print_r($results, TRUE));
        $this->assertEquals("Category:Akhmatova, Anna", $results["id"]);
    }
}
