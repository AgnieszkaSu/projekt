<?php
/**
 * Admin controller.
 */

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Address;
use App\Entity\User;
use App\Entity\Order;
use App\Form\AddressType;
use App\Form\CustomerType;
use App\Form\UserDeleteType;
use App\Form\AdminChangePasswordType;
use App\Form\AdminOrderType;
use App\Form\Model\UserHelper;
use App\Repository\AddressRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminController.
 *
 * @Route("/admin")
 *
 * @IsGranted("MANAGE")
 */
class AdminController extends AbstractController
{
    /**
     * Show users.
     *
     * @param Request $request    HTTP request
     * @param UserRepository $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route("/", name="admin_users")
     */
    public function index(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            // $request->query->getInt('page', 1),
            $this->get('request_stack')->getMasterRequest()->query->getInt('page', 1),
            9
        );

        return $this->render(
            'admin/users.html.twig',
            [
                'data' => $repository->findAll(),
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * View single user.
     *
     * @param User $user User
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}/",
     *     name="admin_user_view",
     *     requirements={"id": "0*[1-9]\d*"},
     * )
     */
    public function view(User $user): Response
    {
        return $this->render(
            'admin/user_view.html.twig',
            [
                'item' => $user,
            ]
        );
    }

    /**
     * Edit customer.
     *
     * @param Request $request
     * @param Customer $customer
     * @param CustomerRepository $repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/customer/{id}/edit/",
     *     requirements={"id": "0*[1-9]\d*"},
     *     name="admin_customer_edit",
     *     methods={"GET", "POST"},
     * )
     */
    public function edit(Request $request, Customer $customer, CustomerRepository $repository): Response
    {
        $form = $this->createForm(CustomerType::class, $customer, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($customer);

            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_user_view', ['id' => $customer->getUser()->getId()]);
        }

        return $this->render(
            'admin/customer_edit.html.twig',
            [
                'form' => $form->createView(),
                'customer' => $customer,
            ]
        );
    }

    /**
     * Edit address.
     *
     * @param Request $request
     * @param Address $address
     * @param AddressRepository $repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/address/{id}/edit/",
     *     requirements={"id": "0*[1-9]\d*"},
     *     name="admin_address_edit",
     *     methods={"GET", "POST"},
     * )
     */
    public function editAddress(Request $request, Address $address, AddressRepository $repository): Response
    {
        $form = $this->createForm(AddressType::class, $address, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($address);

            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_user_view', ['id' => $address->getCustomer()->getUser()->getId()]);
        }

        return $this->render(
            'admin/address_edit.html.twig',
            [
                'form' => $form->createView(),
                'address' => $address,
            ]
        );
    }

    /**
     * Delete user.
     *
     * @param Request $request    HTTP request
     * @param User $user   User entity
     * @param UserRepository $repository User repository
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
     *     name="admin_user_delete",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function delete(Request $request, User $user, UserRepository $repository): Response
    {
        $userHelper = new userHelper();
        $userHelper->setLogin($user->getLogin());
        $userHelper->setId($user->getId());
        $form = $this->createForm(UserDeleteType::class, $userHelper, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repository->findOneBy(['id' => $userHelper->getId()]);
            $repository->delete($user);
            $this->addFlash('success', 'message.deleted');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render(
            'admin/user_delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Show orders.
     *
     * @param Request $request HTTP request
     * @param OrderRepository $repository User repository
     * @param PaginatorInterface $paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/orders/",
     *     name="admin_orders",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function orders(Request $request, OrderRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->findAll(),
            // $request->query->getInt('page', 1),
            $this->get('request_stack')->getMasterRequest()->query->getInt('page', 1),
            9
        );

        return $this->render(
            'admin/orders.html.twig',
            [
                'data' => $repository->findAll(),
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * Edit order.
     *
     * @param Request $request HTTP request
     * @param Order $order Order
     * @param OrderRepository $repository Order repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route(
     *     "/orders/{id}/edit",
     *     name="admin_order_edit",
     *     requirements={"id": "0*[1-9]\d*"},
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function editOrder(Request $request, Order $order, OrderRepository $repository): Response
    {
        $form = $this->createForm(AdminOrderType::class, $order, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($order);

            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('admin_orders');
        }

        return $this->render(
            'admin/order_edit.html.twig',
            [
                'form' => $form->createView(),
                'order' => $order,
            ]
        );
    }

    /**
     * Change user's password.
     *
     * @param Request $request
     * @param UserRepository $repository User repository
     * @param User $user User
     * @param UserPasswordEncoderInterface $passwordEncoder User password encoder interface
     *
     * @return RedirectResponse|Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *      "/change_password/{id}/",
     *      requirements={"id": "0*[1-9]\d*"},
     *      name="admin_change_password",
     *      methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function changePassword(Request $request, UserRepository $repository, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(AdminChangePasswordType::class, $user, [ 'method' => 'POST' ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $repository->save($user);

            $this->addFlash('success', 'message.updated');
            return $this->redirectToRoute('admin_user_view', ['id' => $user->getId()]);
        }

        return $this->render(
            'registration/change.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
