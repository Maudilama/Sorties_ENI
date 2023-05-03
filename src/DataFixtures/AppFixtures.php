<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Sodium\add;
use function Symfony\Component\String\s;

class AppFixtures extends Fixture
{
    private $hasher;
    private $campusRepository;
    private $villeRepository;
    private $userRepository;
    private $lieuRepository;
    private $etatRepository;

    public function __construct(UserPasswordHasherInterface $hasher,
                                CampusRepository $campusRepository,
                                VilleRepository $villeRepository,
                                UserRepository $userRepository,
                                LieuRepository $lieuRepository,
                                EtatRepository $etatRepository)
    {
        $this->hasher = $hasher;
        $this->campusRepository = $campusRepository;
        $this->villeRepository = $villeRepository;
        $this->userRepository = $userRepository;
        $this->lieuRepository = $lieuRepository;
        $this->etatRepository = $etatRepository;
    }


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        //--------Ville----------
        $ville1 = new Ville();
        $ville1->setNom('Nantes');
        $ville1->setCodePostal('44000');
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom('Rennes');
        $ville2->setCodePostal('35000');
        $manager->persist($ville2);

        $ville3 = new Ville();
        $ville3->setNom('Niort');
        $ville3->setCodePostal('79000');
        $manager->persist($ville3);

        $ville4 = new Ville();
        $ville4->setNom('Quimper');
        $ville4->setCodePostal('29000');
        $manager->persist($ville4);
        $manager->flush();

        //--------Lieu----------
        //Génère lieu aléatoire
        $ville = $this->villeRepository->findAll();
        for ($i=1; $i<=30; $i++){
            $lieu = new Lieu();
            $lieu->setNom($faker->company);
            $lieu->setRue($faker->streetAddress);
            $lieu->setLatitude($faker->latitude);
            $lieu->setLongitude($faker->longitude);
            $lieu->setVille($faker->randomElement($ville));
            $manager->persist($lieu);
            $manager->flush();
        }

                //--------Etat----------
                $etats = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée', 'Historisée'];
                foreach ($etats as $libelle){
                    $etat = new Etat();
                    $etat->setLibelle($libelle);
                    $manager->persist($etat);
                    $manager->flush();
                }

                        //--------Campus----------
                        $campusArray = ['Nantes', 'Rennes', 'Quimper', 'Niort'];
                        foreach ($campusArray as $campusName){
                            $campus = new Campus();
                            $campus->setNom($campusName);
                            $manager->persist($campus);
                            $manager->flush();
                        }

                               //--------User----------
                               $campus = $this->campusRepository->findAll();
                               $user1 = new User();
                               $user1->setEmail('test@test.com');
                               $user1->setRoles(['ROLE_USER']);

                               $password1 = $this->hasher->hashPassword($user1, 'pass_1234');
                               $user1->setPassword($password1);

                               $user1->setNom('Test');
                               $user1->setPrenom('SuperTest');
                               $user1->setTelephone('0652369485');
                               $user1->setActif(true);
                               $user1->setCampus($faker->randomElement($campus));
                               $user1->setPseudo('SupTest');
                               $manager->persist($user1);


                               $user2 = new User();
                               $user2->setEmail('pif@test.com');
                               $user2->setRoles(['ROLE_USER']);

                               $password2 = $this->hasher->hashPassword($user2, 'pass_1234');
                               $user2->setPassword($password2);

                               $user2->setNom('Paf');
                               $user2->setPrenom('Pouf');
                               $user2->setTelephone('0652369485');
                               $user2->setActif(true);
                               $user2->setCampus($faker->randomElement($campus));
                               $user2->setPseudo('PifPafPouf');
                               $manager->persist($user2);


                               $admin = new User();
                               $admin->setEmail('admin@admin.com');
                               $admin->setRoles(['ROLE_ADMIN']);

                               $password3 = $this->hasher->hashPassword($admin, 'admin123');
                               $admin->setPassword($password3);

                               $admin->setNom('Admin');
                               $admin->setPrenom('Istrateur');
                               $admin->setTelephone('0652369485');
                               $admin->setActif(true);
                               $admin->setCampus($faker->randomElement($campus));
                               $admin->setPseudo('Admin');
                               $manager->persist($admin);


                               //générer User aléatoire
                               $campus = $this->campusRepository->findAll();
                               for($i = 1; $i<=20; $i++){
                                   $user = new User();
                                   $user->setNom($faker->lastName);
                                   $user->setPrenom($faker->firstName);
                                   $user->setPseudo($faker->unique()->userName);
                                   $user->setTelephone($faker->numerify('06########'));
                                   $user->setEmail($faker->email);
                                   $user->setPassword($this->hasher->hashPassword($user, 'pass_1234'));
                                   $user->setRoles(['ROLE_USER']);
                                   $user->setActif(true);
                                   $user->setCampus($faker->randomElement($campus));
                                   $manager->persist($user);
                                   $manager->flush();
                               }


                                       //--------Sortie----------

                                       //Génère Sortie Aléatoire
                                       $organisateurS = $this->userRepository->findAll();
                                        $participant = $this->userRepository->findAll();
                                       $lieuS = $this->lieuRepository->findAll();
                                       $etatS = $this->etatRepository->findAll();
                                       //$user = $this->userRepository->findAll();
                                       for ($i = 1; $i<=50; $i++){
                                           $sortie = new Sortie();
                                           $sortie->setNom('Sortie '.$i);
                                           $sortie->setDateLimiteInscription($faker->dateTimeBetween('-1 month', '+1month', 'Europe/Paris'));
                                           $sortie->setDateHeureDebut($faker->dateTimeBetween($sortie->getDateLimiteInscription(), '+1 month', 'Europe/Paris'));
                                           $sortie->setDuree($faker->dateTimeBetween($sortie->getDateHeureDebut(),$sortie->getDateHeureDebut()->add(new \DateInterval('P1D'))));
                                           $sortie->setNbInscriptionsMax($faker->numberBetween(3, 25));
                                           $sortie->setInfosSortie('La sortie numéro : '.$i);
                                           $sortie->setOrganisateur($faker->randomElement($organisateurS));
                                           $sortie->setCampus($sortie->getOrganisateur()->getCampus());
                                           $sortie->setLieu($faker->randomElement($lieuS));
                                           $sortie->setEtat($faker->randomElement($etatS));
                                           for ($j = 1; $j<= $faker->numberBetween(1, $sortie->getNbInscriptionsMax()); $j++){
                                           $sortie->addParticipant($faker->randomElement($participant));
                                           }
                                           $manager->persist($sortie);

                                       }

        $manager->flush();
    }
}
