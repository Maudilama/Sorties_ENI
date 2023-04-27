<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'main_')]
class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->findAll();

        return $this->render('main/home.html.twig', [
            "sorties"=>$sortie,

        ]);
    }


}
