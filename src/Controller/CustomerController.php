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
     *     "/{id}",
     *     name="customer_view",
     *     requirements={"id": "0*[1-9]\d*"},
     * )
     */
    public function view(Customer $customer): Response
    {
        return $this->render(
            'customer.html.twig',
            [
                'item' => $customer,
                'data' => $customer->getProducts(),
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
    public function newCustomer(Request $request, CustomerRepository $repository): Response
    {
        $customer = new Customer();

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
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="customer_edit",
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function edit(Request $request, Customer $customer, CustomerRepository $repository): Response
    {
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

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'customer/edit.html.twig',
            [
                'form' => $form->createView(),
                'customer' => $customer,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Customer                      $customer   Customer entity
     * @param \App\Repository\CustomerRepository        $repository Customer repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="customer_delete",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function delete(Request $request, Customer $customer, CustomerRepository $repository): Response
    {
        if ($customer->getProducts()->count()) {
            $this->addFlash('danger', 'Customer contains products.');

            return $this->redirectToRoute('customer_view', array('id' => $customer->getId()));
        }

        $form = $this->createForm(CustomerType::class, $customer, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($customer);
            $this->addFlash('success', 'Deleted successfully');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'customer/delete.html.twig',
            [
                'form' => $form->createView(),
                'customer' => $customer,
            ]
        );
    }
}
