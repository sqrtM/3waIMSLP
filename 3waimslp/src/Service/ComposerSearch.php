<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\NoApiResponseException;
use Exception;

class ComposerSearch
{

    public function getByIndex(int $index)
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

    public function search(string $searchTerm, int $iterations, int $desiredResponses)
    {
        $results = [];
        for ($i = 0; $i < $iterations; $i++) {
            $response = $this->findTarget($searchTerm, $i * 1000, $desiredResponses);
            if (!is_null($response)) {
                array_push($results, ...$response);
            }
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

    private function findTarget(string $target, int $start, int $desiredResponses)
    {
        try {
            $json = $this->callApi($start);
        } catch (NoApiResponseException $e) {
            throw $e;
        }

        // if we can't find a match .... 
        if (!strpos($json, $target)) {
            return null;
        } else {
            $arr = json_decode($json, associative: true);
            array_pop($arr); // remove metadata from array
            $returnArr = $this->recursiveSearch($arr, $target, $start, $desiredResponses);
            return $returnArr;
        }
    }

    private function recursiveSearch($arr, $needle, $index, $desiredResponses, $offset = 0, &$results = array())
    {
        foreach ($arr as $key => $value) {
            $offset = strpos($value["id"], $needle, $offset);
            if ($offset !== false) {
                if ($key - 1 >= 0) {
                    array_push($results, [($key + $index) => $arr[$key - 1]]);
                }
                for ($i = 0; $i < $desiredResponses; $i++) {
                    if ($key + $i < 1000) array_push($results, [($key + $index + $i) => $arr[$key + $i]]);
                }
                return $this->recursiveSearch(array_slice($arr, count($results)), $needle, $index + count($results), $desiredResponses, $offset, $results);
            } else {
                return $results;
            }
        }
    }
}
