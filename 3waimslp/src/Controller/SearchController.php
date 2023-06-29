<?php

namespace App\Controller;

use App\Entity\Favorites;
use App\Form\SearchType;
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
    public function __construct(SearchService $searchService, EntityManagerInterface $entityManager){

    $this->searchService = $searchService;
    $this->entityManager = $entityManager;

    }

    #[Route('/search', name: 'app_search')]
    public function index(Request $request): Response
    {

        $form = $this->createForm(SearchType::class);

        $form->handleRequest($request);

        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/search/results', name: 'app_search_results')]
    public function results(Request $request): Response
    {
        //get data from request
        dd($request->query);

        //Call api with data to get results
        return $this->render('search/results.html.twig');
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