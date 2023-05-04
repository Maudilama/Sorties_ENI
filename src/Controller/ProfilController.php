<?php



namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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

        // Gestion de la photo de profil
        $photoFile = $form->get('photo')->getData();
        if ($photoFile) {
            // Nom du fichier unique
            $newFilename = uniqid().'.'.$photoFile->guessExtension();

            try {
                $photoFile->move(
                    $this->getParameter('photo_directory'),
                    $newFilename
                );
            } catch (FileException $e) {$form->addError(new FormError('Une erreur s\'est produite lors du téléchargement de la photo.'));
                return $this->render('profile/edit.html.twig', [
                    'form' => $form->createView(),
                ]);

            // Gérer l'erreur si le téléchargement échoue
            }

            // Stocker le nom de fichier dans la base de données
            $user->setPhotoFilename($newFilename);
        }

        // Récupérez la nouvelle valeur du champ de mot de passe
        $newPassword = $form->get('password')->getData();

        // Si le champ de mot de passe est vide, ne modifiez pas le mot de passe
        if (!empty($newPassword)) {
            //crypter le mot de passe
            $newPasswordHashed = $userPasswordHasher->hashPassword($user, $newPassword);
            // Mettre à jour le mot de passe dans la base de données
            $user->setPassword($newPasswordHashed);
        }

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

    // Vérifier si l'utilisateur a une photo de profil
    $photoFilename = $user->getPhotoFilename();

        // Renvoie la vue contenant le formulaire de profil
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        'photoFilename' => $photoFilename,
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





