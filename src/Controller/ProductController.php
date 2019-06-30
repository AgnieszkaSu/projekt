<?php
/**
 * Product controller.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Type;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ProductController.
 *
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductRepository            $repository Type repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new/",
     *     name="product_new",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function new(Request $request, ProductRepository $repository): Response
    {
        $product = new Product();

        $form = $this->createForm(
            ProductType::class,
            $product,
            [
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($product);

            $this->addFlash('success', 'Utworzono produkt');

            return $this->redirectToRoute('type_view', array('id' => $product->getType()->getId()));
        }

        return $this->render(
            'product/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductRepository            $repository Type repository
     * @param \App\Repository\Type type Type
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new/{id}/",
     *     requirements={"id": "0*[1-9]\d*"},
     *     name="product_new_with_id",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function newWithId(Request $request, ProductRepository $repository, Type $type): Response
    {
        $product = new Product();
        $product->setType($type);
        $form = $this->createForm(
            ProductType::class,
            $product,
            [
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($product);

            $this->addFlash('success', 'Utworzono produkt');

            return $this->redirectToRoute('type_view', array('id' => $product->getType()->getId()));
        }

        return $this->render(
            'product/new_with_id.html.twig',
            [
                'form' => $form->createView(),
                'type_id' => $type->getId(),
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Product                       $product    Product entity
     * @param \App\Repository\ProductRepository         $repository Product repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit/",
     *     requirements={"id": "[1-9]\d*"},
     *     name="product_edit",
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function edit(Request $request, Product $product, ProductRepository $repository): Response
    {
        $form = $this->createForm(
            ProductType::class,
            $product,
            [
                'method' => 'PUT',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($product);

            $this->addFlash('success', 'Zakutalizowano produkt');

            return $this->redirectToRoute('type_view', array('id' => $product->getType()->getId()));
        }

        return $this->render(
            'product/edit.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }

    /**
    * Delete action.
    *
    * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
    * @param \App\Entity\Product                          $product       Product entity
    * @param \App\Repository\ProductRepository            $repository Product repository
    *
    * @return \Symfony\Component\HttpFoundation\Response HTTP response
    *
    * @throws ORMException
    * @throws OptimisticLockException
    *
    * @Route(
    *     "/{id}/delete/",
    *     methods={"GET", "DELETE"},
    *     requirements={"id": "[1-9]\d*"},
    *     name="product_delete",
    * )
    *
    * @IsGranted("MANAGE")
    */
    public function delete(Request $request, Product $product, ProductRepository $repository): Response
    {
        $form = $this->createForm(ProductType::class, $product, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($product);
            $this->addFlash('success', 'Usunięto produkt');

            return $this->redirectToRoute('type_view', array('id' => $product->getType()->getId()));
        }

        return $this->render(
            'product/delete.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }
}
