<?php
namespace App\Controller;

use App\Dice\Dice;
use App\Dice\DiceGraphic;
use App\Dice\DiceHand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->render('game/card/card.html.twig');
    }

    // #[Route("/card/deck", name: "card_deck")]
    // public function deck(): Response
    // {
    //     return $this->render('game/card/deck.html.twig');
    // }

    // #[Route("/card/deck/shuffle", name: "card_deck_shuffle", methods: ['GET'])]
    // public function deckShuffle(): Response
    // {
    //     return $this->render('game/card/deck_shuffle.html.twig');
    // }

    // #[Route("/card/deck/shuffle", name: "card_deck_shuffle_post", methods: ['POST'])]
    // public function deckShuffleCallback(): Response
    // {
    //     return $this->redirectToRoute('card_deck_shuffle');
    // }

    // #[Route("/card/deck/draw", name: "card_draw")]
    // public function draw(): Response
    // {
    //     return $this->render('game/card/draw.html.twig');
    // }

    // #[Route("/card/deck/draw//{num<\d+>}", name: "card_draw_many", methods: ['GET'])]
    // #[Route("/card/deck/draw:number", name: "card_deck_shuffle", methods: ['GET'])]
    // public function deckShuffle(): Response
    // {
    //     return $this->render('game/card/deck_draw_many.html.twig');
    // }

    // Skapa en sida card/deck/draw som drar ett kort från kortleken och visar upp det. Visa även antalet kort som är kvar i kortleken.

    // Skapa en sida card/deck/draw/:number som drar :number kort från kortleken och visar upp dem. Visa även antalet kort som är kvar i kortleken.

}
