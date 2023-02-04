<?php

namespace App\Controller;

use App\Form\MesaType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservaController extends AbstractController
{
    #[Route('/reservar', name: 'app_reserva')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(MesaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //creo y subo la mesa
            $mesa = ($form->getData()); 
            $entityManager = $doctrine->getManager();

            $entityManager->persist($mesa);
            $entityManager->flush();
        }
        $formValid = true;

        if($form->isSubmitted() && !$form->isValid()){
            $formValid = false;
        }

        return $this->render('Reserva/index.html.twig', [
            'form' => $form,
            'formValid' => $formValid,
        ]);
    }
}
