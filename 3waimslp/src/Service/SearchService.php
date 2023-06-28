<?php 

declare(strict_types=1);

namespace App\Service;

class SearchService {
    public function __construct() {
    }

    public function callApiForMusic(int $start): string {
        return file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=2/start=$start/limit=1000/retformat=json");
    }

    private function callApiForComposer(int $start): string {
        return file_get_contents("https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=1/start=$start/limit=1000/retformat=json");
    }


    public function searchForTargetMusic(string $target) {
        // API has 224063 entries in music, so take a guess as to a good starting spot
        // for now we start in the middle
        //$json = callApiForMusic(110000);
    }   
}

