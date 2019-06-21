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
use App\Repository\ShippingMethodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
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
    public function view(Request $request, ProductRepository $repository, ShippingMethodRepository $shippingrepository): Response
    {
        $order = new Order();

        $oldCart = $request->getSession()->get('cart');
        $shippingId = $request->getSession()->get('shipping');
        if ($shippingId) {
            $order->setShippingMethod($shippingrepository->findOneBy(['id' => $shippingId]));
        }
        $sum = 0;
        if (isset($oldCart)) {
            foreach ($oldCart as $id => $amount) {
                $product = $repository->findOneBy(['id' => $id]);
                if (!$product) {
                    continue;
                }
                $item = new OrderProducts();
                $item->setProduct($product);
                $price = $product->getPrice();
                $item->setPrice($price);
                $item->setQuantity($amount);
                $order->addOrderProduct($item);
                $sum += $price * $amount;
            }
        }

        $form = $this->createForm(OrderType::class, $order, ['method' => 'PUT']);
        $form->get('sum')->setData($sum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = [];
            foreach ($order->getOrderProducts() as $product) {
                $quantity = $product->getQuantity();
                if ($quantity) {
                    $cart[$product->getProduct()->getId()] = $quantity;
                }
            }
            $request->getSession()->set('cart', $cart);
            if ($order->getShippingMethod()) {
                $request->getSession()->set('shipping', $order->getShippingMethod()->getId());
            }
            $this->addFlash('success', 'Cart updated.');
            $this->addFlash('warning', 'TODO: save order into database.');
            // TODO: save id DB
            return $this->redirectToRoute('cart_view');
        }

        return $this->render(
            'cart_.html.twig',
            [
                'form' => $form->createView(),
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
