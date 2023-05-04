<?php

namespace App\Controller;





use App\Entity\Sortie;
use App\Form\AnnulerFormType;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use http\Env\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\SortieRepository;

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


           if($sortie->getEtat()->getLibelle() === 'Ouverte' || $sortie->getEtat()->getLibelle() === 'Clôturée'){

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
                ]);


        }

}