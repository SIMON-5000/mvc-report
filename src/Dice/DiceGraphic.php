<?php

namespace App\Dice;

class DiceGraphic extends Dice
{
    /**
     * 1-6 as die-sides
     * @var array<string>
     */
    private $representation = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAsString(): string
    {
        // DieVal-1 = index
        return $this->representation[$this->value - 1];
    }
}
