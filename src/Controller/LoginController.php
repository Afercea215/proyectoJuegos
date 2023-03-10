<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        //dd($lastUsername);
        return $this->render('login/login.html.twig', [
            'controller_name' => 'LoginController',
             'last_username' => $lastUsername,
             'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Security $security)
    {
        $security->logout();
        //return $this->redirect($this->generateUrl('homepage'));
        return $this->redirectToRoute('home');
        // controller can be blank: it will never be called!
        //throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
