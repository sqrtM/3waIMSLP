<?php 

declare(strict_types=1);

namespace Tests;

use App\Service\SearchService;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase {

    private SearchService $search;

    public function testSearchServiceCallsApi() {
        $this->search = new SearchService();
        $jsonRes = $this->search->callApiForMusic(0);
        $this->assertIsInt(strpos($jsonRes, "Amicizia"));
    }
}