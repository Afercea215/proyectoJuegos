<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;

class SendEmailController extends AbstractController
{
    
    #[Route('/send/email', name: 'app_send_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        try {
            $email = (new TemplatedEmail())
            ->from('andrexillofx@gmail.com')
            ->to('xokasycia@gmail.com')
                    
                    ->subject('prueba correo')
                    ->text('aaaaa prueba')
                    ->htmlTemplate('base.html.twig');

            $mailer->send($email);

            return new Response('enviado');
        } catch (\Throwable $th) {
            return new Response('no enviado');
            //throw $th;
        }
    }
}
