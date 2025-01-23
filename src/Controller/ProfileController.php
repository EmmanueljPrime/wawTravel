<?php

namespace App\Controller;

use App\Entity\RoadTrip;
use App\Entity\Checkpoint;
use App\Form\RoadTripType;
use App\Form\CheckpointType;
use App\Repository\RoadTripRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function profile(
        Request $request,
        RoadTripRepository $roadTripRepository,
        EntityManagerInterface $entityManager
    ): Response {

        $user = $this->getUser();

        $publicRoadTrips = $roadTripRepository->findBy(['visibility' => 'public','owner' => $user]);

        $roadTrips = $roadTripRepository->findBy(['owner' => $user]);

        // Nouveau road trip
        $newRoadTrip = new RoadTrip();
        $newRoadTrip->setOwner($user);
        $form = $this->createForm(RoadTripType::class, $newRoadTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newRoadTrip);
            $entityManager->flush();
            return $this->redirectToRoute('app_profile');
        }

        // Formulaires pour éditer et créer des checkpoints
        $editForms = [];
        $createCheckpointForms = [];
        $editCheckpointForms = [];

        foreach ($roadTrips as $roadTrip) {
            // Formulaire d'édition pour le road trip
            $editForms[$roadTrip->getId()] = $this->createForm(RoadTripType::class, $roadTrip)->createView();

            // Formulaire de création d'un checkpoint pour chaque road trip
            $newCheckpoint = new Checkpoint();
            $newCheckpoint->setRoadTrip($roadTrip);
            $createCheckpointForms[$roadTrip->getId()] = $this->createForm(CheckpointType::class, $newCheckpoint)->createView();

            // Formulaires d'édition pour chaque checkpoint du road trip
            foreach ($roadTrip->getCheckpoints() as $checkpoint) {
                $editCheckpointForms[$checkpoint->getId()] = $this->createForm(CheckpointType::class, $checkpoint)->createView();
            }
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'road_trips' => $roadTrips,
            'public_road_trips' => $publicRoadTrips,
            'form' => $form->createView(),
            'edit_forms' => $editForms,
            'create_checkpoint_forms' => $createCheckpointForms,
            'edit_checkpoint_forms' => $editCheckpointForms,
        ]);
    }

    #[Route('/search/{username}', name: 'app_public_profile', methods: ['GET'])]
    public function publicProfile(
        string $username,
        UserRepository $userRepository,
        RoadTripRepository $roadTripRepository
    ): Response {
        // Trouver l'utilisateur correspondant au username
        $user = $userRepository->findOneBy(['username' => $username]);

        // Si l'utilisateur n'existe pas, retourner une 404
        if (!$user) {
            throw $this->createNotFoundException("The user '{$username}' does not exist.");
        }

        // Récupérer les voyages publics de cet utilisateur
        $publicRoadTrips = $roadTripRepository->findBy([
            'owner' => $user,
            'visibility' => 'public',
        ]);

        // Rendre la vue pour afficher les voyages publics
        return $this->render('profile/public.html.twig', [
            'profileUser' => $user,
            'publicRoadTrips' => $publicRoadTrips,
        ]);
    }

}