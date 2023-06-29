<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\NoApiResponseException;
use Exception;

class MusicSearch
{
    public function getByIndex(int $index)
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

    public function search(string $searchTerm, int $iterations)
    {
        $results = [];
        for ($i = 0; $i < $iterations; $i++) {
            $response = $this->findTarget($searchTerm, $i * 1000);
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

    private function findTarget(string $target, int $start)
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
            $returnArr = [];
            foreach ($arr as $key => $value) {
                if (strpos($value["intvals"]["worktitle"], $target) !== false) {
                    if ($key - 1 >= 0) {
                        array_push($returnArr, [($key + $start) => $arr[$key - 1]]);
                    }
                    array_push($returnArr, [($key + $start) => $arr[$key]]);
                    if ($key + 1 < 1000) array_push($returnArr, [($key + $start + 1) => $arr[$key + 1]]);
                    if ($key + 2 < 1000) array_push($returnArr, [($key + $start + 2) => $arr[$key + 2]]);
                }
            }
            return $returnArr;
        }
    }
}
