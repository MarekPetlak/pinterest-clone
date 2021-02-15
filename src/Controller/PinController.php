<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
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

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/{id<\d+>}", name="show", methods="GET")
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pin/show.html.twig', ['pin' => $pin]);
    }

    /**
     * @Route("/create", name="create", methods={"GET","POST"})
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
            $this->em->persist($pin);
            $this->em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('pin/create.html.twig', ['pinForm' => $form->createView()]);
    }


    /**
     * @Route("/{id<\d+>}/edit", name="edit", methods={"GET","PUT"})
     */
    public function edit(Pin $pin, Request $request): Response
    {
        $form = $this->createForm(
            PinType::class,
            $pin,
            [
                'method' => 'PUT'
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('pin/edit.html.twig', [
            'pin'     => $pin,
            'pinForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id<\d+>}/delete", name="delete", methods="DELETE")
     */
    public function delete(Pin $pin, Request $request): Response
    {
        if ($this->isCsrfTokenValid(
            sprintf('pin_deletion_%d', $pin->getId()),
            $request->request->get('csrf_token'))
        ) {
            $this->em->remove($pin);
            $this->em->flush();
        }

        return $this->redirectToRoute('homepage');
    }

}
