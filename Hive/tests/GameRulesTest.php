<?php

use HiveGame\GameRules;
use HiveGame\Player;
use PHPUnit\Framework\TestCase;

class GameRulesTest extends TestCase
{
    public function testQueenMovement() {
        $gameRules = $this->getMockBuilder(GameRules::class)
            ->disableOriginalConstructor()
            ->getMock();


        $initialBoard = ["0,0" => [0, "Q"], "1,0" => [1, "Q"]];
        $to = "0,1";
        $from = "0,0";

        $player = new Player(0);

        $gameRules->expects($this->once())
            ->method('validMove')
            ->with([$initialBoard, $to, $from, $player])
            ->willReturn(true);

    }

    public function testGrasshopperMovement()
    {
        $gameRules = new GameRules();
        $board = [
            '0,0' => [['player' => new Player(0), 'piece' => 'G']],
            '1,0' => [['player' => new Player(1), 'piece' => 'B']],
        ];

        //a
        $this->assertTrue($gameRules->validGrasshopperMove($board, '0,0', '2,0'));
        //b
        $this->assertFalse($gameRules->validGrasshopperMove($board, '0,0', '0,0'));
        //c
        $this->assertFalse($gameRules->validGrasshopperMove($board, '0,0', '0,1'));
        //d
        $this->assertFalse($gameRules->validGrasshopperMove($board, '0,0', '1,0'));
        //e
        $this->assertFalse($gameRules->validGrasshopperMove($board, '0,0', '1,0'));

    }

}
