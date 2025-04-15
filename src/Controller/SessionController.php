<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Contrellers for session-related pages.
 * Code for SessionInterface:
 * https://github.com/symfony/symfony/blob/7.3/src/Symfony/Component/HttpFoundation/Session/SessionInterface.php
 * more info ~ https://symfony.com/doc/current/session.html
 */
class SessionController extends AbstractController
{
    #[Route('/session', name: "session_home")]
    public function session(
        SessionInterface $session
    ): Response {
        // Gets all attributes we've set to SessionInterface.
        $sessionContent = $session->all();
        $data = [
            'session' => $sessionContent
        ];

        return $this->render('session/session.html.twig', $data);
    }

    #[Route('/session/delete', name: "session_delete", methods: ['POST'])]
    public function number(SessionInterface $session): Response
    {
        // Clears all session attributes and flashes and regenerates the
        // session and deletes the old session from persistence.
        $session->invalidate();

        return $this->redirectToRoute('session_home');
    }
}
