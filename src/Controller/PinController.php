<?php

namespace App\Controller;

use App\Entity\Pin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinController extends AbstractController
{
    /**
     * @Route("/pin/{id<\d+>}", name="pin_show")
     */
    public function show(Pin $pin): Response
    {

        return $this->render('pin/show.html.twig', ['pin' => $pin]);
    }

}
