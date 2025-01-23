<?php

namespace App\Controller;

use App\Entity\RoadTrip;
use App\Form\RoadTripType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/road/trip')]
final class RoadTripController extends AbstractController
{
    #[Route(name: 'app_road_trip_index', methods: ['GET'])]
    public function index(): Response
    {
        // Récupère les road trips de l'utilisateur connecté
        $roadTrips = $this->getUser()->getRoadTrips();

        return $this->render('road_trip/index.html.twig', [
            'road_trips' => $roadTrips,
        ]);
    }

    #[Route('/new', name: 'app_road_trip_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security, ): Response
    {
        $roadTrip = new RoadTrip();
        $roadTrip->setOwner($security->getUser());

        $form = $this->createForm(RoadTripType::class, $roadTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//

            $entityManager->persist($roadTrip);
            $entityManager->flush();

            return $this->redirectToRoute('app_road_trip_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('road_trip/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_road_trip_show', methods: ['GET'])]
    public function show(RoadTrip $roadTrip): Response
    {
        if ($roadTrip->getOwner() !== $this->getUser()) {
            throw new AccessDeniedException('You cannot access this road trip.');
        }

        return $this->render('road_trip/show.html.twig', [
            'road_trip' => $roadTrip,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_road_trip_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RoadTrip $roadTrip, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur est bien le propriétaire du voyage
        if ($roadTrip->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot edit this road trip.');
        }

        // Associe le formulaire au voyage existant
        $form = $this->createForm(RoadTripType::class, $roadTrip, [
            'action' => $this->generateUrl('app_road_trip_edit', ['id' => $roadTrip->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//

            // Sauvegarde des modifications
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('road_trip/edit.html.twig', [
            'form' => $form->createView(),
            'roadTrip' => $roadTrip,
        ]);
    }

    #[Route('/{id}', name: 'app_road_trip_delete', methods: ['POST'])]
    public function delete(Request $request, RoadTrip $roadTrip, EntityManagerInterface $entityManager): Response
    {
        if ($roadTrip->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot delete this road trip.');
        }

        if ($this->isCsrfTokenValid('delete' . $roadTrip->getId(), $request->request->get('_token'))) {
            $entityManager->remove($roadTrip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile');
    }
}