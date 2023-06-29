<?php

namespace App\Controller;

use App\Entity\Favorites;
use App\Form\SearchType;
use App\Service\ComposerSearch;
use App\Service\MusicSearch;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\SearchService;

class SearchController extends AbstractController
{
    public function __construct(SearchService $searchService, EntityManagerInterface $entityManager, ComposerSearch $composerSearch, MusicSearch $musicSearch){
        $this->searchService = $searchService;
        $this->entityManager = $entityManager;
        $this->composerSearch = $composerSearch;
        $this->musicSearch = $musicSearch;
    }

    #[Route('/search/results', name: 'app_search_results')]
    public function results(Request $request): Response
    {
        //get data from request
        $get = $request->query->all();

        $musics =  $this->musicSearch->search($get['search']['search'], 5);
        $composers = $this->composerSearch->search($get['search']['search'], 5);

        $data = array_merge($musics, $composers);

        //Call api with data to get results
        return $this->render('search/results.html.twig', [
            'datas' => $data
        ]);
    }

    //TODO Mettre id en {id} où seras notre imslpIndex pour retrouver la ressource
    #[Route('/search/id', name: 'app_search_favorite')]
    public function addFavorite(): Response
    {

        $fav = new Favorites();

        //get data from api with imslpIndex avec files_get_content
        $fav->setFavoritedUser($this->getUser());
        $fav->setCreatedAt(new \DateTimeImmutable());
        $fav->setType(1);
        $fav->setImslpId("1");
        $fav->setImslpIndex(1);


        $this->entityManager->persist($fav);

        $this->entityManager->flush();

        return $this->render('search/favorite.html.twig');
    }
}