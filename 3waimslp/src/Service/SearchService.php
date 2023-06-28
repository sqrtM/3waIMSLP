<?php 

declare(strict_types=1);

namespace App\Service;

use Exception;
use SebastianBergmann\Invoker\TimeoutException;

class SearchService {
    public function __construct() {
    }

    private function callApiForMusic(int $start): string {
        return file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=2/start=".$start."/limit=1000/retformat=json");
    }

    private function callApiForComposer(int $start): string {
        return file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=1/start=".$start."/limit=1000/retformat=json");
    }


    private function searchApiForTargetMusic(string $target, int $start) {
        $json = $this->callApiForMusic($start);
        // if we can't find it .... 
        if (!strpos($json, $target)) {
            return null;
        } else {
            $arr = json_decode($json, associative: true);
            array_pop($arr); // remove metadata from array
            $returnArr = [];
            for ($i = 0; $i < count($arr); $i++) {
                if (strpos($arr[$i]["intvals"]["worktitle"], $target) !== false) {
                    array_push($returnArr, $arr[$i - 1]);
                    array_push($returnArr, $arr[$i]);
                    array_push($returnArr, $arr[$i + 1]);
                    array_push($returnArr, $arr[$i + 2]);
                }
            }
            return $returnArr;
        }
    }
    
    public function searchForMusic(string $searchTerm, int $iterations) {
        $results = [];
        for ($i = 0; $i < $iterations; $i++) {
            $response = $this->searchApiForTargetMusic($searchTerm, $i * 1000);
            if (!is_null($response)) {
                array_push($results, ...$response);
            }
        }
        return $results;
    }
}

