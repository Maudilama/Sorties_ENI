<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\User;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Service\Archiver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function Sodium\add;

#[Route('/', name: 'main_')]
class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(SortieRepository $sortieRepository,
                        Request $request,
                        EtatRepository $etatRepository,
                        EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $sortiesAActualiser = $sortieRepository->findAll();
        $actualiseEtat = new Archiver();
        $actualiseEtat->actualiseEtatSorties($sortiesAActualiser, $etatRepository, $entityManager);



        $user = $this->getUser();
        assert($user instanceof User);

        $defaultList = $sortieRepository->AllSortiesFromUserCampus($user);
        $filtreList = [];

        $today = new \DateTime();
        $formSubit = false;

        //Formulaire filtres
        $formFilter = $this->createFormBuilder()
            ->add('nom', TextType::class, [
                'label'=>'Nom de la sortie : ',
                'required'=> false
            ])
        ->add('campus', EntityType::class, [
            'class'=>Campus::class,
            'choice_label'=>'nom',
            'label'=>'Campus : ',
            'required'=> false,
            'placeholder' =>'Tous les Campus'
            ])
        ->add('dateDebut', DateTimeType::class, [
            'html5'=> true,
            'widget'=>'single_text',
            'required'=>false,
            'label'=> 'Sortie(s) entre le '
    ])
        ->add('dateFin', DateTimeType::class, [
        'html5'=> true,
        'widget'=>'single_text',
        'required'=>false,
            'label'=>'Et le '

    ])
            ->add('sortiesOrganises', CheckboxType::class, [
                'label'=>'Sorties dont je suis l\'organisateur',
                'required'=>false,
                'data'=>false
            ])
            ->add('sortiesInscrites', CheckboxType::class, [
                'label'=>'Sorties auxquelles je suis inscrit.e',
                'required'=>false,
                'data'=>false
            ])
            ->add('sortiesNonInscrites', CheckboxType::class, [
                'label'=>'Sorties auxquelles je ne suis pas inscrit.e',
                'required'=>false,
                'data'=>false
            ])
            ->add('sortiesPassees', CheckboxType::class, [
                'label'=>'Sorties passées',
                'required'=>false,
                'data'=>false
            ])
        ->getForm();

        $formFilter->handleRequest($request);

        if ($formFilter->isSubmitted() && $formFilter->isValid()){
            $data = $formFilter->getData();
            $sortieList = $sortieRepository->filtreSorties($data, $user);

        }else {
            $sortieList = $defaultList;
        }


        return $this->render('main/home.html.twig', [
            "sorties"=>$sortieList,
            'user' => $user,
            'today'=> $today,
            'filterForm'=>$formFilter->createView()

        ]);
    }


    #[Route('/desistement/{id}', name: 'sortie_desistement')]
    public function desistement(int $id,
                                EntityManagerInterface $entityManager,
                                Request $request,
                                SortieRepository $sortieRepository,
                                UserRepository $userRepository): Response
    {
            $sortie = $sortieRepository->find($id);

            $user = $this->getUser();
            assert($user instanceof User);

            $sortie->removeParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('succes', 'Vous vous êtes désinscrit!');
            return $this->redirectToRoute('app_home');

    }


}
