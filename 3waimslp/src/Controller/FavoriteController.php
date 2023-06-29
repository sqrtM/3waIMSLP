<?php

namespace App\Controller;

use App\Entity\Favorites;
use App\Repository\FavoritesRepository;
use App\Service\ComposerSearch;
use App\Service\MusicSearch;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    public function __construct(FavoritesRepository $favoritesRepo, MusicSearch $musicSearch, ComposerSearch $composerSearch, EntityManagerInterface $entityManager){
        $this->favoritesRepo = $favoritesRepo;
        $this->composerSearch = $composerSearch;
        $this->musicSearch = $musicSearch;
        $this->entityManager = $entityManager;
    }

    #[Route('/favorites', name: 'app_favorites')]
    public function index(): Response
    {

        $user = $this->getUser();

        if($user === null){
            $this->redirectToRoute('app_login');
        }

        $favorites = $this->favoritesRepo->findBy(['favoritedUserId' => $user->getId()]);

        $results = [];

        foreach ($favorites as $favorite) {
            if ($favorite->getType() === 1) {

                $fav = $this->composerSearch->getByIndex($favorite->getImslpIndex());

            } else {
                $fav = $this->musicSearch->getByIndex($favorite->getImslpIndex());

            }
            $fav += ['idDb'=> $favorite->getId()];
            $results[] = $fav ;
        }

        return $this->render('favorites/index.html.twig', [
            'favorites' => $results,
        ]);
    }

    #[Route('/favorites/delete/{id}', name: 'app_favorites_delete')]
    public function delete(int $id): Response
    {

        $favEntity = $this->entityManager->getRepository(Favorites::class)->findOneBy(['id' => $id]);

        $this->entityManager->remove($favEntity);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_favorites');
    }

}