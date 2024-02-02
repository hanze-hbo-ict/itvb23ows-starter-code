<?php


use HiveGame\Database;
use HiveGame\GameActions;
use HiveGame\GameState;
use PHPUnit\Framework\TestCase;

class GameActionsTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testNoUndoOnFirstMove() {
        $db = $this->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->getMock();

        $gameState = $this->getMockBuilder(GameState::class)
            ->getMock();

        $gameActions = new GameActions($db, $gameState);


        $gameState->expects($this->once())
            ->method('getLastMove')
            ->willReturn(0);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No previous move');

        $gameActions->undoMove();

    }

    /**
     * @throws Exception
     */
    public function testUndoLastMove() {
        $db = $this->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->getMock();

        $gameState = $this->getMockBuilder(GameState::class)
            ->getMock();

        $gameActions = new GameActions($db, $gameState);


        $gameState->expects($this->once())
            ->method('getLastMove')
            ->willReturn(123);

        $expectedMoveData = [
            "previous_id" => 456,
            "state" => "state",
        ];
        $db->expects($this->once())
            ->method('getMoves')
            ->with(123)
            ->willReturn($expectedMoveData);

        $gameState->expects($this->once())
            ->method('setLastMove')
            ->with(456);
        $gameActions->expects($this->once())
            ->method('setState')
            ->with("state");

        $gameActions->undoMove();

    }
}
