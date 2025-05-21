<?php

namespace App\Controller;

use App\Card\CardsDeck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardsApiController extends AbstractController
    /**
     * Controller for API routing of Cards
     */
{
    /**
     * Gets the current deck from sessions, or make a new if none is there/empty.
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @return \App\Card\CardsDeck
     */
    private function getDeck(SessionInterface $session): CardsDeck
    {
        /** @var ?CardsDeck $deck */
        $deck = $session->get("api_deck");
        if (!$deck || $deck->deckSize() == 0) {
            $deck = new CardsDeck();
            $deck->fillDeck();
            $session->set("api_deck", $deck);
            $this->addFlash(
                'notice',
                'Du drog sista kortet, ny kortlek tillagd!'
            );
        }
        /** @var CardsDeck $deck */
        return $deck;
    }

    #[Route("/api/deck", name:"api_deck")]
    public function apiDeck(): Response
    {
        $deck = new CardsDeck();
        $deck->fillDeck();

        $deckValues = $deck->getValues();

        $response = new JsonResponse($deckValues);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name:"api_deck_shuffle_post", methods: ['POST'])]
    public function apiDeckShuffle(SessionInterface $session): Response
    {
        /** @var CardsDeck $deck */
        $deck = $this->getDeck($session);
        $deck->shuffle();

        $session->set("api_deck", $deck);

        return $this->redirectToRoute('api_deck_shuffle');
    }

    #[Route("/api/deck/shuffle/json", name:"api_deck_shuffle", methods: ['GET'])]
    public function apiDeckShuffleJson(SessionInterface $session): Response
    {
        /** @var CardsDeck $deck */
        $deck = $session->get("api_deck");
        $response = new JsonResponse($deck->getValues());

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", name:"api_deck_draw_post", methods: ['POST'])]
    public function apiDeckDrawPost(SessionInterface $session): Response
    {
        $deck = $this->getDeck($session);

        $drawn = $deck->draw();

        $session->set("api_drawn", $drawn);
        $session->set("api_deck", $deck);

        return $this->redirectToRoute('api_deck_draw');
    }

    #[Route("/api/deck/draw/many", name:"api_deck_draw_many_handler", methods: ['POST'])]
    public function apiDeckDrawManyHandler(Request $request): Response
    {
        $num = $request->request->get('num-cards');

        return $this->redirectToRoute("api_deck_draw_many_post", ['num' => $num]);
    }

    #[Route("/api/deck/draw/{num<\d+>}", name:"api_deck_draw_many_post")]
    public function apiDeckDrawManyPost(int $num, SessionInterface $session): Response
    {
        $deck = $this->getDeck($session);

        if ($num > $deck->deckSize()) {
            $this->addFlash(
                'warning',
                'Du kan inte dra fler kort än det fins i leken!'
            );
            return $this->redirectToRoute('api');
        }

        $drawn = $deck->draw($num);

        $session->set("api_drawn", $drawn);
        $session->set("api_deck", $deck);

        return $this->redirectToRoute('api_deck_draw');
    }

    #[Route("/api/deck/draw/json", name:"api_deck_draw", methods: ['GET'])]
    public function apiDeckDraw(SessionInterface $session): Response
    {
        $this->getDeck($session);
        $response = new JsonResponse($session->get("api_drawn"));

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
