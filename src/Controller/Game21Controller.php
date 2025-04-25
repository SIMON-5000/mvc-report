<?php

namespace App\Controller;

// use App\Dice\Dice;
// use App\Dice\DiceGraphic;
// use App\Dice\DiceHand;
use App\Card\CardsDeck;
use App\Card\Game21Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Routing class for card game 21.
*/
class Game21Controller extends AbstractController
{
    // Welcome
    #[Route("/game", name: "game21_home", methods: ['GET'])]
    public function game(): Response
    {
        return $this->render('game/home.html.twig');
    }

    // Setup
    #[Route("/game/init", name: "game21_init", methods: ['POST'])]
    public function init(Game21Handler $game): Response
    {
        $deck = new CardsDeck();
        $game->initGame( $deck);
        // $playerHand = new CardsHand();
        // $bankHand = new CardsHand();

        return $this->redirectToRoute('game21_play');
    }

    // Shows game state, and offers draw/hold
    #[Route("/game/play", name: "game21_play", methods: ['GET'])]
    public function play(): Response
    {
        return $this->render('game/play.html.twig');
    }

    // Handle draw
    #[Route("/game/handle_draw", name: "game21_handle_draw", methods: ['POST'])]
    public function handleDraw(Game21Handler $game): Response
    {
        //get all from session

        // draw card
        
        // add card

        // if($hand->isBust()) {
        //  $this->addFlash(
            // 'warning',
            // 'Otur! Du fick mer än 21 och förlorar rundan.'
            // );

            // Set state to lost
            // redirect
        // }

        return $this->redirectToRoute('game21_play');
    }

    // Documentation
    #[Route("/game/doc", name: "game21_doc", methods: ['GET'])]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}
