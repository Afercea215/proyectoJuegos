<?php
namespace App\Service;

use App\Entity\Evento;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private $params;
    private $correo;
    private $mailer;

    function __construct(ParameterBagInterface $params, MailerInterface $mailer)
    {
        $this->params = $params;
        $this->mailer = $mailer;
        $this->correo = $this->getParam('correo') ;
    }

    public function getParam($name)
    {
        return $this->params->get($name);
        // ...
    }

    public function sendEmail(User $user,string $subject,string $htmlTemplate,?array $context=null){
        try {
            $email = (new TemplatedEmail())
            ->from($this->correo)
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context($context);
            
            $this->mailer->send($email);
    
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function sendWelcomeEmail(User $user)
    {
        $this->sendEmail(
            $user,
            'Bienvenido a Xokas y cia!',
            'send_email/welcome.html.twig',
            ['usuario'=>$user]
        );
    }

    public function sendInvitacionEvento(User $user,Evento $evento)
    {
        $this->sendEmail(
            $user,
            'Invitacion evento '+$evento->getNombre()+'!',
            'send_email/invitacionEvento.html.twig',
            ['usuario'=>$user, 'evento'=>$evento]
        );
    }
}


?>