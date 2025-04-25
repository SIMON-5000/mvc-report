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
        CardsDeck $deck
        ): void
    {
        $deck->fillDeck();
        $deck->shuffle();

        $this->session->set("gameDeck", $deck);
    }
}
