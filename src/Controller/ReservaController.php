<?php

namespace App\Controller;

use App\Form\MesaType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReservaController extends AbstractController
{
    #[Route('/reservar', name: 'app_reserva')]
    public function index(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(MesaType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //creo y subo la mesa si no tiene errores
            $mesa = ($form->getData()); 
            $entityManager = $doctrine->getManager();
            
            //$errors = $validator->validate($mesa);
            
            //if(count($errors)==0){
                try {
                    $entityManager->persist($mesa);
                    $entityManager->flush();
                    $this->addFlash(
                        'success',
                        '¡Mesa creada!'
                    );
                } catch (\Throwable $th) {
                    $this->addFlash(
                        'error',
                        '¡No se ha podido crear la mesa!'
                    );
                    //throw $th;
                }
            //}else{
            //}
        }

        return $this->render('Reserva/reservar.html.twig', [
            'form' => $form,
        ]);
    }
}
