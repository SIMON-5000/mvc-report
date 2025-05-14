<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use SessionIdInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Test cases for the game handler class: Game21Handler.
 */
class Game21HandlerTest extends TestCase
{
    // A mocked RequestStack to get session from, similar to corresponding "real" class.
    /** @var RequestStack&\PHPUnit\Framework\MockObject\MockObject $stubRequestStack */
    private $stubRequestStack;

    /** @var SessionInterface&\PHPUnit\Framework\MockObject\MockObject $stubSession */
    private $stubSession;

    /** @var  array<string, CardsHand|CardsDeck> : empty-array */
    private $mockSessionArray;

    /**
     * Prepare the mock-session and it's return values.
     * @return void
     */
    public function setUp(): void
    {
        // Mock the session flow of game handler class:
        $this->mockSessionArray = [];
        $this->stubRequestStack = $this->createMock(RequestStack::class);
        $this->stubSession = $this->createMock(SessionInterface::class);

        // ->getSession() returns a mock SessionInterface.
        $this->stubRequestStack
            ->method('getSession')
            ->willReturn($this->stubSession);


        // Obtruce way of giving values to hand instead of adding a method in hand-class.
        $deck = new CardsDeck();
        $deck->createFromArray([["suit" => "hearts", "rank" => "3" ]]);
        $playerHand = new CardsHand();
        $playerHand->drawCardToHand($deck);

        $deck->createFromArray([["suit" => "hearts", "rank" => "2" ]]);
        $bankHand = new CardsHand();
        $bankHand->drawCardToHand($deck);

        // Callback instead of valueMap, because I didn't get it to work.
        // https://docs.phpunit.de/en/9.6/test-doubles.html#test-doubles-stubs-examples-stubtest6-php
        // https://stackoverflow.com/questions/74603121/phpunit-how-can-i-use-a-mapping-for-method-arguments
        // Not [expectations].
        // Here I could use $this_>mockSessionArray. But this works and is strictly controlled.
        $this->stubSession->method('get')
            ->willReturnCallback(function (string $arg) use ($playerHand, $bankHand, $deck) {
                if ($arg === 'playerHand') {
                    return $playerHand;
                } elseif ($arg === 'bankHand') {
                    return $bankHand;
                } elseif ($arg === 'gameDeck') {
                    return $deck;
                }
                return null;
            });

        // var_dump($this->stubSession->get("playerHand"));

        // Jag kunde läsa från array:en också I method('get').
        $this->stubSession->method('set')
            ->willReturnCallback(function (string $arg) use ($playerHand, $bankHand, $deck) {
                if ($arg === 'playerHand') {
                    $this->mockSessionArray['playerHand'] = $playerHand;
                } elseif ($arg === 'bankHand') {
                    $this->mockSessionArray['bankHand'] = $bankHand;
                } elseif ($arg === 'gameDeck') {
                    $this->mockSessionArray['gameDeck'] = $deck;
                }
                return null;
            });

    }

    /**
     * Test to initialize game and confirm it saves a Deck and two hands to session.
     * @return void
     */
    public function testInit(): void
    {
        $game = new Game21Handler($this->stubRequestStack);
        $deck = new CardsDeck();
        $playerHand = new CardsHand();
        $bankHand = new CardsHand();

        $game->initGame($deck, $playerHand, $bankHand);
        // var_dump($this->stubSession);

        $mockSession = $this->mockSessionArray;

        $this->assertEquals(3, count($mockSession));
        $this->assertInstanceOf(CardsDeck::class, $mockSession['gameDeck']);
        $this->assertInstanceOf(CardsHand::class, $mockSession['bankHand']);
        $this->assertInstanceOf(CardsHand::class, $mockSession['playerHand']);
    }

    /**
     * Testing getGame.
     * Method should get 1 Deck and 2 CardsHand objects from session.
     * @return void
     */
    public function testGetGame(): void
    {
        $game = new Game21Handler($this->stubRequestStack);

        [$deck, $pHand, $bHand] = $game->getGame();

        $this->assertInstanceOf(CardsDeck::class, $deck);
        $this->assertInstanceOf(CardsHand::class, $pHand);
        $this->assertInstanceOf(CardsHand::class, $bHand);
    }

