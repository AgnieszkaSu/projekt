<?php
/**
 * Home page controller.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderProducts;
use App\Form\CartType;
use App\Form\OrderType;
use App\Repository\ProductRepository;
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
     *      methods={"GET", "PUT"}
     * )
     */
    public function view(Request $request, ProductRepository $repository): Response
    {
        $oldCart = $request->getSession()->get('cart');
        $cart = [];
        if (isset($oldCart)) {
            foreach ($oldCart as $id => $amount) {
                $product = $repository->findOneBy(['id' => $id]);
                if (!$product) {
                    continue;
                }
                $item = new OrderProducts();
                $item->setProduct($product);
                $item->setQuantity($amount);
                $cart[] = $item;
            }
        }

        dump($cart);
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO: save id DB
            return $this->redirectToRoute('type_view', array('id' => $product->getType()->getId()));
        }

        return $this->render(
            'cart_.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
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
     *      "/add/{id}/",
     *      requirements={"id": "[1-9]\d*"},
     *      name="cart_add",
     *      methods={"GET"},
     * )
     */
    public function add(Request $request, Product $product): Response
    {
        $oldCart = $request->getSession()->get('cart');
        if (!$oldCart) {
            $oldCart = [];
        }
        $id = $product->getId();
        if (array_key_exists($id, $oldCart)) {
            $oldCart[$id] += 1;
        } else {
            $oldCart[$id] = 1;
        }

        $request->getSession()->set('cart', $oldCart);

        $this->addFlash('success', 'Product added to cart.');

        return $this->redirect($request->headers->get('referer'));
    }
}
