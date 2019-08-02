<?php
/**
 * Colour controller.
 */

namespace App\Controller;

use App\Entity\Colour;
use App\Form\ColourType;
use App\Repository\ColourRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ColourController.
 *
 * @Route("/colour")
 */
class ColourController extends AbstractController
{
    /**
     * New colour.
     *
     * @param Request $request    HTTP request
     * @param ColourRepository $repository Colour repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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

            $this->addFlash('success', 'message.created');

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
     * Edit colour.
     *
     * @param Request $request    HTTP request
     * @param Colour $colour    Colour entity
     * @param ColourRepository $repository Colour repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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

            $this->addFlash('success', 'message.updated');

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
     * Delete colour.
     *
     * @param Request $request    HTTP request
     * @param Colour $colour   Colour entity
     * @param ColourRepository $repository Colour repository
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
     *     name="colour_delete",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function delete(Request $request, Colour $colour, ColourRepository $repository): Response
    {
        if ($colour->getProducts()->count()) {
            $this->addFlash('danger', 'error.colorcontainsproducts');

            return $this->redirectToRoute('colour_view', array('id' => $colour->getId()));
        }

        $form = $this->createForm(ColourType::class, $colour, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($colour);
            $this->addFlash('success', 'message.deleted');

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
