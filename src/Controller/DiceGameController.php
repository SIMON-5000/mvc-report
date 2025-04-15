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
 * Routing class for pig game.
*/
class DiceGameController extends AbstractController
{
    // WELCOME
    // #[Route("/game/pig", name: "pig_start")]
    // public function home(): Response
    // {
    //     return $this->render('game/pig/pig.html.twig');
    // }

    #[Route("/game/pig/init", name: "pig_init_get", methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('game/pig/pig_init.html.twig');
    }

    // Handles POST
    #[Route("/game/pig/init", name: "pig_init_post", methods: ['POST'])]
    // Request and SessionInterface are passed as arguments, so we can use them inside the method.
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        // echo("SETTING SESSION...");
        $numDice = $request->request->get('num_dice');

        $hand = new DiceHand();
        for ($i = 1; $i <= $numDice; $i++) {
            $hand->add(new DiceGraphic());
        }
        $hand->roll();

        // Saves hand object to session
        $session->set("pig_dicehand", $hand);
        $session->set("pig_dice", $numDice);
        $session->set("pig_round", 0);
        $session->set("pig_total", 0);
        $session->set("pig_round_rolls", 1);
        $session->set("pig_total_rolls", 1);

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/play", name: "pig_play", methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response {
        // Gets hand object from session
        $dicehand = $session->get("pig_dicehand");

        $data = [
            "pigDice" => $session->get("pig_dice"),
            "pigRound" => $session->get("pig_round"),
            "pigTotal" => $session->get("pig_total"),
            "dicehand" => $dicehand->getString(),
            "totalRolls" => $session->get("pig_total_rolls"),
            "roundRolls" => $session->get("pig_round_rolls")
        ];

        return $this->render('game/pig/pig_play.html.twig', $data);
    }

    #[Route("/game/pig/roll", name: "pig_roll", methods: ['POST'])]
    public function roll(
        SessionInterface $session
    ): Response {
        $roundRolls = $session->get("pig_round_rolls");
        $totalRolls = $session->get("pig_total_rolls");
        $hand = $session->get("pig_dicehand");
        $hand->roll();

        $roundTotal = $session->get("pig_round");
        $round = 0;
        $values = $hand->getValues();
        foreach ($values as $value) {
            if ($value === 1) {
                $round = 0;
                $roundTotal = 0;
                $roundRolls = 0;
                $this->addFlash(
                    'warning',
                    'You got a 1 and you lost the round points!'
                );
                break;
            }
            $round += $value;
        }

        $session->set("pig_round", $roundTotal + $round);
        $session->set("pig_round_rolls", $roundRolls + 1);
        $session->set("pig_total_rolls", $totalRolls + 1);

        // remember to REDIRECT, not render.
        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/save", name: "pig_save", methods: ['POST'])]
    public function save(
        SessionInterface $session
    ): Response {
        $roundTotal = $session->get("pig_round");
        $gameTotal = $session->get("pig_total");
        $totalRolls = $session->get("pig_total_rolls");

        // Sets new values to session
        $session->set("pig_round", 0);
        $session->set("pig_total", $roundTotal + $gameTotal);
        $session->set("pig_round_rolls", 1);
        $session->set("pig_total_rolls", $totalRolls + 1);

        if ($gameTotal + $roundTotal >= 100) {
            return $this->redirectToRoute('pig_winner');
        }

        $this->addFlash(
            'notice',
            'Your round was saved to the total!'
        );

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/winner", name: "pig_winner", methods: ['GET'])]
    public function winner(
        SessionInterface $session
    ): Response {
        $data = [
            "pigTotal" => $session->get("pig_total"),
            "totalRolls" => $session->get("pig_total_rolls"),
        ];

        return $this->render('game/pig/pig_winner.html.twig', $data);
    }
}
