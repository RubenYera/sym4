<?php

// src/Controller/AlumnoController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Alumno;
use App\Entity\Asignatura;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlumnoController extends AbstractController
{
    /**
     * @Route("/Alumno/listaAlumnos/", name="alumno_listaAlumnos")
     */
    public function listaAlumnos(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $alumnos = $entityManager->getRepository(Alumno::class)->findAll();

        if (!$alumnos) {
            throw $this->createNotFoundException(
                'No hay alumnos'
            );
        }

        return $this->render('alumno/listaAlumnos.html.twig', ['alumnos' => $alumnos]);
    }

    /**
     * @Route("/Alumno/listaAlumno/{id}", name="alumno_listaAlumno")
     */
    public function listaAlumno(ManagerRegistry $doctrine,int $id): Response
    {
        $entityManager = $doctrine->getManager();

        $alumno = $entityManager->getRepository(Alumno::class)->find($id);

        if (!$alumno) {
            throw $this->createNotFoundException(
                'No hay alumnos con id '.$id
            );
        }

        return $this->render('alumno/listaAlumno.html.twig', ['alumno' => $alumno]);
    }

    /**
     * @Route("/Alumno/insertaAlumno/{nombre}", name="alumno_insertaAlumno")
     */
    public function insertaAlumno(ManagerRegistry $doctrine, string $nombre): Response
    {
        $entityManager = $doctrine->getManager();

        $alumno = new Alumno();
        $alumno->setNombre($nombre);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($alumno);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new alumno with id '.$alumno->getId());
    }

    /**
     * @Route("/Alumno/asociaAlumno/{id_Asignatura}/{id_Alumno}", name="alumno_asociaAlumno")
     */
    public function asociaAlumno(ManagerRegistry $doctrine, int $id_Asignatura, int $id_Alumno): Response
    {
        $entityManager = $doctrine->getManager();

        $asignatura = $entityManager->getRepository(Asignatura::class)->find($id_Asignatura);
        $alumno = $entityManager->getRepository(Alumno::class)->find($id_Alumno);
        $alumno->addAsignatura($asignatura);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($alumno);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Se ha actualizado el alumno con id '.$alumno->getId());
    }

}