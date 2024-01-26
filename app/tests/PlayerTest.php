<?php

use app\Player;

class PlayerTest extends PHPUnit\Framework\TestCase
{
    public function testTest(): void
    {
        $s = "abc";                    // arrange
        $len = strlen($s);             // act
        $this->assertEquals(3, $len);  // assert
    }

    public function testGivenPieceOfTypeWhenMoreThanOneAndRemovedFromHandThenCountValueLowers(): void
    {
        $hand = ["Q" => 1, "B" => 2];
        $player = new Player(0, $hand);
        $player->removePieceFromHand("B");
        $this->assertEquals(1, $player->getHand()["B"]);
    }

    public function testGivenLastPieceOfTypeWhenRemovedFromHandThenKeyNotInHand(): void
    {
        $hand = ["Q" => 1, "B" => 2];
        $player = new Player(0, $hand);
        $player->removePieceFromHand("Q");
        $this->assertEquals(["B" => 2], $player->getHand());
    }

}