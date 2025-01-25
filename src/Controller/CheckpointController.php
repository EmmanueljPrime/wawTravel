<?php

namespace App\Controller;

use App\Entity\Checkpoint;
use App\Entity\Image;
use App\Entity\RoadTrip;
use App\Form\CheckpointType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/checkpoint')]
class CheckpointController extends AbstractController
{
    #[Route('/edit/{id}', name: 'app_checkpoint_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Checkpoint $checkpoint,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérification de l'autorisation
        if ($checkpoint->getRoadTrip()->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot edit this checkpoint.');
        }

        $form = $this->createForm(CheckpointType::class, $checkpoint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();

            foreach ($images as $imageFile) {
                $image = new Image();
                $image->setPath('uploads/checkpoints/'. uniqid() . '.' . $imageFile->guessExtension());
                $image->setType('checkpoint');
                $image->setCheckpoint($checkpoint);

                $imageFile->move('uploads/checkpoints/', $image->getPath());

                $entityManager->persist($image);
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('checkpoint/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new/{roadTripId}', name: 'app_checkpoint_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        int $roadTripId,
        EntityManagerInterface $entityManager
    ): Response {
        $roadTrip = $entityManager->getRepository(RoadTrip::class)->find($roadTripId);

        if (!$roadTrip || $roadTrip->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot add a checkpoint to this road trip.');
        }

        $checkpoint = new Checkpoint();
        $checkpoint->setRoadTrip($roadTrip);

        $form = $this->createForm(CheckpointType::class, $checkpoint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();

            foreach ($images as $imageFile) {
                $image = new Image();
                $image->setPath('uploads/checkpoints/'. uniqid() . '.' . $imageFile->guessExtension());
                $image->setType('checkpoint');
                $image->setCheckpoint($checkpoint);

                $imageFile->move('uploads/checkpoints/', $image->getPath());

                $entityManager->persist($image);
            }

            $entityManager->persist($checkpoint);
            $entityManager->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('checkpoint/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_checkpoint_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Checkpoint $checkpoint,
        EntityManagerInterface $entityManager
    ): Response {
        if ($checkpoint->getRoadTrip()->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot delete this checkpoint.');
        }

        if ($this->isCsrfTokenValid('delete' . $checkpoint->getId(), $request->request->get('_token'))) {
            // Supprime le fichier image associé
            if ($checkpoint->getImage()) {
                $imagePath = $this->getParameter('checkpoint_images_directory') . '/' . $checkpoint->getImage();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $entityManager->remove($checkpoint);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile');
    }
}