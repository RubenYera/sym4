<?php

// src/Controller/AsignaturaController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Asignatura;
use App\Entity\Alumno;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AsignaturaController extends AbstractController
{
    /**
     * @Route("/Asignatura/listaAsignaturas/", name="asignatura_listaAsignaturas")
     */
    public function listaAsignaturas(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $asignaturas = $entityManager->getRepository(Asignatura::class)->findAll();

        if (!$asignaturas) {
            throw $this->createNotFoundException(
                'No hay asignaturas'
            );
        }

        return $this->render('alumno/listaAsignaturas.html.twig', ['asignaturas' => $asignaturas]);
    }

    /**
     * @Route("/Asignatura/listaAsignatura/{id}", name="asignatura_listaAsignatura")
     */
    public function listaAsignatura(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        $asignatura = $entityManager->getRepository(Asignatura::class)->find($id);

        if (!$asignatura) {
            throw $this->createNotFoundException(
                'No hay asignatura con id '.$id
            );
        }

        return $this->render('alumno/listaAsignatura.html.twig', ['asignatura' => $asignatura]);
    }

    /**
     * @Route("/Asignatura/insertaAsignatura/{descripcion}", name="asignatura_insertaAsignatura")
     */
    public function insertaAsignatura(ManagerRegistry $doctrine, string $descripcion): Response
    {
        $entityManager = $doctrine->getManager();

        $asignatura = new Asignatura();
        $asignatura->setDescripcion($descripcion);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($asignatura);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Has guardado una nueva asignatura con id '.$asignatura->getId());
    }
    
    /**
     * @Route("/Asignatura/asociaAsignatura/{id_Asignatura}/{id_Alumno}", name="asignatura_asociaAsignatura")
     */
    public function asociaAsignatura(ManagerRegistry $doctrine, int $id_Asignatura, int $id_Alumno): Response
    {
        $entityManager = $doctrine->getManager();

        $asignatura = $entityManager->getRepository(Asignatura::class)->find($id_Asignatura);
        $alumno = $entityManager->getRepository(Alumno::class)->find($id_Alumno);
        $asignatura->addAlumno($alumno);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($asignatura);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Se ha actualizado la asignatura con id '.$asignatura->getId());
    }
}