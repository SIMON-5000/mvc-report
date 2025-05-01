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
        ): void
    {
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

        $this->session->set("playerHand", $playerHand);
    }

    public function bankPlays():void
    {
        $gameDeck = $this->session->get("gameDeck");
        $bankHand = $this->session->get("bankHand");

        while ($bankHand->getHandValue() < 17){
            $bankHand->drawCardToHand($gameDeck);
        }

        $this->session->set("bankHand", $bankHand);
    }

    public function getWinner(): string
    {
        

        $playerHand = $this->session->get("playerHand");
        $bankHand = $this->session->get("bankHand");
        
        $playerIsBust = $this->isBust($playerHand);

        if(!$playerIsBust && $playerHand->getHandValue() > $bankHand->getHandValue()){
            return "Du";
        }

        return "Banken";
    }

    public function isBust(CardsHand $hand):bool
    {
        if($hand->getHandValue() > 21) {
            return true;
        }
        return false;
    }
}
