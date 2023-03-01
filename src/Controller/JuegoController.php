<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Form\JuegoType;
use App\Repository\JuegoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JuegoController extends AbstractController
{

    #[Route('/juegos', name: 'app_juegos', methods:'GET')]
    public function juegos(JuegoRepository $jr): Response
    {
        return $this->render('Juego/listado.html.twig',[
            'juegos' => $jr->findAll(),
        ]);
    }
    

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/juego/nuevo', name: 'app_new_juego')]
    public function juegoNew(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(JuegoType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //creo y subo la mesa si no tiene errores
            $juego = ($form->getData()); 
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
                        './images/juegos/',
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e);
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $juego->setImg($newFilename);
            }

            // ... persist the $product variable or any other work

            try {
                $entityManager->persist($juego);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    '¡Juego Creado!'
                );
                return $this->redirectToRoute('app_admin_juegos');

            } catch (\Throwable $th) {
                dd([$th,$juego]);
                $this->addFlash(
                    'error',
                    '¡No se ha podido crear el juego!'
                );
                    //throw $th;
            }
                //}else{
                    //}
        }
                
        return $this->render('Juego/new.html.twig',[
            'form' => $form,
        ]);
    }
    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/juego/edit/{id}', name: 'app_juego_edit')]
    public function juegoEdit(Request $request, ManagerRegistry $doctrine, Juego $j): Response
    {
        $form = $this->createForm(JuegoType::class, $j);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //creo y subo la mesa si no tiene errores
            $juego = ($form->getData()); 
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
                        './images/juegos/',
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e);
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $juego->setImg($newFilename);
            }

            // ... persist the $product variable or any other work

            try {
                $entityManager->persist($juego);
                $entityManager->flush();
                return $this->redirectToRoute('app_juegos');

            } catch (\Throwable $th) {
                dd($th);
                $this->addFlash(
                    'error',
                    '¡No se ha podido actualizar mesa!'
                );
                    //throw $th;
            }
                //}else{
                    //}
        }
                
        return $this->render('Juego/edit.html.twig',[
            'juego' => $j,
            'form' => $form,
        ]);
    }
    
    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/borrar/juego/{id}', name: 'app_juego_borrar')]
    public function borrarJuego(ManagerRegistry $doctrine, Juego $j=null): Response
    {
        if($j!=null){
            try {
                $entityManager = $doctrine->getManager();
                $entityManager->remove($j);
                $entityManager->flush();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        return $this->redirectToRoute('app_admin_juegos');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/juegos/{pag}/{orden}/{tipo}', name: 'app_admin_juegos')]
    public function adminJuegos(JuegoRepository $jr, int $pag=1, string $orden='nombre', string $tipo='ASC '): Response
    {
        $juegos = $jr->getPage($pag, $orden, $tipo);
        if ($juegos == []) {
            $pag = 1;
        }
        return $this->render('Juego/crudJuegos.html.twig',[
            'juegos' => $juegos,
            'pag' => $pag,
            'orden' => $orden,
            'tipo' => $tipo,
        ]);
    }

    
    #[Route('/juego/{id}', name: 'app_juego', methods:'GET')]
    public function juego(Juego $j): Response
    {
        return $this->render('Juego/juego.html.twig',[
            'juego' => $j,
        ]);
    }
}
