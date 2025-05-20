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
     * Gets all parts of the game
     * @return mixed[]
     */
    public function getGame(): array
    {
        $game = [
            $this->getGameDeck(),
            $this->getPlayerHand(),
            $this->getBankHand()
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

    /**
     * Player draws a card
     * @return void
     */
    public function playerDraw(): void
    {
        $playerHand = $this->getPlayerHand();
        $deck = $this->getGameDeck();

        $playerHand->drawCardToHand($deck);

        // IF playerHand->isBust && playerHand->holdsAces
        //

        $this->session->set("playerHand", $playerHand);
    }

    /**
     * Bank draws cards until a score of 17 is reached.
     * @return void
     */
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
     * If there is a draw, bank "Banken" wins.
     * @param ?CardsHand $playerHand
     * @param ?CardsHand $bankHand
     * @return string "Du" if you win, and Banken if the bank wins.
     */
    public function getWinner($playerHand = null, $bankHand = null): string
    {
        if (!$playerHand) {
            /** @var CardsHand */
            $playerHand = $this->session->get("playerHand");
        }

        if (!$bankHand) {
            /** @var CardsHand */
            $bankHand = $this->session->get("bankHand");
        }
        
        if ($this->playerHasWon($playerHand, $bankHand)) {
            return "Du";
        }

        return "Banken";
    }

    /**
     * Checks a hand if it is over 21 with possible Aces deducted.
     * @param \App\Card\CardsHand $hand
     * @return bool
     */
    public function isBust(CardsHand $hand): bool
    {
        if ($this->calculate21Score($hand) > 21) {
            return true;
        }
        return false;
    }

    /**
     * Runs a controll to see if player has won.
     * Otherwise bank has won.
     * @param \App\Card\CardsHand $playerHand
     * @param \App\Card\CardsHand $bankHand
     * @return bool
     */
    private function playerHasWon(CardsHand $playerHand, CardsHand $bankHand): bool
    {
        // $playerIsBust = $this->isBust($playerHand);
        $bankIsBust = $this->isBust($bankHand);
        $playerScore = $this->calculate21Score($playerHand);
        $bankScore = $this->calculate21Score($bankHand);
        
        // if ($playerIsBust) {
        //     return false;
        // }
        
        // Player is never bust here.

        if ($bankIsBust) {
            return true;
        }

        // Noone is bust
        
        if ($playerScore > $bankScore) {
            return true;
        }

        return false;
    }
}
