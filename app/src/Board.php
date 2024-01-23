<?php

namespace app;

class Board
{
    // Board bestaat alleen uit pieces, niet uit beschikbare plekken
    private array $board = [];

    public function getBoard(): array
    {
        return $this->board;
    }

    public function setBoard(array $board): void
    {
        $this->board = $board;
    }

    //todo logica van util

    function pieceHasNeighbour($pieceOne): bool
    {
        foreach (array_keys($this->board) as $pieceTwo) {
            if (isNeighbour($pieceOne, $pieceTwo)) {
                return true;
            }
        }
        return false;
    }

    function pieceIsNeighbourOf($pieceOne, $pieceTwo): bool {
        {
            $pieceOne = explode(',', $pieceOne);
            $pieceTwo = explode(',', $pieceTwo);
            return
                ($pieceOne[0] == $pieceTwo[0] && abs($pieceOne[1] - $pieceTwo[1]) == 1) ||
                ($pieceOne[1] == $pieceTwo[1] && abs($pieceOne[0] - $pieceTwo[0]) == 1) ||
                ($pieceOne[0] + $pieceOne[1] == $pieceTwo[0] + $pieceTwo[1]);
        }
    }

    function neighboursOfPieceAreTheSameColor($player, $pieceOne) {
        foreach ($this as $pieceTwo => $st) {
            //todo wat is st?
            if (!$st) {
                continue;
            }
            //todo wat is c?
            $c = $st[count($st) - 1][0];
            if ($c != $player && isNeighbour($pieceOne, $pieceTwo)) {
                return false;
            }
        }
        return true;

    }



}