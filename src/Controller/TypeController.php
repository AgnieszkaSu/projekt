<?php
/**
 * Type controller.
 */

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TypeController.
 *
 * @Route("/type")
 */
class TypeController extends AbstractController
{
    /**
     * View all types.
     *
     * @param Request $request HTTP request
     * @param TypeRepository $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route("/", name="type_index")
     */
    public function index(Request $request, TypeRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            // $request->query->getInt('page', 1),
            $this->get('request_stack')->getMasterRequest()->query->getInt('page', 1),
            9
        );

        return $this->render(
            'type_list.html.twig',
            [
                'data' => $repository->findAll(),
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * View all products of this type.
     *
     * @param Type $type Type
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}/",
     *     name="type_view",
     *     requirements={"id": "0*[1-9]\d*"},
     * )
     */
    public function view(Type $type): Response
    {
        return $this->render(
            'type.html.twig',
            [
                'item' => $type,
                'data' => $type->getProducts(),
            ]
        );
    }

    /**
     * Add new type.
     *
     * @param Request $request    HTTP request
     * @param TypeRepository $repository Type repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new/",
     *     name="type_new",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function newType(Request $request, TypeRepository $repository): Response
    {
        $type = new Type();

        $form = $this->createForm(
            TypeType::class,
            $type,
            [
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($type);

            $this->addFlash('success', 'message.created');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'type/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit type.
     *
     * @param Request $request    HTTP request
     * @param Type $type    Type entity
     * @param TypeRepository $repository Type repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit/",
     *     requirements={"id": "[1-9]\d*"},
     *     name="type_edit",
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function edit(Request $request, Type $type, TypeRepository $repository): Response
    {
        $form = $this->createForm(
            TypeType::class,
            $type,
            [
                'method' => 'PUT',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($type);

            $this->addFlash('success', 'message.updated');

            return $this->redirectToRoute('type_view', array('id' => $type->getId()));
        }

        return $this->render(
            'type/edit.html.twig',
            [
                'form' => $form->createView(),
                'type' => $type,
            ]
        );
    }

    /**
     * Delete type.
     *
     * @param Request $request    HTTP request
     * @param Type $type   Type entity
     * @param TypeRepository $repository Type repository
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
     *     name="type_delete",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function delete(Request $request, Type $type, TypeRepository $repository): Response
    {
        if ($type->getProducts()->count()) {
            $this->addFlash('danger', 'error.typecontainsproducts');

            return $this->redirectToRoute('type_view', array('id' => $type->getId()));
        }

        $form = $this->createForm(TypeType::class, $type, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($type);
            $this->addFlash('success', 'message.deleted');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'type/delete.html.twig',
            [
                'form' => $form->createView(),
                'type' => $type,
            ]
        );
    }
}
