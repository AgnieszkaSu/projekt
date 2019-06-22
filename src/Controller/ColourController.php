<?php
/**
 * Colour controller.
 */

namespace App\Controller;

use App\Entity\Colour;
use App\Form\ColourType;
use App\Repository\ColourRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ProductController.
 *
 * @Route("/colour")
 */
class ColourController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ColourRepository            $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/", name="colour_index")
     */
    public function index(Request $request, ColourRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            // $request->query->getInt('page', 1),
            $this->get('request_stack')->getMasterRequest()->query->getInt('page', 1),
            9
        );

        return $this->render(
            'colour_list.html.twig',
            [
                'data' => $repository->findAll(),
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * Colour action.
     *
     * @param \App\Entity\Colour colour Colour
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}/",
     *     name="colour_view",
     *     requirements={"id": "0*[1-9]\d*"},
     * )
     */
    public function view(Colour $colour): Response
    {
        return $this->render(
            'colour.html.twig',
            [
                'item' => $colour,
                'data' => $colour->getProducts(),
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ColourRepository            $repository Colour repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new/",
     *     name="colour_new",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function newColour(Request $request, ColourRepository $repository): Response
    {
        $colour = new Colour();

        $form = $this->createForm(
            ColourType::class,
            $colour,
            [
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($colour);

            $this->addFlash('success', 'Utworzono kolor');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'colour/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Colour                       $colour    Colour entity
     * @param \App\Repository\ColourRepository         $repository Colour repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit/",
     *     requirements={"id": "[1-9]\d*"},
     *     name="colour_edit",
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function edit(Request $request, Colour $colour, ColourRepository $repository): Response
    {
        $form = $this->createForm(
            ColourType::class,
            $colour,
            [
                'method' => 'PUT',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($colour);

            $this->addFlash('success', 'Zaktualizowano kolor');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'colour/edit.html.twig',
            [
                'form' => $form->createView(),
                'colour' => $colour,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Colour                      $colour   Colour entity
     * @param \App\Repository\ColourRepository        $repository Colour repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete/",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="colour_delete",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function delete(Request $request, Colour $colour, ColourRepository $repository): Response
    {
        if ($colour->getProducts()->count()) {
            $this->addFlash('danger', 'Kolor jest używany przez produkty');

            return $this->redirectToRoute('colour_view', array('id' => $colour->getId()));
        }

        $form = $this->createForm(ColourType::class, $colour, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($colour);
            $this->addFlash('success', 'Usunięto kolor');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'colour/delete.html.twig',
            [
                'form' => $form->createView(),
                'colour' => $colour,
            ]
        );
    }
}
