<?php
/**
 * Home page controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request    HTTP request
     *
     * @return Response HTTP response
     *
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        return $this->forward('App\Controller\TypeController::index');
    }
}
