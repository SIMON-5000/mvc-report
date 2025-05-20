<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardsDeck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Routing class for card game.
*/
class CardGameController extends AbstractController
{
    // WELCOME
    #[Route("/card", name: "card_home")]
    public function home(): Response
    {
        $card = new Card('Hjärter', 'Kung');
        $cardGr = new CardGraphic('spades', 'A');
        $deck = new CardsDeck();
        $deck->fillDeck();
        $deck->shuffle();

        $data = [
            'card' => $card->getValue(),
            'graphic' => $cardGr->getAsCard(),
            'graphicVal' => $cardGr->getValue(),
            'deckSize' => $deck->deckSize(),
            'deck' => $deck->getCardsFromDeck()
        ];

        return $this->render('game/card/card.html.twig', $data);
    }
    
    #[Route("/card/deck", name: "card_deck")]
    public function deck(): Response
    {
        $deck = new CardsDeck();
        $deck->fillDeck();
        $data = [
            'deckSize' => $deck->deckSize(),
            'deck' => $deck->getCardsFromDeck()
        ];

        return $this->render('game/card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle", methods: ['GET'])]
    public function deckShuffle(): Response
    {
        $deck = new CardsDeck();
        $deck->fillDeck();
        $deck->shuffle();
        $data = [
            'deckSize' => $deck->deckSize(),
            'deck' => $deck->getCardsFromDeck()
        ];

        return $this->render('game/card/deck_shuffle.html.twig', $data);
    }

    /**
     * Draws a card
     */
    #[Route("/card/deck/draw", name: "card_draw")]
    public function draw(
        SessionInterface $session
    ): Response {
        
        /** @var ?CardsDeck $deck */
        $deck = $session->get("current_deck");
        
        if (!$deck || $deck->deckSize() == 0) {
            $deck = new CardsDeck();
            $deck->fillDeck();
            $deck->shuffle();
            $session->set("current_deck", $deck);
        }

        if (!$session->get("removed_cards")) {
            $session->set("removed_cards", []);
        }

        if ($session->get("last_removed")) {
            /** @var array<array{suit: string, rank: string}> */
            $drawnCardVal = $session->get("last_removed");
            // $drawnCard = new CardGraphic($drawnCardVal['suit'], $drawnCardVal['rank']) ?? null;
            $deckOfLastRemoved = new CardsDeck();
            $deckOfLastRemoved->createFromArray($drawnCardVal);
        }
        
        /** @var CardsDeck $deck */
        $deck = $session->get("current_deck");

        /** @var array<array{suit: string, rank: string}> */
        $removedCardsList = $session->get("removed_cards");

        $allRemovedCards = new CardsDeck();
        $allRemovedCards->createFromArray($removedCardsList);

        $data = [
            'deckSize' => $deck->deckSize(),
            'deck' => $deck->getCardsFromDeck(),
            'removed' => $allRemovedCards->getCardsFromDeck(),
            'drawn' => isset($deckOfLastRemoved) ? $deckOfLastRemoved->getCardsFromDeck() : null
        ];

        return $this->render('game/card/draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/post", name: "draw_post", methods: ['POST'])]
    public function drawCallback(SessionInterface $session): Response
    {
        /** @var CardsDeck $deck */
        $deck = $session->get("current_deck");
        /** @var array<array{suit: string, rank: string}> $removedCards */        
        $removedCards = $session->get("removed_cards");
        $drawn = $deck->draw();

        $removedCards = array_merge($removedCards, $drawn);
        $session->set("removed_cards", $removedCards);
        $session->set("last_removed", $drawn);

        if ($deck->deckSize() == 0) {
            $session->set("last_removed", null);
            $session->set("removed_cards", []);
            $this->addFlash(
                'notice',
                'Sista kortet, ny giv!'
            );
        }

        return $this->redirectToRoute('card_draw');
    }


    #[Route("/card/deck/draw/{num<\d+>}", name: "card_draw_many", methods: ['GET'])]
    public function drawMany(
        int $num,
        SessionInterface $session
    ): Response
    {
        /** @var CardsDeck $deck */
        $deck = $session->get("current_deck");
        
        /** @var array<array{suit: string, rank: string}> $removedCards */
        $removedCards = $session->get("removed_cards");

        if ($num > $deck->deckSize()) {
            $this->addFlash(
                'warning',
                'Du kan inte dra fler kort än det fins i leken!'
            );
            return $this->redirectToRoute('card_draw');
        }

        $drawn = $deck->draw($num);
        $removedCards = array_merge($removedCards, $drawn);
        $session->set("removed_cards", $removedCards);
        $session->set("last_removed", $drawn);


        return $this->redirectToRoute('card_draw');
    }

}
