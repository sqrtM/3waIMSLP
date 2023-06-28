<?php 

declare(strict_types=1);

namespace App\Service;

class SearchService {
    public function __construct() {
    }

    public function callApiForMusic(int $start): string {
        return file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=2/start=$start/limit=1000/retformat=json");
    }

    public function callApiForComposer(int $start): string {
        return file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=1/start=$start/limit=1000/retformat=json");
    }


    public function searchForTargetMusic(string $target) {
        $json = $this->callApiForMusic(0);
        if (strpos($json, $target) === false) {
            return json_decode($json, associative: true)[0]["intvals"]["worktitle"];
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
}

