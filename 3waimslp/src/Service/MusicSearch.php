<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\NoApiResponseException;
use Exception;
use ValueError;

class MusicSearch implements Search
{
    public function getByIndex(int $index): array
    {
        try {
            $response = file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=2/start=" . $index . "/limit=1/retformat=json");
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

    public function search(string $searchTerm, int $iterations, int $numOfResponses): array
    {
        $resultsPerPage = 1000;
        $results = [];
        for ($i = 0; $i < $iterations; $i++) {
            $response = $this->findTarget($searchTerm, $i * $resultsPerPage, $numOfResponses);
            if (!is_null($response)) {
                array_push($results, ...$response);
            }
        }
        return $results;
    }

    private function callApi(int $start): string
    {
        try {
            $apiResults = file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=2/start=" . $start . "/limit=1000/retformat=json");
        } catch (Exception $e) {
            throw new NoApiResponseException($e->getMessage(), $e->getCode());
        }
        if ($apiResults === false) {
            throw new NoApiResponseException();
        } else {
            return $apiResults;
        }
    }

    private function findTarget(string $target, int $start, int $numOfResponses): array
    {
        try {
            $json = $this->callApi($start);
        } catch (NoApiResponseException $e) {
            throw $e;
        }
        $arr = json_decode($json, associative: true);
        array_pop($arr); // remove metadata from array
        $returnArr = $this->recursiveSearch($arr, $target, $start, $numOfResponses);
        return $returnArr;
    }

    private function recursiveSearch(
        array &$arr,
        string $target,
        int $index,
        int $numOfResponses,
        int $offset = 0,
        array &$results = array()
    ): array {
        foreach ($arr as $key => $value) {
            try {
                $offset = strpos($value["id"], $target, $offset);
            } catch (ValueError $_e) {
                return $results;
            }
            if ($offset !== false) {
                for ($i = 0; $i < $numOfResponses && $key + $i < 1000; $i++) {
                    array_push($results, [($key + $index + $i) => $arr[$key + $i]]); 
                }
                $arr = array_slice($arr, count($results));
                return $this->recursiveSearch(
                    $arr,
                    $target,
                    $index + count($results),
                    $numOfResponses,
                    $offset + 1,
                    $results
                );
            } else {
                return $results;
            }
        }
    }
}