    /**
     * Test playerDraw asserts that a card with a value has been added to hand,
     * and that a card has been removed from the deck.
     * @return void
     */
    public function testPlayerDraw(): void
    {
        $game = new Game21Handler($this->stubRequestStack);

        /** @var CardsDeck $deck
         * @var CardsHand $hand */
        [$deck, $hand ] = $game->getGame();
        $deck->fillDeck();
        $preHandValue = $hand->getHandValue();
        $preDeckSize = $deck->deckSize();

        // Draws a card from deck to hand and saves in session.
        $game->playerDraw();
        /** @var CardsHand $postHand */
        $postHand = $this->mockSessionArray['playerHand'];

        // GetHandValue does not get from session, just calculates.
        $this->assertGreaterThan($preHandValue, $postHand->getHandValue());
        $this->assertLessThan($preDeckSize, $deck->deckSize());
    }

    /**
     * Test bankPlays, see that cards are drawn and that the value is
     * greater than or equal to 17.
     * @return void
     */
    public function testBankPlays(): void
    {
        $game = new Game21Handler($this->stubRequestStack);

        /**
         * @var CardsDeck $deck
         * @var CardsHand $bankHand
         */
        [$deck, , $bankHand ] = $game->getGame();
        $deck->fillDeck();

        $preHandValue = $bankHand->getHandValue();
        $preDeckSize = $deck->deckSize();

        // Draws a card from deck to hand and saves in session.
        $game->bankPlays();

        /** @var CardsHand $postHand */
        $postHand = $this->mockSessionArray['bankHand'];

        $this->assertGreaterThan($preHandValue, $postHand->getHandValue());
        $this->assertGreaterThanOrEqual(17, $postHand->getHandValue());
        $this->assertLessThan($preDeckSize, $deck->deckSize());
    }

    /**
     * Testing calculate 21 score:
     * Create and draw two aces from deck,
     * assert that one ace is = 14 and two is 15
     * @return void
     */
    public function testCalculate21Score(): void
    {
        $game = new Game21Handler($this->stubRequestStack);

        $deck = new CardsDeck();
        $deck->createFromArray([["suit" => "hearts", "rank" => "A" ], ["suit" => "spades", "rank" => "A" ]]);

        $hand = new CardsHand();
        $hand->drawCardToHand($deck);

        $res1 = $game->calculate21Score($hand);

        $hand->drawCardToHand($deck);

        $res2 = $game->calculate21Score($hand);


        $this->assertEquals(14, $res1);
        $this->assertEquals(15, $res2);
    }

    /**
     * Testing isBust:
     * assert that hand holding one Queen (12) is not bust and that
     *  hand holding two Queens (24) is bust.
     * @return void
     */
    public function testIsBust(): void
    {
        $game = new Game21Handler($this->stubRequestStack);

        $deck = new CardsDeck();
        $deck->createFromArray([["suit" => "hearts", "rank" => "Q" ], ["suit" => "spades", "rank" => "Q" ]]);

        $hand = new CardsHand();
        $hand->drawCardToHand($deck);
        $res1 = $game->isBust($hand);

        $hand->drawCardToHand($deck);
        $res2 = $game->isBust($hand);


        $this->assertEquals(false, $res1);
        $this->assertEquals(true, $res2);
    }

    /**
     * Testing getWinner:
     * Setup for three outcomes:
     * 1. Player by default holds a 3, bank a 2
     *      Expects Player to win.
     * 2. Bank draws a King
     *      Expects Bank to win.
     * 3. Bank draws a second king.
     *      Expects bBank to be bust; Player wins.
     * @return void
     */
    public function testGetWinner(): void
    {
        $game = new Game21Handler($this->stubRequestStack);

        // Player wins handvalue 3 vs 2
        $exp = "Du";
        $res = $game->getWinner();

        $playerHand = $game->getPlayerHand();
        $bankHand = $game->getBankHand();
        $card = new CardGraphic("hearts", "K");
        $bankHand->add($card);

        $res2 = $game->getWinner($playerHand, $bankHand);
        $exp2 = "Banken";

        $bankHand->add($card);

        $res3 = $game->getWinner($playerHand, $bankHand);
        $exp3 = "Du";

        $this->assertEquals($exp, $res);
        $this->assertEquals($exp2, $res2);
        $this->assertEquals($exp3, $res3);

    }

    public function testGetPlayerHand(): void
    {
        $game = new Game21Handler($this->stubRequestStack);

        $this->assertInstanceOf("\App\Card\Game21Handler", $game);

        $playerHand = $game->getPlayerHand();
        // $game->initGame($deck, $playerHand, $bankHand);
        // var_dump($this->stubSession);

        $this->assertInstanceOf(CardsHand::class, $playerHand);
    }
}
