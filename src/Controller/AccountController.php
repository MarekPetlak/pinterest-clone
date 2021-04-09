<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("", name="account")
     */
    public function show(): Response
    {
        return $this->render('account/show.html.twig');
    }

    /**
     * @Route("/edit", name="account_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $userForm = $this->createForm(UserFormType::class, $this->getUser());
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Profile successfully updated');

            return $this->redirectToRoute('account');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $userForm->createView(),
        ]);
    }

    /**
     * @Route("/change-password", name="account_change_password", methods={"GET","PUT"})
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $passwordChangeForm = $this->createForm(ChangePasswordFormType::class, null, [
            ChangePasswordFormType::CURRENT_PASSWORD_REQUIRED_OPTION_NAME => true,
            'method' => 'PUT',
        ]);
        $passwordChangeForm->handleRequest($request);

        if ($passwordChangeForm->isSubmitted() && $passwordChangeForm->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            $user->setPassword(
                $encoder->encodePassword($user, $passwordChangeForm->get('plainPassword')->getData())
            );

            $this->entityManager->flush();

            $this->addFlash('success', 'Password successfully changed');

            return $this->redirectToRoute('account');

        }

        return $this->render('account/change_password.html.twig', [
            'form' => $passwordChangeForm->createView(),
        ]);
    }
}
