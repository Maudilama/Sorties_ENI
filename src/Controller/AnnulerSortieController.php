<?php

namespace App\Controller;





use App\Controller\SortieRepository;
use App\Form\AnnulerFormType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AnnulerSortieController extends AbstractController

{
    #[Route ('/annulerSortie{id}', name: 'annulerSortie')]
   public function annulerSortie(int $id,
                            \App\Repository\SortieRepository $sortieRepository,
                            EtatRepository $etatRepository,
                            Request $request,
                            EntityManagerInterface $entityManager,
                            ParticipantRepository $participantRepository): Response
    {

        $sortie = $sortieRepository->find($id);
        $etat=$etatRepository->EtatByLibelle('Annulée');

        $infosA = $sortie->getInfosSortie();
        $sortie->setInfosSortie("");
        $annulerForm = $this->createForm(AnnulerFormType::class, $sortie);
        $annulerForm->handleRequest($request);
        $infosB = $sortie->getInfosSortie();


        if ($annulerForm->isSubmitted() && $annulerForm->isValid()) {

            $sortie->setEtat($etat);

            $sortie->setInfosSortie(nl2br("MOTIF D'ANNULATION: $infosB  \n $infosA"));

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('succes', 'Votre sortie a été annulée');
            return $this->redirectToRoute('main_home');

        }
        return $this->render('annulerSortie/annulerSortie.html.twig', [
            'sortie' => $sortie,
            'annulerForm' => $annulerForm->createView()
        ]);
    }

           /*if($sortie->getEtat()->getLibelle() === 'Ouverte' || $sortie->getEtat()->getLibelle() === 'Clôturée'){

               $infosSortie = $sortie->getInfosSortie();

                $AnnulerSortieForm = $this->createForm(AnnulerFormType::class, $sortie);


                $AnnulerSortieForm ->handleRequest($request);

                if ($AnnulerSortieForm ->isSubmitted() && $AnnulerSortieForm->isValid())
                {

                       $etatAnnulee = $etatRepository->findBy(['libelle' => 'Annulée']);
                        $sortie->setInfosSortie($infosSortie .' - Motif d\'Annulation : '.$sortie->getInfosSortie());
                        $sortie->setEtat($etatAnnulee[0]);
                        $entityManager->persist($sortie);
                        $entityManager->flush();

                        $this->addFlash('success', 'La sortie a été annulée avec succès !');


                        return $this->redirectToRoute('main_home');
                    }

              }


                return $this->render('annulerSortie/annulerSortie.html.twig', [
                    'annulationSortieForm' => $AnnulerSortieForm->createView(),
                    'sortie' => $sortie
                ]);*/




}