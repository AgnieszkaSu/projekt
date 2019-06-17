<?php
/**
 * Customer controller.
 */

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ProductController.
 *
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * Customer action.
     *
     * @param \App\Repository\Customer customer Customer
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="customer_view",
     * )
     */
    public function view(Security $security): Response
    {
        $customer = $security->getUser()->getCustomer()[0];

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
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\CustomerRepository            $repository Customer repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     name="customer_new",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function newCustomer(Request $request, CustomerRepository $repository, Security $security): Response
    {
        $user = $security->getUser();
        if (!isset($user->getCustomer()[0])) {
            $this->redirectToRoute('customer_edit');
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

            $this->addFlash('success', 'Customer created.');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'customer/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Customer                       $customer    Customer entity
     * @param \App\Repository\CustomerRepository         $repository Customer repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/edit",
     *     name="customer_edit",
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function edit(Request $request, CustomerRepository $repository, Security $security): Response
    {
        $customer = $security->getUser()->getCustomer()[0];

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

            $this->addFlash('success', 'Customer updated successfully.');

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
