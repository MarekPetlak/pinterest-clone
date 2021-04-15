<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pin", name="pin_")
 */
class PinController extends AbstractController
{
    private PinRepository $pinRepository;

    public function __construct(PinRepository $pinRepository)
    {
        $this->pinRepository = $pinRepository;
    }

    /**
     * @Route("/{id<\d+>}", name="show", methods="GET")
     * @param Pin $pin
     * @return Response
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pin/show.html.twig', ['pin' => $pin]);
    }

    /**
     * @Route("/create", name="create", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        if (!$this->getUser()->isVerified()) {
            $this->addFlash('danger', 'You have to verify your account first!');

            return $this->redirectToRoute('homepage');
        }

        $pin = new Pin();

        $form = $this->createForm(
            PinType::class,
            $pin,
            [
                'action' => $this->generateUrl('pin_create'),
                'method' => 'POST'
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pin->setUser($this->getUser());
            $this->pinRepository->save($pin);

            $this->addFlash('success', 'Pin successfully created');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('pin/create.html.twig', ['pinForm' => $form->createView()]);
    }


    /**
     * @Route("/{id<\d+>}/edit", name="edit", methods={"GET","PUT"})
     * @param Pin $pin
     * @param Request $request
     * @return Response
     */
    public function edit(Pin $pin, Request $request): Response
    {
        if ($this->getUser() != $pin->getUser()) {
            $this->addFlash('danger', 'Access forbidden!');

            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(
            PinType::class,
            $pin,
            [
                'method' => 'PUT'
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->pinRepository->save($pin);

            $this->addFlash('success', 'Pin successfully updated');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('pin/edit.html.twig', [
            'pin'     => $pin,
            'pinForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id<\d+>}/delete", name="delete", methods="DELETE")
     * @param Pin $pin
     * @param Request $request
     * @return Response
     */
    public function delete(Pin $pin, Request $request): Response
    {
        if ($this->getUser() != $pin->getUser()) {
            $this->addFlash('danger', 'Access forbidden!');

            return $this->redirectToRoute('homepage');
        }

        if ($this->isCsrfTokenValid(
            sprintf('pin_deletion_%d', $pin->getId()),
            $request->request->get('csrf_token'))
        ) {
            $this->pinRepository->remove($pin);

            $this->addFlash('success', 'Pin successfully deleted');
        }

        return $this->redirectToRoute('homepage');
    }

}
