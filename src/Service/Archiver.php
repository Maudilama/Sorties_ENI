<?php

namespace App\Service;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;

class Archiver
{
    public function actualiseEtatSorties(array $sorties,
                                         EtatRepository $etatRepository,
                                         EntityManagerInterface $entityManager)
    {

        foreach ($sorties as $sortie) {

            $dateHeureDebutInSeconds = $sortie->getDateHeureDebut()->getTimestamp();
            $dureeSeconds = $sortie->getDuree()->getTimestamp();
            $dateTermineeInSeconds = $dateHeureDebutInSeconds + $dureeSeconds;


            $dateLimiteInscriptionInSeconds = $sortie->getDateLimiteInscription()->getTimestamp();

            $currentTimeinSeconds = time();
            $mois = 30 * 24 * 60 * 60;


            $etatArchivee = $etatRepository->EtatByLibelle(Etat::ARCHIVEE);
            if (($currentTimeinSeconds - $dateTermineeInSeconds) > $mois and $sortie->getEtat() != $etatArchivee) {
                $sortie->setEtat($etatArchivee);

            } elseif (($currentTimeinSeconds - $dateTermineeInSeconds) > 0 and $sortie->getEtat() != $etatArchivee) {
                $etatPassee = $etatRepository->EtatByLibelle(Etat::PASSEE);
                $sortie->setEtat($etatPassee);

            } elseif ($dateLimiteInscriptionInSeconds < $currentTimeinSeconds and $dateHeureDebutInSeconds > $currentTimeinSeconds) {
                $etatCloturee = $etatRepository->EtatByLibelle(Etat::CLOTUREE);
                $sortie->setEtat($etatCloturee);


            } elseif ($dateHeureDebutInSeconds < $currentTimeinSeconds and $dateTermineeInSeconds > $currentTimeinSeconds) {
                $etatEnCours = $etatRepository->EtatByLibelle(Etat::ENCOURS);
                $sortie->setEtat($etatEnCours);
            }


            $entityManager->persist($sortie);
            $entityManager->flush();


        }


    }
}