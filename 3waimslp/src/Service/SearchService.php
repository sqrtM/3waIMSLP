<?php

declare(strict_types=1);

namespace App\Service;

class SearchService
{
    public ComposerSearch $composer;
    public MusicSearch $music;

    public function __construct() { 
        $this->composer = new ComposerSearch();
        $this->music = new MusicSearch();
    }
}
