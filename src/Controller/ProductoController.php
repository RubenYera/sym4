<?php

// src/Controller/ProductoController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\Type\ProductoType;
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
     * @Route("/producto/listaProductos", name="producto_listaProductos")
     */
    public function listaProductos(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $productos = $entityManager->getRepository(Producto::class)->findAll();

        if (!$productos) {
            throw $this->createNotFoundException(
                'No product found for categoria '.$categoria->getDescripcion()
            );
        }

        return $this->render('producto/listaProductos.html.twig', ['productos' => $productos]);
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

    /**
     * @Route("/Producto/borrarProducto/{id}", name="producto_borrarProducto")
     */
    public function borrarProducto(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();

        $producto = $entityManager->getRepository(Producto::class)->find($id);
        $entityManager->remove($producto);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('producto_listaProductos');
    }

    /**
     * @Route("/Producto/Formulario", name="producto_formularioProducto")
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        // just set up a fresh $task object (remove the example data)
        $producto = new Producto();

        $form = $this->createForm(ProductoType::class, $producto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $producto = $form->getData();
            $categoria = $entityManager->getRepository(Categoria::class)->find(1);
            $producto->setCategoria($categoria);

            $entityManager->persist($producto);
            $entityManager->flush();

            return $this->redirectToRoute('producto_listaProductos');
        }

        return $this->renderForm('producto/new.html.twig', [
            'form' => $form,
        ]);
    }

}