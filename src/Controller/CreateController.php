<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CreateController extends AbstractController
{
    #[Route ('/createSortie', name: 'createSortie')]
    public function createSortie(Request $request,
                                 EntityManagerInterface
                                 $entityManager):Response

    {

        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);


        $sortieForm->handleRequest($request);



        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $sortie = $sortieForm->getData();


            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('main_home');
        }


        return $this->render('main/createSortie.html.twig', [
            "sortieForm" => $sortieForm->createView()

        ]);
    }





}