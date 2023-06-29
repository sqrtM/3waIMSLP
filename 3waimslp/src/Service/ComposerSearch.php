<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\NoApiResponseException;
use Exception;
use ValueError;

class ComposerSearch implements Search
{
    public function getByIndex(int $index): array
    {
        try {
            $response = file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=1/start=" . $index . "/limit=1/retformat=json");
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
        for ($i = 0; $i < $iterations && count($results) < $numOfResponses; $i++) {
            $results = $this->findTarget($searchTerm, $i * $resultsPerPage, $numOfResponses, $results);
        }
        return $results;
    }

    private function callApi(int $start): string
    {
        try {
            $apiResults = file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=1/start=" . $start . "/limit=1000/retformat=json");
        } catch (Exception $e) {
            throw new NoApiResponseException($e->getMessage(), $e->getCode());
        }
        if ($apiResults === false) {
            throw new NoApiResponseException();
        } else {
            return $apiResults;
        }
    }

    private function findTarget(string $target, int $start, int $numOfResponses, array $returnArr): array|null
    {
        try {
            $json = $this->callApi($start);
        } catch (NoApiResponseException $e) {
            throw $e;
        }
        if (strpos($json, $target) !== false && count($returnArr) < $numOfResponses) {
            $arr = json_decode($json, associative: true);
            array_pop($arr); // remove metadata from array
            array_push($returnArr, ...$this->recursiveSearch($arr, $target, $start, $numOfResponses));
        }
        return $returnArr;
    }

    private function recursiveSearch(
        array $arr,
        string $target,
        int $index,
        int $numOfResponses,
        int $offset = 0,
        array $results = array()
    ): array {
        foreach ($arr as $key => $value) {
            try {
                $offset = strpos($value["id"], $target, $offset);
            } catch (ValueError $e) {
                return $results;
            }

            if ($offset !== false && count($results) < $numOfResponses) {
                array_push($results, [($key + $index) => $arr[$key]]);
                $arr = array_slice($arr, count($results));
                return $this->recursiveSearch(
                    $arr,
                    $target,
                    $index + count($results),
                    $numOfResponses,
                    $offset,
                    $results
                );
            } else {
                return $results;
            }
        }
    }
}
