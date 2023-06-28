<?php

declare(strict_types=1);

namespace App\Service;

class SearchService
{
    public function __construct()
    {
    }

    public function getMusicByIndex(int $index)
    {
        $response = file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=2/start=" . $index . "/limit=1/retformat=json");
        $json = json_decode($response, associative: true);
        array_pop($json);
        return $json["0"];
    }

    public function getComposerByIndex(int $index)
    {
        $response = file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=1/start=" . $index . "/limit=1/retformat=json");
        $json = json_decode($response, associative: true);
        array_pop($json);
        return $json["0"];
    }

    public function searchForMusic(string $searchTerm, int $iterations)
    {
        $results = [];
        for ($i = 0; $i < $iterations; $i++) {
            $response = $this->searchApiForTargetMusic($searchTerm, $i * 1000);
            if (!is_null($response)) {
                array_push($results, ...$response);
            }
        }
        return $results;
    }

    public function searchForComposer(string $searchTerm, int $iterations)
    {
        $results = [];
        for ($i = 0; $i < $iterations; $i++) {
            $response = $this->searchApiForTargetComposer($searchTerm, $i * 1000);
            if (!is_null($response)) {
                array_push($results, ...$response);
            }
        }
        return $results;
    }

    private function callApiForMusic(int $start): string
    {
        return file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=2/start=" . $start . "/limit=1000/retformat=json");
    }

    private function callApiForComposer(int $start): string
    {
        return file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=1/start=" . $start . "/limit=1000/retformat=json");
    }


    private function searchApiForTargetMusic(string $target, int $start)
    {
        $json = $this->callApiForMusic($start);
        // if we can't find a match .... 
        if (!strpos($json, $target)) {
            return null;
        } else {
            $arr = json_decode($json, associative: true);
            array_pop($arr); // remove metadata from array
            $returnArr = [];
            foreach ($arr as $key => $value) {
                if (strpos($value["intvals"]["worktitle"], $target) !== false) {
                    if ($key - 1 >= 0) { array_push($returnArr, [($key + $start) => $arr[$key - 1]]); }
                    array_push($returnArr, [($key + $start) => $arr[$key]]);
                    if ($key + 1 < 1000) array_push($returnArr, [($key + $start + 1) => $arr[$key + 1]]);
                    if ($key + 2 < 1000) array_push($returnArr, [($key + $start + 2) => $arr[$key + 2]]);
                }
            }
            return $returnArr;
        }
    }

    private function searchApiForTargetComposer(string $target, int $start)
    {
        $json = $this->callApiForComposer($start);
        // if we can't find a match .... 
        if (!strpos($json, $target)) {
            return null;
        } else {
            $arr = json_decode($json, associative: true);
            array_pop($arr); // remove metadata from array
            $returnArr = [];
            foreach ($arr as $key => $value) {
                if (strpos($value["id"], $target) !== false) {
                    if ($key - 1 >= 0) { array_push($returnArr, [($key + $start) => $arr[$key - 1]]); }
                    array_push($returnArr, [($key + $start) => $arr[$key]]);
                    if ($key + 1 < 1000) array_push($returnArr, [($key + $start + 1) => $arr[$key + 1]]);
                    if ($key + 2 < 1000) array_push($returnArr, [($key + $start + 2) => $arr[$key + 2]]);
                }
            }
            return $returnArr;
        }
    }
}
