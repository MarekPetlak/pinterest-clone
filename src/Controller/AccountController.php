<?php

namespace App\Controller;

use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
}
