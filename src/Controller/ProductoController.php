<?php

// src/Controller/ProductoController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Producto;
use App\Entity\Categoria;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductoController extends AbstractController
{
    /**
     * @Route("/Producto/listaProducto/{id_categoria}", name="producto_listaProducto")
     */
    public function listaProducto(ManagerRegistry $doctrine, int $id_categoria): Response
    {
        $entityManager = $doctrine->getManager();

        $categoria = $entityManager->getRepository(Categoria::class)->find($id_categoria);

        if (!$categoria) {
            throw $this->createNotFoundException(
                'No categoria found for id '.$id_categoria
            );
        }

        $productos = $categoria->getProductos();

        if (!$productos) {
            throw $this->createNotFoundException(
                'No product found for categoria '.$categoria->getDescripcion()
            );
        }

        return $this->render('producto/listaProducto.html.twig', ['productos' => $productos,'categoria' => $categoria]);
    }

    /**
     * @Route("/Producto/insertaProducto/{precio}/{id_categoria}", name="producto_insertaProducto")
     */
    public function insertaProducto(ManagerRegistry $doctrine, int $precio, int $id_categoria): Response
    {
        $entityManager = $doctrine->getManager();

        $categoria = $entityManager->getRepository(Categoria::class)->find($id_categoria);

        $producto = new Producto();
        $producto->setPrecio($precio);
        $producto->setCategoria($categoria);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($producto);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new producto with id '.$producto->getId());
    }

}