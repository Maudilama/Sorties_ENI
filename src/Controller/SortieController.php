<?php

namespace App\Controller;

use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route ('/createSortie/{id}inscriptionSortie', name: 'inscriptionSortie')]
    public function inscriptionSortie(Sortie $sortie,
                                      Request $request,
                                      EntityManagerInterface $entityManager): Response
    {

        $participant = $this->getUser();
        if (!$participant) {
            return $this->redirectToRoute('');
        }

        $sortie->addParticipant($participant);
        $entityManager->persist($participant);
        $entityManager->flush();

        return $this->redirectToRoute('', ['id' => $sortie->getId()]);
    }




}