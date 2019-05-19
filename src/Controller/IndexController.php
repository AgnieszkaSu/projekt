<?php
/**
 * Home page controller.
 */

namespace App\Controller;

use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController.
 */
class IndexController extends AbstractController
{
    /**
     * Index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/")
     */
    public function index(TypeRepository $repository): Response
    {
        return $this->render(
            'index.html.twig',
            ['data' => $repository->findAll()]
        );
    }
}
