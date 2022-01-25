<?php

// src/Controller/CategoriaController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Categoria;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriaController extends AbstractController
{
    /**
     * @Route("/Categoria/listaCategoria/{id_producto}", name="producto_listaCategoria")
     */
    public function listaCategoria(ManagerRegistry $doctrine, int $id_producto): Response
    {
        $entityManager = $doctrine->getManager();

        $producto = $entityManager->getRepository(Producto::class)->find($id_producto);

        if (!$producto) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $categoria = $producto->getCategoria();

        return $this->render('categoria/listaCategoria.html.twig', ['categoria' => $categoria, 'producto' => $producto]);
    }

    /**
     * @Route("/Categoria/insertaCategoria/{descripcion}", name="categoria_insertaCategoria")
     */
    public function insertaProducto(ManagerRegistry $doctrine, string $descripcion): Response
    {
        $entityManager = $doctrine->getManager();

        $categoria = new Categoria();
        $categoria->setDescripcion($descripcion);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($categoria);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new categoria with id '.$categoria->getId());
    }

}