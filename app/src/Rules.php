<?php

namespace app;

class Rules
{
    public static function pieceIsLegalToPlay(String $piece, String $toPosition, int $playerNumber, array $hand, Board $board): bool
    {
        $boardTiles = $board->getBoardTiles();

        return !(
            self::tileNotInHand($hand, $piece) ||
            self::boardPositionIsNotEmpty($boardTiles, $toPosition) ||
            self::boardPositionHasNoNeighbour($board, $boardTiles, $toPosition) ||
            self::boardPositionHasOpposingNeighbour($board, $hand, $playerNumber, $toPosition) ||
            self::queenBeeMustBePlayedBeforeTurnFour($hand)
        );
    }

    public static function pieceIsLegalToMove(Board $board, Player $player, String $fromPosition, String $toPosition): bool
    {
        $boardTiles = $board->getBoardTiles();
        $playerNumber = $player->getPlayerNumber();
        $hand = $player->getHand();

        unset($_SESSION['error']);

        return self::thereIsATileToMoveLegally($boardTiles, $hand, $playerNumber, $fromPosition) &&
            self::tileMoveWontSplitHive($board, $fromPosition) &&
            self::tileToMoveCanMove($board, $fromPosition, $toPosition);
    }

    private static function tileNotInHand($hand, $piece): bool
    {
        if (!$hand[$piece]) {
            $_SESSION['error'] = "Player does not have tile";
            return true;
        }
        return false;
    }

    private static function boardPositionIsNotEmpty($boardTiles, $position): bool
    {
        if (isset($boardTiles[$position])) {
            $_SESSION['error'] = 'Board position is not empty';
            return true;
        }
        return false;
    }

    private static function boardPositionHasNoNeighbour(Board $board, $boardTiles, $position): bool
    {
        if (count($boardTiles) && !$board->pieceHasNeighbour($position)) {
            $_SESSION['error'] = "board position has no neighbour";
            return true;
        }
        return false;
    }

    private static function boardPositionHasOpposingNeighbour(Board $board, $hand, $playerNumber, $position): bool
    {
        if (array_sum($hand) < 11 && !$board->neighboursOfPieceAreTheSameColor($playerNumber, $position)) {
            $_SESSION['error'] = "Board position has opposing neighbour";
            return true;
        }
        return false;
    }

    private static function queenBeeMustBePlayedBeforeTurnFour($hand): bool
    {
        if (array_sum($hand) <= 8 && $hand['Q']) {
            $_SESSION['error'] = 'Must play queen bee';
            return true;
        }
        return false;
    }



    private static function thereIsATileToMoveLegally($boardTiles, $hand, $playerNumber, $fromPosition): bool
    {
        return !(self::boardPositionIsEmpty($boardTiles, $fromPosition) ||
            self::tileIsNotOwnedByPlayer($boardTiles, $fromPosition, $playerNumber) ||
            self::handContainsQueen($hand));
    }

    private static function boardPositionIsEmpty($boardTiles, $position): bool
    {
        if (!isset($boardTiles[$position])) {
            $_SESSION['error'] = 'Board position is empty';
            return false;
        }
        return true;
    }

    private static function tileIsNotOwnedByPlayer($boardTiles, $position, $playerNumber): bool
    {
        if ($boardTiles[$position][count($boardTiles[$position])-1][0] != $playerNumber) {
            $_SESSION['error'] = "Tile is not owned by player";
            return false;
        }
        return true;
    }

    private static function handContainsQueen($hand): bool
    {
        if ($hand['Q']) {
            $_SESSION['error'] = "Queen bee is not played";
            return false;
        }
        return true;
    }

    private static function tileMoveWontSplitHive(Board $board, $toPosition): bool
    {
        // todo var namen anders (begrijpen wat ermee bedoeld wordt)
        $boardTiles = $board->getBoardTiles();
        if (!$board->pieceHasNeighbour($toPosition)) {
            $_SESSION['error'] = "Move would split hive";
            return false;
        } else {
            $allTiles = array_keys($boardTiles);
            $queue = [array_shift($allTiles)];
            while ($queue) {
                $next = explode(',', array_shift($queue));
                foreach ($board->getOffsets() as $offset) {
                    list($p, $q) = $offset;
                    $p += $next[0];
                    $q += $next[1];
                    if (in_array("$p,$q", $allTiles)) {
                        $queue[] = "$p,$q";
                        $allTiles = array_diff($allTiles, ["$p,$q"]);
                    }
                }
            }
            if ($allTiles) {
                $_SESSION['error'] = "Move would split hive";
                return false;
            }
        }
        return true;
    }

    private static function tileToMoveCanMove(Board $board, $fromPosition, $toPosition): bool
    {
        $boardTiles = $board->getBoardTiles();
        $tile = array_pop($boardTiles[$fromPosition]);

        return !(self::positionsAreTheSame($fromPosition, $toPosition)||
            self::tileIsNotEmpty($boardTiles, $toPosition, $tile) ||
            self::tileMustSlide($tile, $board, $fromPosition, $toPosition));
    }

    private static function positionsAreTheSame($fromPosition, $toPosition): bool
    {
        if ($fromPosition == $toPosition) {
            $_SESSION['error'] = 'Tile must move';
            return false;
        }
        return true;
    }

    private static function tileIsNotEmpty($boardTiles, $toPosition, $tile): bool
    {
        if (isset($boardTiles[$toPosition]) && $tile[1] != "B"){
            $_SESSION['error'] = 'Tile not empty';
            return false;
        }
        return true;
    }

    private static function tileMustSlide($tile, $board, $fromPosition, $toPosition): bool
    {
        if (($tile[1] == "Q" || $tile[1] == "B") && !self::slide($board, $fromPosition, $toPosition)) {
            $_SESSION['error'] = 'Tile must slide';
            return false;
        }
        return true;
    }

    private static function len($tile): int
    {
        return $tile ? count($tile) : 0;
    }

    private static function slide(Board $board, $from, $to): bool
    {
        //todo herschrijven met logische var namen
        if ((!$board->pieceHasNeighbour($to)) || (!$board->pieceIsNeighbourOf($from, $to))){
            return false;
        }

        $b = explode(',', $to);
        $common = [];
        foreach ($board->getOffsets() as $pq) {
            $p = $b[0] + $pq[0];
            $q = $b[1] + $pq[1];
            if ($board->pieceIsNeighbourOf($from, $p.",".$q)) {
                $common[] = $p.",".$q;
            }
        }
        if (!$board[$common[0]] && !$board[$common[1]] && !$board[$from] && !$board[$to]) {
            return false;
        }
        return min(self::len($board[$common[0]]), self::len($board[$common[1]]))
            <= max(self::len($board[$from]), self::len($board[$to]));
    }

}