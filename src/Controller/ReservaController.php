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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReservaController extends AbstractController
{
    #[IsGranted("ROLE_USER")]
    #[Route('/reservar', name: 'app_reserva')]
    public function reserva(TramoRepository $tr): Response
    {
        return $this->render('Reserva/reservar.html.twig', [
            'tramos' => $tr->findAll(),
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin/reservas/{fecha}', name: 'app_admin_reserva')]
    public function adminReserva(ReservaRepository $rp, string $fecha=null): Response
    {
        if ($fecha==null) {
            $fecha = new DateTime();
        }else{
            $fecha = new DateTime($fecha);
        }

        return $this->render('Reserva/admin.html.twig', [
            'reservas' => $rp->findBy(['fecha'=>$fecha]),
            'fecha' => $fecha,
            'fechaActual' => date_time_set(new DateTime(),0,0,0,0),
        ]);
    }

    

    #[IsGranted("ROLE_USER")]
    #[Route('api/reserva/cancelar/{id}', name: 'api_cancela_reserva', methods:'PUT')]
    public function cancelar(Reserva $reserva=null, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $reserva->setFechaAnul(new DateTime());
        
        try {
            $entityManager->persist($reserva);
            $entityManager->flush();
        } catch (\Throwable $th) {

        }

        return $this->json([
            'message' => 'Se ha cancelado la reserva ',
        ],200);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('api/reservas/{id}/{presentado}', name: 'api_presentado_reserva', methods:'PUT')]
    public function presentado(ManagerRegistry $doctrine, Reserva $reserva, string $presentado): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $reserva->setFechaAnul(new DateTime());
        $reserva->setPresentado($presentado=='true'?true:false);
        
        try {
            $entityManager->persist($reserva);
            $entityManager->flush();
        } catch (\Throwable $th) {
            dd($th);
        }

        return $this->json([
            'message' => 'Se ha actualizado la reserva ',
        ],200);
    }

    #[IsGranted("ROLE_ADMIN")]
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
