<?php



namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

class ProfilController extends AbstractController
{
    #[Route('/profile', name: 'profile_edit')]
    public function edit(Request                     $request,
                         EntityManagerInterface      $entityManager,
                         UserPasswordHasherInterface $userPasswordHasher,)


{
    // Récupérez les informations de profil de l'utilisateur actuel
    $user = $this->getUser();

    // Créez le formulaire de profil
    $form = $this->createForm(ProfilType::class, $user);

    // Traitez la soumission du formulaire
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        assert($user instanceof User);

        // Récupérez la nouvelle valeur du champ de mot de passe
        $newPassword = $form->get('password')->getData();

        //crypter le mot de passe
        $newPasswordHashed = $userPasswordHasher->hashPassword($user, $newPassword);

        // Mettre à jour le mot de passe dans la base de données
        $user->setPassword($newPasswordHashed);

        $entityManager->persist($user);
        $entityManager->flush();
            /*  // Vérifiez si le pseudo est unique
              $pseudo = $form->get('pseudo')->getData();
              $existingUser = $entityManager->getRepository(User::class)->findOneBy(['pseudo' => $pseudo]);
              if ($existingUser && $existingUser->getId() !== $user->getId()) {
                  $form->get('pseudo')->addError(new FormError('Ce pseudo est déjà pris.'));
                  return $this->render('profile/edit.html.twig', [
                      'form' => $form->createView(),
                  ]);
              }
    */

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('profile_edit');

        }

        // Renvoie la vue contenant le formulaire de profil
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


#[Route('//profile/{id}', name: 'profile_show', methods: ['GET'])]
    public function show(User $user): Response
{

        // Récupérer les champs de l'utilisateur pour les afficher
        $pseudo = $user->getPseudo();
        $prenom = $user->getPrenom();
        $nom = $user->getNom();
        $telephone = $user->getTelephone();
        $email = $user->getEmail();
        $campus = $user->getCampus()->getNom();


    return $this->render('profile/show.html.twig', [

        'user' => $user,
        'pseudo' => $pseudo,
        'prenom' => $prenom,
        'nom' => $nom,
        'telephone' => $telephone,
        'email' => $email,
        'campus' => $campus,
    ]);
}
}




