<?php

namespace App\Controller;

use App\Entity\Mesa;
use App\Form\MesaType;
use App\Repository\MesaRepository;
use App\Repository\TramoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//#[Route('/api', name: 'api_')]
class MesaController extends AbstractController
{

    #[Route('/mesas', name: 'app_mesa')]
    public function index(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(MesaType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //creo y subo la mesa si no tiene errores
            $mesa = ($form->getData()); 
            $entityManager = $doctrine->getManager();
            
            //$errors = $validator->validate($mesa);
            
            //if(count($errors)==0){
                try {
                    $entityManager->persist($mesa);
                    $entityManager->flush();
                    $this->addFlash(
                        'success',
                        '¡Mesa creada!'
                    );
                } catch (\Throwable $th) {
                    $this->addFlash(
                        'error',
                        '¡No se ha podido crear la mesa!'
                    );
                    //throw $th;
                }
            //}else{
            //}
        }

        return $this->render('Mesa/gestionMesas.html.twig', [
            'form' => $form,
        ]);
    }
    
    /* #[Route('/mesa/{id}', name: '_mesa_getAll', methods:'GET')]
    public function get(MesaRepository $mr, int $id=null): JsonResponse
    {
        if ($id) {

            $mesa = $mr->find($id);
            if ($mesa) {
                return $this->json([
                    'obj' => $mesa->objToArray(),
                ],200);
            }
            
            return $this->json([
                'message' => 'La mesa con la id '.$id.' no existe'
            ],400);

        }

        return $this->json([
            'obj' => $mr->getAllArray()
        ],200);
    }

    
    #[Route('/mesa/{id}', name: '_mesa_update', methods:'PUT')]
    public function update(Request $request, int $id, MesaRepository $mr, ManagerRegistry $doctrine): JsonResponse
    {
        $mesa = $mr->find($id);

        if ($mesa) {
            $entityManager = $doctrine->getManager();

            $mesa->setLongitud($request->request->get('longitud'));
            $mesa->setAncho($request->request->get('ancho'));
            $mesa->setX($request->request->get('x'));
            $mesa->setY($request->request->get('y'));

            try {
                $entityManager->persist($mesa);
                $entityManager->flush();
            } catch (\Throwable $th) {
                return $this->json([
                    'message' => 'No se se puede actualizar la mesa con la id : '.$id
                ],400);
            }

            return $this->json([
                'message' => 'Se ha actualizado la mesa con la id : '.$mesa->getId()
            ],201);
        }
        return $this->json([
            'message' => 'No se ha encontrado la mesa con la id : '.$id
        ],400);
    } */
    
}
