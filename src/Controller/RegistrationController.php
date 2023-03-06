<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //$user->setRoles([]);
            $user->setRoles([]);
            $user->setNombre($form->get('nombre')->getData());
            $user->setEmail($form->get('email')->getData());
            $user->setApellidos($form->get('apellidos')->getData());
            $user->setTelegramUser($form->get('telegramUser')->getData());
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            // do anything else you need here, like send an email
            //dd($user);
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/profile/edit', name: 'app-update_profile')]
    public function editProfile(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user2 = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user2);
        $form->handleRequest($request);
        $user = new User();
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            //$user->setRoles([]);
            $user->setId($user2->id);
            $user->setNombre($form->get('nombre')->getData());
            $user->setEmail($form->get('email')->getData());
            $user->setApellidos($form->get('apellidos')->getData());
            $user->setTelegramUser($form->get('telegramUser')->getData());
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            // do anything else you need here, like send an email
            //dd($user);
            return $this->redirectToRoute('home');
        }

        return $this->render('User/profileEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
