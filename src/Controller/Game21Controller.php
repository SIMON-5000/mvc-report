<?php

namespace App\Controller;

// use App\Dice\Dice;
// use App\Dice\DiceGraphic;
// use App\Dice\DiceHand;
use App\Card\CardsDeck;
use App\Card\CardsHand;
use App\Card\Game21Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
    public function init(
        Game21Handler $game,
        CardsDeck $deck
        ): Response
    {
        // These seem to point at same object if injected:
        $playerHand = new CardsHand();
        $bankHand = new CardsHand();
        
        $game->initGame( $deck, $playerHand, $bankHand);
        return $this->redirectToRoute('game21_play');
    }

    // Shows game state, and offers draw/hold
    #[Route("/game/play", name: "game21_play", methods: ['GET'])]
    public function play(Game21Handler $game): Response
    {
        $playerHand = $game->getPlayerHand();
        $bankHand = $game->getBankHand();

        $data = [
            "playerCards" => $playerHand->getCardsFromHand(),
            "playerScore" => $game->calculate21Score($playerHand),
            "bankCards" => $bankHand->getCardsFromHand(),
            "bankScore" => $game->calculate21Score($bankHand)
        ];

        return $this->render('game/play.html.twig', $data);
    }

    // Handle draw
    #[Route("/game/handle_draw", name: "game21_handle_draw", methods: ['POST'])]
    public function handleDraw(Game21Handler $game): Response
    {
        // //get all from session
        // // Sköta i GameLogic? $game->playerDraws(); ?
        // $playerHand = $game->getPlayerHand();
        // $deck = $game->getGameDeck();

        // // draw card & add card
        // $playerHand->drawCardToHand($deck);

        $game->playerDraw();

        $playerHand = $game->getPlayerHand();

        if($game->isBust($playerHand)) {
         $this->addFlash(
            'warning',
            'Otur! Du fick mer än 21 och förlorar rundan.'
            );

            // Set state to lost
            // redirect
        }

        return $this->redirectToRoute('game21_play');
    }

    // Bank draws cards
    #[Route("/game/banks_turn", name: "game21_banks_turn", methods: ['POST'])]
    public function bankPlays(Game21Handler $game): Response
    {
        $game->bankPlays();

        return $this->redirectToRoute('game21_winner');
    }

    // Present Winner
    #[Route("/game/winner", name: "game21_winner", methods: ['GET'])]
    public function presentWinner(Game21Handler $game): Response
    {
        $playerHand = $game->getPlayerHand();
        $bankHand = $game->getBankHand();

        $data = [
            "winner" => $game->getWinner(),
            "playerCards" => $playerHand->getCardsFromHand(),
            "playerScore" => $game->calculate21Score($playerHand),
            "bankCards" => $bankHand->getCardsFromHand(),
            "bankScore" => $game->calculate21Score($bankHand)
        ];

        return $this->render('game/winner.html.twig', $data);
    }

    // Documentation
    #[Route("/game/doc", name: "game21_doc", methods: ['GET'])]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}
