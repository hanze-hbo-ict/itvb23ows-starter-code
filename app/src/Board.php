<?php

namespace app;

class Board
{
    // Board bestaat alleen uit tiles, niet uit alle beschikbare plekken
    private array $boardTiles;
    private array $offsets = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    public function __construct($boardTiles = [])
    {
        $this->boardTiles = $boardTiles;
    }

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

    public function neighboursOfPieceAreTheSameColor($player, $pieceOne): bool
    {
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

    public function getTilesFromPlayer($playerNumber): array
    {
        $boardTiles = $this->getBoardTiles();
        $playerBoardTiles = [];

        foreach ($boardTiles as $position => $tiles) {
            foreach ($tiles as $tile) {
                if ($tile[0] == $playerNumber) {
                    $playerBoardTiles[$position] = $tiles;
                }
            }
        }
        return $playerBoardTiles;
    }

    public function getPossiblePlayPositions($playerNumber, $hand): array {
        $offsets = $this->getOffsets();
        $boardTiles = $this->getBoardTiles();
        $possiblePlayPositions = [];

        foreach ($offsets as $offset) {
            foreach (array_keys($boardTiles) as $position) {
                $positionArray = explode(',', $position);
                $possiblePosition = ($offset[0] + $positionArray[0]).','.($offset[1] + $positionArray[1]);
                if (Rules::positionIsLegalToPlay($possiblePosition, $playerNumber, $hand, $this)) {
                    $possiblePlayPositions[] = $possiblePosition;
                }
            }
        }
        $possiblePlayPositions = array_unique($possiblePlayPositions);
        if (!count($possiblePlayPositions)) {
            $possiblePlayPositions[] = '0,0';
        }

        return $possiblePlayPositions;
    }

}
