<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Login;
use App\Entity\Mesa;
use App\Entity\User;
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
    public function profile(UserRepository $rp): Response
    {
        return $this->render('User/profile.html.twig', [
            'user' => $this->getUser(),
            'points' => $rp->getPoints($this->getUser()),
        ]);
    }


    function sortByOrder($a, $b) {
        return $a[1] - $b[1];
    }

    #[Route('/api/user/points', name: 'user_points')]
    public function points(UserRepository $ur): JsonResponse
    {
        $users = $ur->findAll();
        $puntos=[];

        foreach ($users as $key => $value) {
           $puntos[] = [
            "user" => $value,
            "points" => $ur->getPoints($value)
        ];
        }

        for ($i = 0; $i < sizeOf($puntos); $i++) {
            for ($j = $i + 1; $j < sizeOf($puntos); $j++) {
                if ($puntos[$i]['points'] > $puntos[$j]['points']) {
                    $temp = $puntos[$i];
                    $puntos[$i] = $puntos[$j];
                    $puntos[$j] = $temp;
                }
            }
        }
        
        return $this->json(
            $puntos
        );
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

    #[Route('/profile/eventos', name: 'app_profile_evento')]
    public function profileEventos(EventoRepository $rp): Response
    {
        $eventos = $rp->getEventosUser($this->getUser());
        $eventosFuturos = [];
        $eventosPasados = [];
        $fecha = new DateTime();

        foreach ($eventos as $key => $value) {
            if ($value->getFecha()<$fecha) {
                $eventosPasados[]=$value;
            }else{
                $eventosFuturos[]=$value;
            }
        }

        return $this->render('User/profileEventos.html.twig', [
            'eventosFuturos' => $eventosFuturos,
            'eventosPasados' => $eventosPasados,
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
