<?php

namespace App\Card;

use App\Card\CardsHand;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameSessionHandler
{
    /**
     * Summary of getPlayerHand
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @return CardsHand
     */
    public function getPlayerHand(
        SessionInterface $session
    ): CardsHand {
        /** @var CardsHand $playerHand */
        $playerHand = $session->get("playerHand");

        return $playerHand;
    }
}
