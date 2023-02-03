<?php

namespace App\Controller;

use App\Service\JuegoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(JuegoService $js): Response
    {
        return $this->render('main/home.html.twig', [
            'imgsJuegos' => $js->getFondoPantalla(),
        ]);
    }
}
