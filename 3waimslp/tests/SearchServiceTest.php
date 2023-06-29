<?php

declare(strict_types=1);

namespace Tests;

use App\Exception\NoApiResponseException;
use App\Service\SearchService;
use Exception;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{

    private SearchService $service;

    public function testSearchForMusicAlgorithm()
    {
        $this->service = new SearchService();
        $results = $this->service->music->search("A", 3, 5);
        fwrite(STDERR, print_r($results, TRUE));
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    public function testSearchForComposerAlgorithm()
    {
        $this->service = new SearchService();
        $results = $this->service->composer->search("o", 3, 5);
        fwrite(STDERR, print_r($results, TRUE));
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    public function testGetMusicByIndex() 
    {
        $this->service = new SearchService();
        $results = $this->service->music->getByIndex(378);
        //fwrite(STDERR, print_r($results, TRUE));
        $this->assertEquals("10 Deutsche Tänze mit Coda (Vocet, Ignác)", $results["id"]);
    }

    public function testGetComposerByIndex() 
    {
        $this->service = new SearchService();
        $results = $this->service->composer->getByIndex(378);
        //fwrite(STDERR, print_r($results, TRUE));
        $this->assertEquals("Category:Akhmatova, Anna", $results["id"]);
    }

    public function testNoApiResponseThrowsException() 
    {
        $this->expectException(NoApiResponseException::class);
        $this->getComposerByIndexBadUrl(378);
    }

    private function getComposerByIndexBadUrl(int $index)
    {
        try {
            $response = file_get_contents("https://imslp.org/imslpscripts/API.ISCR.ph?account=worklist/disclaimer=accepted/sort=id/type=1/start=" . $index . "/limit=1/retformat=json");
        } catch (Exception $e) {
            throw new NoApiResponseException($e->getMessage(), $e->getCode());
        }

        if ($response === false) {
            throw new NoApiResponseException();
        } else {
            $json = json_decode($response, associative: true);
            array_pop($json);
            return $json["0"];
        }
    }
}
