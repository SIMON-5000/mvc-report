<?php

namespace App\Controller;

use App\Card\CardsDeck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route("/api/quote", name:"quote")]
    /**
     * Serves a quote from Alfred Hitchcock by converting a JSON file into an associated array,
     * picking a random quote that gets turned back to JSON and presented.
     * @return JsonResponse
     */
    public function jsonQuotes(): Response
    {
        $number = random_int(0, 52);
        $quotes = json_decode(file_get_contents('data/quotes.json'), true);
        // var_dump($quotes['quotes']);
        // var_dump(array_keys($quotes));
        $quote = $quotes['quotes'][$number];

        // Returning JSON data:

        // $response = new Response();
        // $response->setContent(json_encode($data));
        // // Sets type of data
        // $response->headers->set('Content-Type', 'application/json');
        // return $response;

        // This can be simplified by using: JsonResponse
        // "imported" with use at top.


        // RAW JSON DATA TO PRINT
        // return new JsonResponse($quotes);

        // Here we first prettify the json data, JSON_PRETTY_PRINT

        $response = new JsonResponse($quote);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    private function getDeck(SessionInterface $session): CardsDeck
    {
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
        $deck = $this->getDeck($session);
        $deck->shuffle();

        $session->set("api_deck", $deck);

        return $this->redirectToRoute('api_deck_shuffle');
    }

    #[Route("/api/deck/shuffle/json", name:"api_deck_shuffle", methods: ['GET'])]
    public function apiDeckShuffleJson(SessionInterface $session): Response
    {
        $response = new JsonResponse($session->get("api_deck")->getValues());

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
