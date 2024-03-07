<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{

    public function __construct(DestinationRepository $destinationRepo){
        $this->destinationRepo = $destinationRepo;
    }
    /**
     * Displays the list of destinations with their images and descriptions.
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $destinations = $this->destinationRepo->findAll();
        return $this->render('home/index.html.twig', [
            'destinations' => $destinations,
        ]);
    }

    /**
     * Displays to the details of the destination
     */
    #[Route('/destination/{id}', name: 'app_destination_details')]
    public function destinationDetails(Request $request, int $id): Response
    {

        $destination = $this->destinationRepo->find($id);
        return $this->render('home/destination_details.html.twig', [
            'destination' => $destination,
        ]);

    }
}
