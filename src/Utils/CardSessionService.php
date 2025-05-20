<?php

namespace App\Utils;


use App\Card\CardsDeck;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardSessionService
/** A service module for getting and setting cards in sessions */
{
    /**
     * Returns active deck from session.
     * If deck is not set or cards are out, it deals a new and resets removed.
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @return CardsDeck
     */
    public function getDeck(SessionInterface $session): CardsDeck
    {
        /** @var ?CardsDeck $deck */
        $deck = $session->get("current_deck");

        if (!$deck || $deck->deckSize() === 0) {
            $deck = new CardsDeck();
            $deck->fillDeck();
            $deck->shuffle();
            $session->set("current_deck", $deck);
            $session->set("removed_cards", []);
            $session->set("last_removed", null);
        }

        return $deck;
    }

    /**
     * Gets array of removed cards in string format.
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @return array<array{suit: string, rank: string}>
     */
    public function getRemovedCards(SessionInterface $session): array
    {
        /** @var array<array{suit: string, rank: string}> */
        $removedCards = $session->get("removed_cards") ?? [];

        return $removedCards;
    }

    /**
     * Setter for removed cards.
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param array<array{suit: string, rank: string}> $cards
     * @return void
     */
    public function setRemovedCards(
        SessionInterface $session,
        array $cards
        ): void
    {
        $session->set("removed_cards", $cards);
        $session->set("last_removed", $cards);
    }

    /**
     * Gets last removed card or cards if more than one removed at the same time.
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @return CardsDeck|null
     */
    public function getLastRemovedCard(SessionInterface $session): ?CardsDeck
    {
        $deckOfLastRemoved = null;
        
        if ($session->get("last_removed")) {
            /** @var array<array{suit: string, rank: string}> */
            $drawnCardVal = $session->get("last_removed");
            // $drawnCard = new CardGraphic($drawnCardVal['suit'], $drawnCardVal['rank']) ?? null;
            $deckOfLastRemoved = new CardsDeck();
            $deckOfLastRemoved->createFromArray($drawnCardVal);
        }

        return $deckOfLastRemoved;
    }
}
