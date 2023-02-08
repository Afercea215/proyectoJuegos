<?php
namespace App\Service;

use App\Entity\Evento;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

class PdfService //extends AbstractController
{
    private $params;
    private $templating;

    function __construct(ParameterBagInterface $params, ContainerInterface $container)
    {
        $this->params = $params;
        $this->templating = $container->get('templating');
    }

    public function getParam($name)
    {
        return $this->params->get($name);
        // ...
    }

    public function generatePDF(string $twigPath, ?array $params = [], ?array $css = []) : Response
    {
        $params['css']=$css;
        $html =  $this->templating->renderView($twigPath, $params);
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
}


?>