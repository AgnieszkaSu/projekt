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
     * Add new product.
     *
     * @param Request $request    HTTP request
     * @param ProductRepository $repository Type repository
     *
     * @return Response HTTP response
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

            $this->addFlash('success', 'message.created');

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
     * Add new product to category.
     *
     * @param Request $request    HTTP request
     * @param ProductRepository $repository Type repository
     * @param Type $type Type
     *
     * @return Response HTTP response
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

            $this->addFlash('success', 'message.created');

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
     * Edit product.
     *
     * @param Request $request    HTTP request
     * @param Product $product    Product entity
     * @param ProductRepository $repository Product repository
     *
     * @return Response HTTP response
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

            $this->addFlash('success', 'message.updated');

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
    * Delete product.
    *
    * @param Request $request    HTTP request
    * @param Product $product       Product entity
    * @param ProductRepository $repository Product repository
    *
    * @return Response HTTP response
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
            $this->addFlash('success', 'message.updated');

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
