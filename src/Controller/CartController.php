<?php
/**
 * Home page controller.
 */

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController.
 *
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *      "/",
     *      name="cart_view",
     * )
     */
    public function view(Request $request): Response
    {
        $cart = $request->getSession()->get('cart');
        return $this->render(
            'cart.html.twig',
            [
                'cart' => $cart,
            ]
        );
    }

    /**
     * Add action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *      "/add/{id}",
     *      requirements={"id": "[1-9]\d*"},
     *      name="cart_add",
     *      methods={"GET"},
     * )
     */
    public function add(Request $request, Product $product): Response
    {
        $oldCart = $request->getSession()->get('cart');
        $oldCart[] = $product;
        $request->getSession()->set('cart', $oldCart);

        $this->addFlash('success', 'Product added to cart.');

        return $this->redirect($request->headers->get('referer'));
    }
}
