<?php
/**
 * Address controller.
 */

namespace App\Controller;

use App\Entity\Address;
use App\Entity\AddressBase;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AddressController.
 *
 * @Route("/address")
 */
class AddressController extends AbstractController
{
    /**
     * Add new address.
     *
     * @param Request $request HTTP request
     * @param AddressRepository $repository Address repository
     * @param Security $security Security
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new/",
     *     name="address_new",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function newAddress(Request $request, AddressRepository $repository, Security $security): Response
    {
        $customer = $security->getUser()->getCustomer();
        if (!isset($customer)) {
            return $this->redirectToRoute('customer_new');
        }
        $address = $customer->getAddress();
        if (isset($address)) {
            return $this->redirectToRoute('address_edit');
        }

        $address = new Address();
        $address->setAddress(new AddressBase());
        $address->setCustomer($customer);

        $form = $this->createForm(
            AddressType::class,
            $address,
            [
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($address);

            $this->addFlash('success', 'message.created');

            return $this->redirectToRoute('customer_view');
        }

        return $this->render(
            'address/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit address.
     *
     * @param Request $request    HTTP request
     * @param AddressRepository $repository Address repository
     * @param Security $security  Security
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/edit/",
     *     name="address_edit",
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function edit(Request $request, AddressRepository $repository, Security $security): Response
    {
        $customer = $security->getUser()->getCustomer();
        if (!isset($customer)) {
            return $this->redirectToRoute('customer_new');
        }
        $address = $customer->getAddress();

        if (!isset($address)) {
            return $this->redirectToRoute('address_new');
        }

        $form = $this->createForm(
            AddressType::class,
            $address,
            [
                'method' => 'PUT',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($address);

            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('customer_view');
        }

        return $this->render(
            'address/edit.html.twig',
            [
                'form' => $form->createView(),
                'address' => $address,
            ]
        );
    }
}
