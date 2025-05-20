<?php

namespace App\Controller;

use App\Card\CardsDeck;
use App\Card\Game21Handler;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class Game21ApiController extends AbstractController
    /**
     * Controller for API routing of game 21
     */
{
    /**
     * Serves an overview of the state of the current or last game.
     */
    #[Route("/api/game21", name:"api_game_21")]
    public function apiGame21(Game21Handler $game): Response
    {
        $bankHand = $game->getBankHand();
        $playerHand = $game->getPlayerHand();

        $gameRepresentation = [
            "Bank Hand" => $game->calculate21Score($bankHand),
            "Bank Cards" => $bankHand->getCardsFromHand(),
            "Is Bank Bust" => $game->isBust($bankHand),
            "Player Hand" => $game->calculate21Score($playerHand),
            "Player Cards" => $playerHand->getCardsFromHand(),
            "Is Player Bust" => $game->isBust($playerHand)
        ];

        $response = new JsonResponse($gameRepresentation);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
