<?php

namespace App\Controller\Admin;

use App\Entity\Destination;
use App\Form\DestinationType;
use App\Service\ImageUploader;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Backoffice where administrators can create, update and delete destinations.
 */
#[Route('/admin/destination')]
class DestinationController extends AbstractController
{

     /**
     * @var ImageUploader
     */
    private $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }


    /**
     * Uses the 'findAll()' function of the DestinationRepository to list all destinations
     */
    #[Route('/', name: 'app_admin_destination_index', methods: ['GET'])]
    public function index(DestinationRepository $destinationRepository): Response
    {
        return $this->render('admin/destination/index.html.twig', [
            'destinations' => $destinationRepository->findAll(),
        ]);
    }


    /**
     * Destination CREATE Method
     */

    #[Route('/new', name: 'app_admin_destination_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $destination = new Destination();
        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * Uses the ImageUploader Service to upload the Destination image into 'uploads' Folder
             */
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = $this->imageUploader->upload($image);
                $destination->setImage($imageName);
            }


            $entityManager->persist($destination);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_destination_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/destination/new.html.twig', [
            'destination' => $destination,
            'form' => $form,
        ]);
    }


    /**
     * Destination UPDATE Method
     */
    #[Route('/{id}/edit', name: 'app_admin_destination_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Destination $destination, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = $this->imageUploader->upload($image);
                $destination->setImage($imageName);
            }
            $entityManager->persist($destination);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_destination_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/destination/edit.html.twig', [
            'destination' => $destination,
            'form' => $form,
        ]);
    }

    /**
     * Destination DELETE Method
     */

    #[Route('/{id}', name: 'app_admin_destination_delete', methods: ['POST'])]
    public function delete(Request $request, Destination $destination, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$destination->getId(), $request->request->get('_token'))) {
            $entityManager->remove($destination);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_destination_index', [], Response::HTTP_SEE_OTHER);
    }
}
