<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("is_granted('PIN_CREATE')", message="Access denied")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
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
     * @Security("is_granted('PIN_EDIT', pin)", message="Access denied")
     * @param Pin $pin
     * @param Request $request
     * @return Response
     */
    public function edit(Pin $pin, Request $request): Response
    {        $form = $this->createForm(
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
     * @Security("is_granted('PIN_DELETE', pin)", message="Access denied")
     * @param Pin $pin
     * @param Request $request
     * @return Response
     */
    public function delete(Pin $pin, Request $request): Response
    {
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
