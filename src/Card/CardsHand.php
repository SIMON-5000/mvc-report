<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardsDeck;
class CardsHand
{
    /**
     * The Hand
     * @var array<CardGraphic>
     */
    private $hand;

    public function __construct()
    {
        $this->hand = [];
    }

    public function drawCard(CardsDeck $deck): void
    {
        $card = $deck->drawCard();
        $this->add($card[0]);
    }


    private function add(CardGraphic $card): void
    {
        array_push($this->hand, $card);
    }


}
