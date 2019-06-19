<?php
/**
 * Admin controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AdminController.
 *
 * @Route("/admin")
 *
 * @IsGranted("MANAGE")
 */
class AdminController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserRepository            $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route("/", name="admin_users")
     */
    public function index(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            // $request->query->getInt('page', 1),
            $this->get('request_stack')->getMasterRequest()->query->getInt('page', 1),
            9
        );

        return $this->render(
            'admin/users.html.twig',
            [
                'data' => $repository->findAll(),
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * View user action.
     *
     * @param \App\Repository\User user User
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}/",
     *     name="admin_user_view",
     *     requirements={"id": "0*[1-9]\d*"},
     * )
     */
    public function view(User $user): Response
    {
        return $this->render(
            'admin/user_view.html.twig',
            [
                'item' => $user,
            ]
        );
    }

    /**
     * Delete user action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                      $user   User entity
     * @param \App\Repository\UserRepository        $repository User repository
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
     *     name="user_delete",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function delete(Request $request, User $user, UserRepository $repository): Response
    {
        if ($user->getProducts()->count()) {
            $this->addFlash('danger', 'User contains products.');

            return $this->redirectToRoute('user_view', array('id' => $user->getId()));
        }

        $form = $this->createForm(UserType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($user);
            $this->addFlash('success', 'Deleted successfully');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
