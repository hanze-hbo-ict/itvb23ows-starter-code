<?php

use app\Board;

class BoardTest extends PHPUnit\Framework\TestCase
{
    public function testTest(): void
    {
        $s = "abc";                    // arrange
        $len = strlen($s);             // act
        $this->assertEquals(3, $len);  // assert
    }

    public function testGivenNoLegalPlayPositionsThenGetPossiblePlayPositionsReturnZeroZero() {
        $boardTiles = [];
        $board = new Board($boardTiles);
        $playerNumber = 0;
        $hand = ["Q" => 1];

        $possiblePlaypositions = $board->getPossiblePlayPositions($playerNumber, $hand);
        self::assertEquals(['0,0'], $possiblePlaypositions);
    }

    public function testGivenOneTileThenGetPossiblePlayPositionsReturnSixSides() {
        $boardTiles = ['0,0' => [[0, "Q"]]];
        $board = new Board($boardTiles);
        $playerNumber = 1;
        $hand = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];

        $possiblePlaypositions = $board->getPossiblePlayPositions($playerNumber, $hand);

        $expectedResult = [0 => '0,1', 1 => '0,-1', 2 => '1,0', 3 => '-1,0', 4 => '-1,1', 5 => '1,-1'];
        self::assertEquals($expectedResult, $possiblePlaypositions);
    }

    public function testGivenTwoTilesThenGetPossiblePlayPositionsReturnSides() {
        $boardTiles = ['0,0' => [[0, "Q"]], '0,1' => [[1, "Q"]]];
        $board = new Board($boardTiles);
        $playerNumber = 0;
        $hand = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];

        $possiblePlaypositions = $board->getPossiblePlayPositions($playerNumber, $hand);

        $expectedResult = [0 => '0,-1', 1 => '-1,0', 2 => '1,-1'];
        self::assertEquals($expectedResult, $possiblePlaypositions);
    }

    public function testGivenPlayerZeroWhenGetTilesFromPlayerReturnOnlyTilesFromPlayerZero() {
        $boardTiles = ['0,0' => [[0, "Q"]], '0,1' => [[1, "B"]], '0,-1' => [[0, "B"]],'0,2' => [[1, "S"]]];
        $board = new Board($boardTiles);

        $playerZeroTiles = $board->getTilesFromPlayer(0);

        $expectedResult = ['0,0' => [[0, "Q"]], '0,-1' => [[0, "B"]]];
        self::assertEquals($expectedResult, $playerZeroTiles);
    }


}