<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




class CreateController extends AbstractController
{
    #[Route ('/sortie', name: 'createSortie')]
    public function createSortie(Request $request,
                                 EntityManagerInterface
                                 $entityManager):Response

    {

        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);


        $sortieForm->handleRequest($request);



        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            if ($sortieForm->getClickedButton() && $sortieForm->getClickedButton()->getName() === 'publish') {

                $this->addFlash('success', 'Votre sortie a bien été publiée !');
            } else {
                // handle save action
                $this->addFlash('success', 'Votre sortie a bien été enregistrée !');
            }




            $sortie = $sortieForm->getData();
            $entityManager->persist($sortie);
            $entityManager->flush();



            return $this->redirectToRoute('main_home');
        }


        return $this->render('sortie/createSortie.html.twig', [
            "sortieForm" => $sortieForm->createView()

        ]);
    }





}