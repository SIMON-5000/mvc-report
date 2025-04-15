<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Holds the main routes for the page.
 */
class MainController extends AbstractController
{
    #[Route('/', name: "me")]
    public function number(): Response
    {
        return $this->render('me.html.twig');
    }

    #[Route("/report", name: "report")]
    public function home(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/api", name: "api")]
    public function allApi(SessionInterface $session): Response
    {
        $data = [
            "maxValue" => 52
        ];

        if ($session->get("api_deck")) {
            $data["maxValue"] = $session->get("api_deck")->deckSize();
        }

        return $this->render('api.html.twig', $data);
    }
}
