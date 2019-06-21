<?php
/**
 * Order controller.
 */

namespace App\Controller;

use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class ProductController.
 *
 * @Route("/orders")
 */
class OrderController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\OrderRepository            $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/", name="orders_index")
     */
    public function index(Request $request, OrderRepository $repository, PaginatorInterface $paginator, Security $security): Response
    {
        $pagination = $paginator->paginate(
            $repository->findBy(['customer' => $security->getUser()->getCustomer()]),
            // $request->query->getInt('page', 1),
            $this->get('request_stack')->getMasterRequest()->query->getInt('page', 1),
            9
        );

        return $this->render(
            'orders.html.twig',
            [
                'data' => $repository->findAll(),
                'pagination' => $pagination,
            ]
        );
    }
}
