<?php

namespace App\Controller;

use App\Repository\TareaRepository;
use App\Entity\Tarea;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class TareaController extends AbstractController
{
    #[Route('/', name: 'app_listado_tarea')]
    public function listado(TareaRepository $tareaRepository): Response
    {
        $tareas = $tareaRepository->findAll();
        return $this->render('tarea/listado.html.twig', [
            'tareas' => $tareas,
        ]);
    }

    #[Route('/tarea/crear', name: 'app_crear_tarea')]
    public function crear(Request $request, ManagerRegistry $doctrine): Response
    {
        $tarea = new Tarea();
        $descripcion = $request->request->get('descripcion',null);

        if ($descripcion !== null){
            if (!empty($descripcion)){
                $em = $doctrine->getManager();
                $tarea->setDescripcion($descripcion);
                $em->persist($tarea);
                $em->flush();
                $this->addFlash('success', 'Tarea creada');
                return $this->redirectToRoute('app_listado_tarea');
            }else{
                $this->addFlash('warning', 'El campo descripcion es obligatorio');
            }
        }

        return $this->render('tarea/crear.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    #[Route('/tarea/editar/{id}', name: 'app_editar_tarea')]
    public function editar(int $id, TareaRepository $tareaRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $tarea = $tareaRepository->findOneById($id);

        if(null === $tarea){
            throw $this->createNotFoundException();
        }

        $descripcion = $request->request->get('descripcion',null);

        if ($descripcion !== null){
            if (!empty($descripcion)){
                $em = $doctrine->getManager();
                $tarea->setDescripcion($descripcion);
                $em->persist($tarea);
                $em->flush();
                $this->addFlash('success', 'Tarea editada');
                return $this->redirectToRoute('app_listado_tarea');
            }else{
                $this->addFlash('warning', 'El campo descripcion es obligatorio');
            }
        }
        return $this->render('tarea/editar.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    #[Route('/tarea/eliminar/{id}', name: 'app_eliminar_tarea', requirements:["id" => "\d+"])]
    public function eliminar(Tarea $tarea,ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($tarea);
        $em->flush();
        $this->addFlash('success', 'Tarea eliminada');
        return $this->redirectToRoute('app_listado_tarea');
    }

    #[Route('/tarea/editar/{id}', name: 'app_editar_tarea_con_params_convert', requirements:["id" => "\d+"])]
    public function editarConParamsConvert(Tarea $tarea, TareaRepository $tareaRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $tarea = $tareaRepository->findOneById($id);

        if(null === $tarea){
            throw $this->createNotFoundException();
        }

        $descripcion = $request->request->get('descripcion',null);

        if ($descripcion !== null){
            if (!empty($descripcion)){
                $em = $doctrine->getManager();
                $tarea->setDescripcion($descripcion);
                $em->persist($tarea);
                $em->flush();
                $this->addFlash('success', 'Tarea editada');
                return $this->redirectToRoute('app_listado_tarea');
            }else{
                $this->addFlash('warning', 'El campo descripcion es obligatorio');
            }
        }
        return $this->render('tarea/editar.html.twig', [
            'tarea' => $tarea,
        ]);
    }
}
