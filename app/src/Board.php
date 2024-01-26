<?php

namespace app;

class Board
{
    // Board bestaat alleen uit tiles, niet uit alle beschikbare plekken
    private array $boardTiles = [];
    private array $offsets = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    /**
     * Dit representeert de hexagon, de randen waar eventueel een tegel aankan.
     * @return array|array[]
     */
    public function getOffsets(): array
    {
        return $this->offsets;
    }

    /**
     * boardTile in boardTiles = [String position, [[int playerNumber, String $piece],[...],] ]
     * @return array
     */
    public function getBoardTiles(): array
    {
        return $this->boardTiles;
    }

    public function setBoardTiles(array $boardTiles): void
    {
        $this->boardTiles = $boardTiles;
    }

    public function addPiece(String $piece, int $playerNumber, String $toPosition): void
    {
        $this->boardTiles[$toPosition] = [[$playerNumber, $piece]];
    }

    //todo logica van util

    public function pieceHasNeighbour($pieceOne): bool
    {
        foreach (array_keys($this->boardTiles) as $pieceTwo) {
            if ($this->pieceIsNeighbourOf($pieceOne, $pieceTwo)) {
                return true;
            }
        }
        return false;
    }

    public function pieceIsNeighbourOf($pieceOne, $pieceTwo): bool {
        {
            $pieceOne = explode(',', $pieceOne);
            $pieceTwo = explode(',', $pieceTwo);
            return
                ($pieceOne[0] == $pieceTwo[0] && abs($pieceOne[1] - $pieceTwo[1]) == 1) ||
                ($pieceOne[1] == $pieceTwo[1] && abs($pieceOne[0] - $pieceTwo[0]) == 1) ||
                ($pieceOne[0] + $pieceOne[1] == $pieceTwo[0] + $pieceTwo[1]);
        }
    }

    public function neighboursOfPieceAreTheSameColor($player, $pieceOne) {
        foreach ($this->getBoardTiles() as $pieceTwo => $st) {
            //todo wat is st?
            if (!$st) {
                continue;
            }
            //todo wat is c?
            $c = $st[count($st) - 1][0];
            if ($c != $player && $this->pieceIsNeighbourOf($pieceOne, $pieceTwo)) {
                return false;
            }
        }
        return true;
    }

}
