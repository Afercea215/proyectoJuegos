<?php

namespace App\Controller;

use App\Entity\Evento;
use App\Entity\Participa;
use App\Form\EventoType;
use App\Repository\EventoRepository;
use App\Repository\JuegoRepository;
use App\Repository\UserRepository;
use App\Service\PdfService;
use App\Service\TelegramService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventoController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/evento', name: 'app_admin_evento')]
    public function listado(): Response
    {
        return $this->render('Evento/listadoAdmin.html.twig',[]);
    }   

    


    //#[Security(['is_granted' => 'ROLE_ADMIN'])]
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
                    $direct = $this->getParameter('img_evento_directory');
                    
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
                $_GET['evento']=$evento;
                return $this->redirectToRoute('app_new_evento_2',['id' => $evento->getId()]);

            } catch (\Throwable $th) {
                $this->addFlash(
                    'error',
                    '¡No se ha podido crear el evento!'
                );
            }
        }
                
        return $this->render('Evento/new.html.twig',[
            'form' => $form,
        ]);
    }  

    #[Route('/evento/nuevo/2/{id}', name: 'app_new_evento_2')]
    public function eventoNew2(Request $request,EventoRepository $er, JuegoRepository $jr, int $id, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('juegos', ChoiceType::class,[
                //'expanded' => true,
                'required' => true
                ,'label' => 'Selecciona el juego',
                'choices' => $jr->findAll(),
                'multiple' => true,
                // 'choice_label' => function (?Evento $evento) {
                //    return $evento->getImg();
                //},
            ])->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //creo y subo la mesa si no tiene errores
            $juegos = $form->getData('juegos')['juegos'];
            try {
                //$er->setJuegos($juegos, $id);
                $sql='insert into juego_evento(juego_id,evento_id) values ';
                for ($i=0; $i <sizeof($juegos) ; $i++) { 
                    if ($i<sizeof($juegos)-1) {
                        $sql.='('.$juegos[$i]->getId().','.$id.'),';
                    } else {
                        $sql.='('.$juegos[$i]->getId().','.$id.')';
                    }
                }
                
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->execute();

                $this->addFlash(
                    'success',
                    'Juegos Añadidos!'
                );
                return $this->redirectToRoute('app_new_evento_3',['id'=>$id]);

            } catch (\Throwable $th) {
                dd($th);
                $this->addFlash(
                    'error',
                    '¡No se han podido añadir los juegos!'
                );
            }
        }
                
        return $this->render('Evento/new2.html.twig',[
            'form' => $form,
        ]);
    }   

    #[Route('/evento/nuevo/3/{id}', name: 'app_new_evento_3')]
    public function eventoNew3(Request $request, UserRepository $ur, EventoRepository $er, int $id, ManagerRegistry $doctrine, PdfService $ps, TelegramService $ts): Response
    {
        $form = $this->createFormBuilder()
            ->add('participantes', ChoiceType::class,[
                //'expanded' => true,
                'required' => true
                ,'label' => 'Selecciona los participantes',
                'choices' => $ur->findAll(),
                'multiple' => true,
                // 'choice_label' => function (?Evento $evento) {
                //    return $evento->getImg();
                //},
            ])->getForm();

        $form->handleRequest($request);
        ////////////
        //dd($form->getData('participantes')['participantes']);
        if ($form->isSubmitted()) {
            $entityManager = $doctrine->getManager();
            $evento = $er->findOneById($id);
            $partipantes = $form->getData('participantes')['participantes'];
            
            if (sizeof($partipantes)>0) {
                try {
                    //$er->setJuegos($juegos, $id);
                    for ($i=0; $i <sizeof($partipantes) ; $i++) { 
                        $part = new Participa();
                        $part->setUser($partipantes[$i])
                        ->setEvento($evento);
    
                        $entityManager->persist($part);

                        try {
                            $html = $this->renderView('/pdf_generator/invitacion.html.twig',['user'=>$partipantes[$i], 'evento'=>$evento]);

                            $pdf = $ps->generateInvitacionPDF($partipantes[$i],$evento,$html);
                            $ts->sendFile('5301384404',$pdf);
                            //dd('a');
                        } catch (\Throwable $th) {
                            //throw $th;
                            dd($th);
                        }
                    }
                    $entityManager->flush();
    
                    $this->addFlash(
                        'success',
                        'Participantes Añadidos!'
                    );
                    return $this->redirectToRoute('app_admin_evento');
                    
                } catch (\Throwable $th) {
                    dd($th);
                    $this->addFlash(
                        'error',
                        '¡No se han podido añadir los juegos!'
                    );
                }
            }else{
                $this->addFlash(
                    'error',
                    'Debe seleccionar al menos 1 participante!'
                );
            }
        }
                
        return $this->render('Evento/new3.html.twig',[
            'form' => $form,
        ]);
    }   




    #[Route('/evento/editar/1/{id}', name: 'app_edit_evento_1')]
    public function eventoEdit1(Request $request, ManagerRegistry $doctrine, Evento $evento): Response
    {
        $form = $this->createForm(EventoType::class, $evento);
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
                    $direct = $this->getParameter('img_evento_directory');
                    
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
                    'Evento editado!'
                );
                $_GET['evento']=$evento;
                return $this->redirectToRoute('app_edit_evento_2',['id' => $evento->getId()]);

            } catch (\Throwable $th) {
                $this->addFlash(
                    'error',
                    '¡No se ha podido editar el evento!'
                );
            }
        }
                
        return $this->render('Evento/new.html.twig',[
            'form' => $form,
        ]);
    }  

    #[Route('/evento/editar/2/{id}', name: 'app_edit_evento_2')]
    public function eventoEdit2(Request $request, JuegoRepository $jr, Evento $evento, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('juegos', ChoiceType::class,[
                //'expanded' => true,
                'required' => true
                ,'label' => 'Selecciona el juego',
                'choices' => $jr->findAll(),
                'multiple' => true,
                // 'choice_label' => function (?Evento $evento) {
                //    return $evento->getImg();
                //},
            ])->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //creo y subo la mesa si no tiene errores
            $juegos = $form->getData('juegos')['juegos'];
            try {
                //$er->setJuegos($juegos, $id);
                $sql='insert into juego_evento(juego_id,evento_id) values ';
                for ($i=0; $i <sizeof($juegos) ; $i++) { 
                    if ($i<sizeof($juegos)-1) {
                        $sql.='('.$juegos[$i]->getId().','.$evento->getId().'),';
                    } else {
                        $sql.='('.$juegos[$i]->getId().','.$evento->getId().')';
                    }
                }
                
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->execute();

                $this->addFlash(
                    'success',
                    'Juegos Añadidos!'
                );
                return $this->redirectToRoute('app_edit_evento_3',['id'=>$evento->getId()]);

            } catch (\Throwable $th) {
                dd($th);
                $this->addFlash(
                    'error',
                    '¡No se han podido añadir los juegos!'
                );
            }
        }
                
        return $this->render('Evento/new2.html.twig',[
            'form' => $form,
            'evento' => $evento,
        ]);
    }   

    #[Route('/evento/editar/3/{id}', name: 'app_edit_evento_3')]
    public function eventoEdit3(Request $request, UserRepository $ur, EventoRepository $er, Evento $evento, ManagerRegistry $doctrine, PdfService $ps, TelegramService $ts): Response
    {
        $form = $this->createFormBuilder()
            ->add('participantes', ChoiceType::class,[
                //'expanded' => true,
                'required' => true
                ,'label' => 'Selecciona los participantes',
                'choices' => $ur->findAll(),
                'multiple' => true,
                // 'choice_label' => function (?Evento $evento) {
                //    return $evento->getImg();
                //},
            ])->getForm();

        $form->handleRequest($request);
        ////////////
        //dd($form->getData('participantes')['participantes']);
        if ($form->isSubmitted()) {
            $entityManager = $doctrine->getManager();
            $partipantes = $form->getData('participantes')['participantes'];
            
            if (sizeof($partipantes)>0) {
                try {
                    //$er->setJuegos($juegos, $id);
                    for ($i=0; $i <sizeof($partipantes) ; $i++) { 
                        $part = new Participa();
                        $part->setUser($partipantes[$i])
                        ->setEvento($evento);
    
                        $entityManager->persist($part);

                        try {
                            $html = $this->renderView('/pdf_generator/invitacion.html.twig',['user'=>$partipantes[$i], 'evento'=>$evento]);

                            $pdf = $ps->generateInvitacionPDF($partipantes[$i],$evento,$html);
                            $ts->sendFile('5301384404',$pdf);
                            //dd('a');
                        } catch (\Throwable $th) {
                            //throw $th;
                            dd($th);
                        }
                    }
                    $entityManager->flush();
    
                    $this->addFlash(
                        'success',
                        'Participantes Añadidos!'
                    );
                    return $this->redirectToRoute('app_admin_evento');
                    
                } catch (\Throwable $th) {
                    dd($th);
                    $this->addFlash(
                        'error',
                        '¡No se han podido añadir los juegos!'
                    );
                }
            }else{
                $this->addFlash(
                    'error',
                    'Debe seleccionar al menos 1 participante!'
                );
            }
        }
                
        return $this->render('Evento/new3.html.twig',[
            'form' => $form,
            'evento' => $evento,
            
        ]);
    }   
}
