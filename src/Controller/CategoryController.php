<?php
/**
 * Category controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
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
 * Class ProductController.
 *
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * View all action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\CategoryRepository            $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/", name="category_index")
     */
    public function index(Request $request, CategoryRepository $repository, PaginatorInterface $paginator): Response
    {
        // $pagination = $paginator->paginate(
        //     $repository->queryAll(),
        //     // $request->query->getInt('page', 1),
        //     $this->get('request_stack')->getMasterRequest()->query->getInt('page', 1),
        //     9
        // );
        //
        return $this->render(
            'category.html.twig',
            [
                'data' => $repository->findAll(),
            ]
        );
    }
    /**
     * Category action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Category category Category
     * @param \App\Repository\TypeRepository $repository Type repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}/",
     *     name="category_view",
     *     requirements={"id": "0*[1-9]\d*"},
     * )
     */
    public function view(Request $request, Category $category, TypeRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryByCategory($category->getId()),
            // $request->query->getInt('page', 1),
            $this->get('request_stack')->getMasterRequest()->query->getInt('page', 1),
            9
        );

        return $this->render(
            'type_list.html.twig',
            [
                'data' => $repository->findAll(),
                'pagination' => $pagination,
                'category' => $category->getId(),
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\CategoryRepository            $repository Category repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new/",
     *     name="category_new",
     *     methods={"GET", "POST"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function newCategory(Request $request, CategoryRepository $repository): Response
    {
        $category = new Category();

        $form = $this->createForm(
            CategoryType::class,
            $category,
            [
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($category);

            $this->addFlash('success', 'Utworzono kategorię');

            return $this->redirectToRoute('category_view', ['id' => $category->getId()]);
        }

        return $this->render(
            'category/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Category                       $category    Category entity
     * @param \App\Repository\CategoryRepository         $repository Category repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit/",
     *     requirements={"id": "[1-9]\d*"},
     *     name="category_edit",
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function edit(Request $request, Category $category, CategoryRepository $repository): Response
    {
        $form = $this->createForm(
            CategoryType::class,
            $category,
            [
                'method' => 'PUT',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($category);

            $this->addFlash('success', 'Zaktualizowano kategorię');

            return $this->redirectToRoute('category_view', ['id' => $category->getId()]);
        }

        return $this->render(
            'category/edit.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Category                      $category   Category entity
     * @param \App\Repository\CategoryRepository        $repository Category repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete/",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="category_delete",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function delete(Request $request, Category $category, CategoryRepository $repository): Response
    {
        if ($category->getTypes()->count()) {
            $this->addFlash('danger', 'Kategoria zawiera produkty');

            return $this->redirectToRoute('category_view', array('id' => $category->getId()));
        }

        $form = $this->createForm(CategoryType::class, $category, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($category);
            $this->addFlash('success', 'Usunięto kategorię');

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/delete.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
}
