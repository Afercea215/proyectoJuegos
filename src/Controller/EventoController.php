<?php

namespace App\Controller;

use App\Form\EventoType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventoController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/evento/nuevo', name: 'app_new_evento')]
    public function eventoNew(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(EventoType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //creo y subo la mesa si no tiene errores
            $evento = ($form->getData()); 
            $entityManager = $doctrine->getManager();
            $img = $form->get('img')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($img) {
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $newFilename = $originalFilename.'-'.uniqid().'.'.$img->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $direct = $this->getParameter('img_juego_directory');
                    
                    $img->move(
                        $direct,
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e);
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $evento->setImg($newFilename);
            }

            // ... persist the $product variable or any other work

            try {
                $entityManager->persist($evento);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Evento Creado!'
                );
                return $this->redirectToRoute('app_admin_juegos');

            } catch (\Throwable $th) {
                dd([$th,$evento]);
                $this->addFlash(
                    'error',
                    '¡No se ha podido crear el evento!'
                );
                    //throw $th;
            }
                //}else{
                    //}
        }
                
        return $this->render('Evento/new.html.twig',[
            'form' => $form,
        ]);
    }   
}
