<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Form\MesaType;
use App\Repository\JuegoRepository;
use App\Repository\MesaRepository;
use App\Repository\ReservaRepository;
use App\Repository\TramoRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReservaController extends AbstractController
{
    #[Route('/reservar', name: 'app_reserva')]
    public function reserva(TramoRepository $tr): Response
    {
        return $this->render('Reserva/reservar.html.twig', [
            'tramos' => $tr->findAll(),
        ]);
    }

    #[Route('api/reserva', name: 'api_newMesa', methods:'POST')]
    public function update(Request $request, UserRepository $ur, TramoRepository $tr, JuegoRepository $jr, MesaRepository $mr, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $data = json_decode($request->getContent(),true);
        //dd($data['tramo']);

        $reserva = new Reserva();
        $reserva->setTramo($tr->findOneById($data['tramo']));
        $reserva->setJuego($jr->findOneById($data['juego']));
        $reserva->setMesa($mr->findOneById($data['mesa']));
        $reserva->setUser($ur->findOneById(1));
        
        $fecha = new DateTime($data['fecha']);
        $reserva->setFecha($fecha);
        //dd($reserva);

        try {
            $entityManager->persist($reserva);
            $entityManager->flush();
        } catch (\Throwable $th) {
            return $this->json([
                'error' => json_encode($th),
                'message' => 'No se se puede actualizar la mesa con la id : '
            ],400);
        }

        return $this->json([
            'message' => 'Se ha creado la reserva ',
        ],201);
    }
}
