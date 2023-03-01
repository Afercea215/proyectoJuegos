<?php

namespace App\Controller;

use App\Repository\JuegoRepository;
use App\Service\JuegoService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(JuegoService $js, JuegoRepository $jr): Response
    {
        return $this->render('main/home.html.twig', [
            'imgsJuegos' => $js->getFondoPantalla(),
            'juegos' => $jr->findAll(),
        ]);
    }
    #[Route('/', name: 'base')]
    public function base(): Response
    {
        return $this->redirectToRoute('home');
    }
}
