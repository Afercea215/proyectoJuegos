<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Login;
use App\Entity\Mesa;
use App\Repository\EventoRepository;
use App\Repository\ReservaRepository;
use App\Repository\UserRepository;
use App\Service\PdfService;
use App\Service\TelegramService;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function index(TelegramService $ts, PdfService $ps, UserRepository $ur, EventoRepository $er)
    {
       
    }

    #[Route('/profile/', name: 'profile')]
    public function profile(): JsonResponse
    {
        return $this->json([
        ]);
    }

    #[Route('/profile/reservas', name: 'app_profile_reserva')]
    public function profileReservas(ReservaRepository $rp): Response
    {
        $reservas = $rp->getReservasUser($this->getUser());
        $reservasFuturas = [];
        $reservasPasadas = [];
        $fecha = new DateTime();

        foreach ($reservas as $key => $value) {
            if ($value->getFecha()<$fecha) {
                $reservasPasadas[]=$value;
            }else{
                $reservasFuturas[]=$value;
            }
        }

        return $this->render('User/profileReservas.html.twig', [
            'reservasFuturas' => $reservasFuturas,
            'reservasPasadas' => $reservasPasadas,
        ]);
    }
    
    #[Route('/a', name: 'a')]
    public function login(Request $request):Response
    {
        $login = new login();
        $login->setUserName("andres");

        $form = $this->createFormBuilder($login)
                    ->add('userName', TextType::class,['label' => 'Nombre de usuario'])
                    ->add('contrasena', PasswordType::class,['label' => 'Contraseña'])
                    ->add('mesa', EntityType::class,[
                        'class' => Mesa::class,
                        'label' => 'Mesa',
                        ])
                    ->add('submit', SubmitType::class,['label' => 'Iniciar sesion'])
                    ->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
            //return $this->redirectToRoute('user');
        }

        return $this->render('login.html.twig',[
            'form'=>$form,
        ]);
    }
}
