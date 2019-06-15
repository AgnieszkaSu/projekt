<?php
/**
 * Type controller.
 */

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ProductController.
 *
 * @Route("/type")
 */
class TypeController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\TypeRepository            $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
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
     * Type action.
     *
     * @param \App\Repository\Type type Type
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
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
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\TypeRepository            $repository Type repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
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

            $this->addFlash('success', 'Type created.');

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
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Type                       $type    Type entity
     * @param \App\Repository\TypeRepository         $repository Type repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
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

            $this->addFlash('success', 'Type updated successfully.');

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
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Type                      $type   Type entity
     * @param \App\Repository\TypeRepository        $repository Type repository
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
     *     name="type_delete",
     * )
     */
    public function delete(Request $request, Type $type, TypeRepository $repository): Response
    {
        if ($type->getProducts()->count()) {
            $this->addFlash('danger', 'Type contains products.');

            return $this->redirectToRoute('type_view', array('id' => $type->getId()));
        }

        $form = $this->createForm(TypeType::class, $type, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($type);
            $this->addFlash('success', 'Deleted successfully');

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
