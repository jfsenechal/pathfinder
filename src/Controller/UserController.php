<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\User;
use AfmLibre\Pathfinder\Form\UserEditType;
use AfmLibre\Pathfinder\Form\UserType;
use AfmLibre\Pathfinder\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @Route("/", name="pathfinder_user_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/user/index.html.twig',
            [
                'users' => $this->userRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="pathfinder_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_PATHFINDER' => 'ROLE_PATHFINDER']);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, $form->getData()->getPlainPassword())
            );
            $this->userRepository->persist($user);
            $this->userRepository->flush();

            return $this->redirectToRoute('pathfinder_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            '@AfmLibrePathfinder/user/new.html.twig',
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    /**
     * @Route("/{id}", name="pathfinder_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/user/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="pathfinder_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->flush();

            return $this->redirectToRoute('pathfinder_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            '@AfmLibrePathfinder/user/edit.html.twig',
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    /**
     * @Route("/{id}", name="pathfinder_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            $this->userRepository->remove($user);
            $this->userRepository->flush();
        }

        return $this->redirectToRoute('pathfinder_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
