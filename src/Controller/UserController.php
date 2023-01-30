<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Login;
use App\Entity\Mesa;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
    #[Route('/login', name: 'login')]
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
