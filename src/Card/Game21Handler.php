<?php

namespace App\Card;

// Deprecated since 5.3: https://symfony.com/blog/new-in-symfony-5-3-session-service-deprecation
// use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Game21Handler
{
    /** @var SessionInterface */
    private $session;
    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }
    public function initGame(
        CardsDeck $deck,
        CardsHand $playerHand,
        CardsHand $bankHand
    ): void {
        $deck->fillDeck();
        $deck->shuffle();

        $playerHand->drawCardToHand($deck);

        $this->session->set("gameDeck", $deck);
        $this->session->set("playerHand", $playerHand);
        $this->session->set("bankHand", $bankHand);
    }

    /**
     * Summary of getGame
     * @return mixed[]
     */
    public function getGame(): array
    {
        $game = [
            $this->session->get("gameDeck"),
            $this->session->get("playerHand"),
            $this->session->get("bankHand")
        ];
        return $game;
    }

    public function getPlayerHand(): CardsHand
    {
        /** @var CardsHand */
        $playerHand = $this->session->get("playerHand");
        return $playerHand;
    }

    public function getBankHand(): CardsHand
    {
        /** @var CardsHand */
        $playerHand = $this->session->get("bankHand");
        return $playerHand;
    }

    public function getGameDeck(): CardsDeck
    {
        /** @var CardsDeck */
        $deck = $this->session->get("gameDeck");
        return $deck;
    }

    public function playerDraw(): void
    {
        $playerHand = $this->getPlayerHand();
        $deck = $this->getGameDeck();

        $playerHand->drawCardToHand($deck);

        // IF playerHand->isBust && playerHand->holdsAces
        //

        $this->session->set("playerHand", $playerHand);
    }

    // bank makes moves
    public function bankPlays(): void
    {
        /** @var CardsDeck */
        $gameDeck = $this->session->get("gameDeck");
        /** @var CardsHand */
        $bankHand = $this->session->get("bankHand");

        while ($this->calculate21Score($bankHand) < 17) {
            $bankHand->drawCardToHand($gameDeck);
        }

        $this->session->set("bankHand", $bankHand);
    }

    /**
     * Checks scoring specific for 21.
     * If the player is bust, the aces can be counted as 1 instead of 14
     * @param \App\Card\CardsHand $cardsHand
     * @return int The score.
     */
    public function calculate21Score(CardsHand $cardsHand): int
    {
        $score = $cardsHand->getHandValue();
        $aces = $cardsHand->holdsAces();

        while ($score > 21 && $aces > 0) {
            $score -= 13;
            $aces--;
        }

        return $score;
    }


    /**
     * If there is a draw, bank "banken" wins.
     * @return string "Du" if you win, and Banken if the bank wins.
     */
    public function getWinner(): string
    {
        /** @var CardsHand */
        $playerHand = $this->session->get("playerHand");
        /** @var CardsHand */
        $bankHand = $this->session->get("bankHand");

        $playerIsBust = $this->isBust($playerHand);
        $bankIsBust = $this->isBust($bankHand);
        // This logic could probably be improved:
        if (!$playerIsBust && $this->calculate21Score($playerHand) > $this->calculate21Score($bankHand)) {
            return "Du";
        }

        if (!$playerIsBust && $bankIsBust) {
            return "Du";
        }

        return "Banken";
    }

    public function isBust(CardsHand $hand): bool
    {
        if ($this->calculate21Score($hand) > 21) {
            return true;
        }
        return false;
    }
}
