<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{

    /**
     * Displays the list of honeymoon destinations with their images and descriptions.
     */
    #[Route('/', name: 'app_home')]
    public function index(DestinationRepository $destinationRepo): Response
    {
        // Uses the findBy function of the DestinationRepository to list destinations by type
        $destinations = $destinationRepo->findBy(['type' => 'honey_moon']);
        return $this->render('home/index.html.twig', [
            'destinations' => $destinations,
        ]);
    }

    /**
     * Displays to the details of the destination
     */
    #[Route('/destination/{id}', name: 'app_destination_details')]
    public function destinationDetails(Destination $destination): Response
    {
        return $this->render('home/destination_details.html.twig', [
            'destination' => $destination,
        ]);

    }
}
