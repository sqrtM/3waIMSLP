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

        //get all resssources
        $musics =  $this->musicSearch->search($get['search']['search'], 5,10);
        $composers = $this->composerSearch->search($get['search']['search'], 5,10);

        //merge
        $data = array_merge($musics, $composers);

        //send data to view
        return $this->render('search/results.html.twig', [
            'datas' => $data
        ]);
    }

    #[Route('/search/{type}/{id}', name: 'app_search_favorite')]
    public function addFavorite(int $type, int $id): Response
    {

        $fav = new Favorites();

        //set data from url
        $fav->setFavoritedUser($this->getUser());
        $fav->setCreatedAt(new \DateTimeImmutable());
        $fav->setType($type);
        $fav->setImslpId('1');
        $fav->setImslpIndex($id);


        $this->entityManager->persist($fav);

        $this->entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}