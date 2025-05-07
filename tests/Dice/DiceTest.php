<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice()
    {
        $die = new Dice();
        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $res = $die->getAsString();
        $this->assertNotEmpty($res);
    }

    /**
     * Assert dice value is in range 1-6
     * @return void
     */
    public function testRollDice(): void
    {
        $die = new Dice();

        $res = $die->roll();
        $this->assertLessThanOrEqual(6, $res);
        $this->assertGreaterThanOrEqual(1, $res);
    }

    /**
     * Assert dice value is in range 1-6
     * @return void
     */
    public function testGetValue(): void
    {
        $die = new Dice();

        $exp = $die->roll();
        $res = $die->getValue();
        $this->assertEquals($exp, $res);
        
    }


}