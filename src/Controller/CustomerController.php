<?php
/**
 * Customer controller.
 */

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CustomerController.
 *
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * View customers.
     *
     * @param Security $security Security
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="customer_view",
     * )
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function view(Security $security): Response
    {
        $customer = $security->getUser()->getCustomer();

        if (!isset($customer)) {
            return $this->redirectToRoute('customer_new');
        }

        return $this->render(
            'customer.html.twig',
            [
                'customer' => $customer,
            ]
        );
    }

    /**
     * Add new customer.
     *
     * @param Request $request    HTTP request
     * @param CustomerRepository $repository Customer repository
     * @param Security $security Security
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new/",
     *     name="customer_new",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function newCustomer(Request $request, CustomerRepository $repository, Security $security): Response
    {
        $user = $security->getUser();
        if ($user->getCustomer()) {
            return $this->redirectToRoute('customer_edit');
        }

        $customer = new Customer();
        $customer->setUser($security->getUser());

        $form = $this->createForm(
            CustomerType::class,
            $customer,
            [
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($customer);

            $this->addFlash('success', 'message.created');

            return $this->redirectToRoute('customer_view');
        }

        return $this->render(
            'customer/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit customer.
     *
     * @param Request $request    HTTP request
     * @param CustomerRepository $repository Customer repository
     * @param Security $security Security
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/edit/",
     *     name="customer_edit",
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function edit(Request $request, CustomerRepository $repository, Security $security): Response
    {
        $customer = $security->getUser()->getCustomer();

        if (!isset($customer)) {
            return $this->redirectToRoute('customer_new');
        }

        $form = $this->createForm(
            CustomerType::class,
            $customer,
            [
                'method' => 'PUT',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($customer);

            $this->addFlash('success', 'message.udated');

            return $this->redirectToRoute('customer_view');
        }

        return $this->render(
            'customer/edit.html.twig',
            [
                'form' => $form->createView(),
                'customer' => $customer,
            ]
        );
    }

}
