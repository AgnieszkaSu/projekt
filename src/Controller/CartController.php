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
     *      "/add/{id}",
     *      requirements={"id": "[1-9]\d*"},
     *      name="cart_add",
     *      methods={"GET"},
     * )
     */
    public function add(Request $request, Product $product): Response
    {
        $session = $request->getSession();

        // TU JEST BŁĄD! NIE WIEM GDZIE TO ZROBIĆ
        $cartBag = new NamespacedAttributeBag('cart');
        $cartBag->setName('cart');
        $session->registerBag($cartBag);

        $cart = $session->getBag('cart');
        $cart->add('cart', $product);
        return $this->forward('App\Controller\TypeController::view', ['type' => $product->getType()]);
    }
}