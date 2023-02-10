<?php
namespace App\Service;

use App\Entity\Evento;
use App\Entity\User;
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
    private $templating;
    private $pdf;

    function __construct(ParameterBagInterface $params, ContainerInterface $container, Pdf $pdf)
    {
        $this->params = $params;
        $this->templating = $container->get('templating');
        $this->pdf = $pdf;
    }

    public function getParam($name)
    {
        return $this->params->get($name);
        // ...
    }

    public function generatePDF(string $twigPath, string $titulo, ?array $params = [], ?array $css = []) : Response
    {
        $pdf = $this->pdf;
        $params['css']=$css;
        $html =  $this->templating->renderView($twigPath, $params);
        
        return new PdfResponse(
            $pdf->getOutputFromHtml($html, [
                'images' => true,
                'page-size' => 'A4',
                'viewport-size' => '1280x1024',
            ]),
            $titulo
        );

        /* $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        ); */
    }
}


?>