<?php



namespace App\Controller;

use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

class ProfilController extends AbstractController
{
    #[Route('/profile', name: 'profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérez les informations de profil de l'utilisateur actuel
        $user = $this->getUser();

        // Créez le formulaire de profil
        $form = $this->createForm(ProfilType::class, $user);

        // Traitez la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            /* // Vérifiez si le pseudo est unique
             $pseudo = $form->get('pseudo')->getData();
             $existingUser = $entityManager->getRepository(User::class)->findOneBy(['pseudo' => $pseudo]);
             if ($existingUser && $existingUser->getId() !== $user->getId()) {
                 $form->get('pseudo')->addError(new FormError('Ce pseudo est déjà pris.'));
                 return $this->render('profile/edit.html.twig', [
                     'form' => $form->createView(),
                 ]);
             }
 */
            // Mettez à jour les informations de profil de l'utilisateur
            $user = $form->getData();
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('profile_edit');
        }

        // Renvoie la vue contenant le formulaire de profil
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
