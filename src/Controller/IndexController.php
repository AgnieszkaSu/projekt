<?php
/**
 * Home page controller.
 */

namespace App\Controller;

use App\Repository\TypeRepository;
use App\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController.
 */
class IndexController extends AbstractController
{
    /**
     * Index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/")
     */
    public function index(TypeRepository $repository): Response
    {
        return $this->render(
            'product_list.html.twig',
            ['data' => $repository->findAll()]
        );
    }

    /**
     * Index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/product/{id}",
     *     name="product_view",
     *     requirements={"id": "0*[1-9]\d*"},
     * )
     */
    public function product(Type $type): Response
    {
        return $this->render(
            'product.html.twig',
            [
                'item' => $type,
                'data' => $type->getProducts()
            ]
        );
    }
}
