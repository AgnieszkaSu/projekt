<?php
/**
 * Home page controller.
 */

namespace App\Controller;

use App\Entity\DeliveryAddress;
use App\Entity\Order;
use App\Entity\OrderProducts;
use App\Entity\Product;
use App\Form\CartType;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ShippingMethodRepository;
use App\Repository\PaymentMethodRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
     * @param Request $request HTTP request
     * @param OrderRepository $repository Order repository
     * @param ProductRepository $productrepository Product repository
     * @param ShippingMethodRepository $shippingrepository Shipping method repository
     * @param PaymentMethodRepository $paymentrepository Payment method repository
     * @param Security $security Security
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *      "/",
     *      name="cart_view",
     *      methods={"GET", "PUT"}
     * )
     */
    public function view(Request $request, OrderRepository $repository, ProductRepository $productrepository, ShippingMethodRepository $shippingrepository, PaymentMethodRepository $paymentrepository, Security $security): Response
    {
        $order = new Order();

        $oldCart = $request->getSession()->get('cart');
        $shippingId = $request->getSession()->get('shipping');
        if ($shippingId) {
            $order->setShippingMethod($shippingrepository->findOneBy(['id' => $shippingId]));
        }
        $paymentId = $request->getSession()->get('payment');
        if ($shippingId) {
            $order->setPayment($paymentrepository->findOneBy(['id' => $paymentId]));
        }
        $sum = 0;
        if (isset($oldCart)) {
            foreach ($oldCart as $id => $amount) {
                $product = $productrepository->findOneBy(['id' => $id]);
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
            if ($form->get('order')->isClicked()) {
                $customer = $security->getUser()->getCustomer();
                $order->setCustomer($customer);

                $delivery = new DeliveryAddress();
                $delivery->setAddress($customer->getAddress()->getAddress());
                $order->setAddress($delivery);

                $repository->save($order);

                $request->getSession()->remove('cart');
                $request->getSession()->remove('shipping');
                $request->getSession()->remove('payment');

                $this->addFlash('success', 'message.orderplaced');
            } else {
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

                if ($order->getPayment()) {
                    $request->getSession()->set('payment', $order->getPayment()->getId());
                }

                $this->addFlash('success', 'message.updated');
            }

            return $this->redirectToRoute('cart_view');
        }

        return $this->render(
            'cart.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Add action.
     *
     * @param Request $request    HTTP request
     * @param Product $product Product
     *
     * @return Response HTTP response
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

        $this->addFlash('success', 'message.addedtocart');

        return $this->redirect($request->headers->get('referer'));
    }
}
