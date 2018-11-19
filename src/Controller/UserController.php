<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends Controller
{
    /**
     * @Route("/users", name="user_list")
     */
    public function list()
    {
        // Only admin can see list of users. This could be done with @Security or @IsGranted annotation.s
        $this->denyAccessUnlessGranted('list_view');
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository(User::class)->findAll()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function create(Request $request)
    {
        // Check if current user can create other users (has ROLE_ADMIN, see UserVoter)
        $this->denyAccessUnlessGranted('add', $this->getUser());

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function edit(User $user, Request $request)
    {
        // Check if current user can edit requested profile (his own profile or has ROLE_ADMIN, see UserVoter)
        $this->denyAccessUnlessGranted('edit', $user);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
