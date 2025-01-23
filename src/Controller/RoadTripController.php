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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security,SluggerInterface $slugger ): Response
    {
        $roadTrip = new RoadTrip();
        $roadTrip->setOwner($security->getUser());

        $form = $this->createForm(RoadTripType::class, $roadTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $coverImageFile = $form->get('coverImage')->getData();

            if ($coverImageFile) {
                $originalFilename = pathinfo($coverImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverImageFile->guessExtension();

                try {
                    $coverImageFile->move(
                        $this->getParameter('uploads_directory'), // Répertoire défini dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer les erreurs d'upload
                    throw new \Exception('File upload failed.');
                }

                // Enregistrer le nom du fichier dans l'entité
                $roadTrip->setCoverImage($newFilename);
            }

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
    public function edit(
        Request $request,
        RoadTrip $roadTrip,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
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
            // Gestion de l'upload de l'image
            /** @var UploadedFile $coverImageFile */
            $coverImageFile = $form->get('coverImage')->getData();

            if ($coverImageFile) {
                // Générer un nom de fichier unique
                $originalFilename = pathinfo($coverImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverImageFile->guessExtension();

                // Déplacer l'image dans le répertoire configuré
                try {
                    $coverImageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('An error occurred while uploading the file.');
                }

                // Supprimer l'ancienne image si elle existe
                if ($roadTrip->getCoverImage()) {
                    $oldImagePath = $this->getParameter('uploads_directory') . '/' . $roadTrip->getCoverImage();
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Mettre à jour le champ coverImage avec le nouveau fichier
                $roadTrip->setCoverImage($newFilename);
            }

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