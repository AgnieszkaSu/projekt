<?php
/**
 * Product controller.
 */

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
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
     *     "/new",
     *     name="product_new",
     *     methods={"GET", "POST"},
     * )
     */
    public function new(Request $request, TypeRepository $repository): Response
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($type);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/new.html.twig',
            ['form' => $form->createView()]
        );
    }
}
