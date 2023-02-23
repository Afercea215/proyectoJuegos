<?php
namespace App\Service;

use App\Entity\Evento;
use App\Entity\User;
use Dompdf\Dompdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

class PdfService //extends AbstractController
{
    private $params;
    private $pdf;

    function __construct(ParameterBagInterface $params, Pdf $pdf)
    {
        $this->params = $params;
        $this->pdf = $pdf;
    }

    public function getParam($name)
    {
        return $this->params->get($name);
    }

    public function generatePDF(string $html, string $nombre, ?array $params = [], ?array $css = [])
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        file_put_contents($nombre, $dompdf->output());
    }
    public function generateInvitacionPDF(User $user, Evento $evento, $html)
    {
        $this->generatePDF($html,'pdf/'.'invitacion-'.$user->getUserIdentifier().'-'.$evento->getNombre().'.pdf');

        return 'pdf/'.'invitacion-'.$user->getUserIdentifier().'-'.$evento->getNombre().'.pdf';
    }
}


?>