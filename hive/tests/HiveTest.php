<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Lucas\Hive\Board;
use Lucas\Hive\Hand;
use Lucas\Hive\Hive;
use PHPUnit\Framework\TestCase;

class HiveTest extends TestCase
{
    public function testFromSession()
    {
        $hand = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $session = [
            'board' => [],
            'hand' => [$hand, $hand],
            'player' => 0,
            'game_id' => 1
        ];
        $hive = Hive::fromSession($session);
        $hands = $hive->getHands();

        $this->assertEquals($hand, $hands[0]->getHand());
        $this->assertEquals($hand, $hands[1]->getHand());
        $this->assertEquals(0, $hive->getPlayer());
        $this->assertEquals(1, $hive->getGameId());
        $this->assertEquals([], $hive->getBoard()->getBoard());
    }

    public function testGetOtherPlayer()
    {
        $hive = new Hive(player: 0);
        $this->assertEquals(1, $hive->getOtherPlayer());
        $hive = new Hive(player: 1);
        $this->assertEquals(0, $hive->getOtherPlayer());
    }

    public function testGetHands()
    {
        $hand = new Hand(["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]);
        $hands = [$hand, $hand];
        $hive = new Hive(hands: $hands);

        $this->assertEquals($hands, $hive->getHands());
    }

    public function testGetPlayerHand()
    {
        $hand = new Hand(["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]);
        $hand1 = new Hand(["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]);
        $hive = new Hive(player: 0, hands: [$hand, $hand1]);

        $this->assertEquals($hand, $hive->getPlayerHand());
    }

    public function testGetGameId()
    {
        $hive = new Hive(gameId: 1);
        $this->assertEquals(1, $hive->getGameId());
    }

    public function testGetPlayer()
    {
        $hive = new Hive(player: 1);
        $this->assertEquals(1, $hive->getPlayer());
    }

    public function testGetBoard()
    {
        $board = new Board([]);
        $hive = new Hive(board: $board);

        $this->assertEquals($board, $hive->getBoard());
    }

    public function testGetValidPositions()
    {
        $emptyBoard = new Hive(new Board([]), player: 0);
        $this->assertEquals(
            ['0,0'], $emptyBoard->getValidPositions());

        $nonEmptyCheck = new Hive(new Board(['0,0' => [[0, 'Q']]]), player: 1);
        $nonEmptyTiles = ['0,1', '0,-1', '1,0', '-1,0', '-1,1', '1,-1'];
        $this->assertEquals($nonEmptyTiles, $nonEmptyCheck->getValidPositions());

        $neighboursBoard = new Board(['0,0' => [[0, 'Q']], '1,0' => [[1, 'Q']]]);
        $hands = [new Hand(['Q' => 0, 'B' => 2, 'S' => 2, 'A' => 3, 'G' => 3,]), new Hand(['Q' => 0, 'B' => 2, 'S' => 2, 'A' => 3, 'G' => 3])];
        $neighboursCheck = new Hive($neighboursBoard, player: 0, hands: $hands);
        $validTiles = ['0,-1', '-1,0', '-1,1'];
        $this->assertEquals($validTiles, $neighboursCheck->getValidPositions());
    }
}
