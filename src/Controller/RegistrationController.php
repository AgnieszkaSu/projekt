<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use App\Form\Model\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route(
     *      "/register/",
     *      name="user_registration",
     *      methods={"GET", "POST"},
     * )
     */
    public function registerAction(Request $request, UserRepository $repository, RoleRepository $roleRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [ 'method' => 'POST' ]);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->setRole($roleRepository->findOneBy(['name' => 'user']));

            // 4) save the User!
            $repository->save($user);

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->addFlash('success', 'User registered.');

            return $this->get('security.authentication.guard_handler')->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $this->get('App\Security\LoginFormAuthenticator'),
                'main'
            );
        }

        return $this->render(
            'registration/register.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route(
     *      "/change_password/",
     *      name="user_password",
     *      methods={"GET", "POST"},
     * )
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function changePassword(Request $request, UserRepository $repository, Security $security, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $security->getUser();
        $changePassword = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePassword, [ 'method' => 'POST' ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $changePassword->getPassword());
            $user->setPassword($password);

            $repository->save($user);

            $this->addFlash('success', 'Password changed.'); 
            return $this->redirectToRoute('index');
        }

        return $this->render(
            'registration/change.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
