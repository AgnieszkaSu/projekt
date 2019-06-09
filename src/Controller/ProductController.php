<?php
/**
 * Product controller.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Type;
use App\Form\ProductType;
use App\Form\TypeType;
use App\Repository\ProductRepository;
use App\Repository\TypeRepository;
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
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\TypeRepository            $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/", name="product_index")
     */
    public function index(Request $request, TypeRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            // $request->query->getInt('page', 1),
            $this->get('request_stack')->getMasterRequest()->query->getInt('page', 1),
            10
        );

        return $this->render(
            'product_list.html.twig',
            [
                'data' => $repository->findAll(),
                'pagination' => $pagination
            ]
        );
    }

    /**
     * Product action.
     *
     * @param \App\Repository\Type type Type
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="product_view",
     *     requirements={"id": "0*[1-9]\d*"},
     * )
     */
    public function view(Type $type): Response
    {
        return $this->render(
            'product.html.twig',
            [
                'item' => $type,
                'data' => $type->getProducts()
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\TypeRepository            $repository Type repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new_type",
     *     name="product_new_type",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function newType(Request $request, TypeRepository $repository): Response
    {
        $type = new Type();

        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($type);

            $this->addFlash('success', 'Product type created.');

            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/new_type.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductRepository            $repository Type repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     name="product_new",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function new(Request $request, ProductRepository $repository): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($product);

            $this->addFlash('success', 'Product created.');

            return $this->redirectToRoute('product_view', array('id' => $product->getType()->getId()));
        }

        return $this->render(
            'product/new.html.twig',
            ['form' => $form->createView()]
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new/{id}",
     *     name="product_new_with_id",
     *     requirements={"id": "0*[1-9]\d*"},
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function newWithId(Request $request, ProductRepository $repository, Type $type): Response
    {
        $product = new Product();
        $product->setType($type);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($product);

            $this->addFlash('success', 'Product created.');

            return $this->redirectToRoute('product_view', array('id' => $product->getType()->getId()));
        }

        return $this->render(
            'product/new_with_id.html.twig',
            [
                'form' => $form->createView(),
                'type_id' => $type->getId()
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Task                          $task       Task entity
     * @param \App\Repository\TaskRepository            $repository Task repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="product_edit",
     * )
     */
    public function edit(Request $request, Product $product, ProductRepository $repository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($product);

            $this->addFlash('success', 'Product updated successfully.');

            return $this->redirectToRoute('product_view', array('id' => $product->getType()->getId()));
        }

        return $this->render(
            'product/edit.html.twig',
            [
                'form' => $form->createView(),
                'type_id' => $product->getType()->getId(),
                'product' => $product,
            ]
        );
    }
}
