<?php

namespace app;

class Rules
{
    public static function positionIsLegalToPlay(String $toPosition, int $playerNumber, array $hand, Board $board): bool
    {
        $boardTiles = $board->getBoardTiles();
        return
            self::boardPositionIsEmpty($boardTiles, $toPosition) &&
            self::boardPositionHasANeighbour($board, $boardTiles, $toPosition) &&
            self::boardPositionHasNoOpposingNeighbour($board, $hand, $playerNumber, $toPosition) &&
            self::queenBeeIsPlayedBeforeTurnFour($hand);
    }

    public static function positionIsLegalToMove(Board $board, Player $player, String $fromPosition, String $toPosition): bool
    {
        $boardTiles = $board->getBoardTiles();
        $playerNumber = $player->getPlayerNumber();
        $hand = $player->getHand();

        unset($_SESSION['error']);

        return self::thereIsATileToMoveLegally($boardTiles, $hand, $playerNumber, $fromPosition) &&
            self::tileMoveWontSplitHive($board, $fromPosition) &&
            self::tileToMoveCanMove($board, $fromPosition, $toPosition);

    }

    public static function tileNotInHand($hand, $piece): bool
    {
        try {
            if (!$hand[$piece]) {
                throw new RulesException("Player does not have tile in hand");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return false;
    }

    private static function boardPositionIsEmpty($boardTiles, $position): bool
    {
        try{
            if (isset($boardTiles[$position])) {
                throw new RulesException("Board position is not empty");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    private static function boardPositionHasANeighbour(Board $board, $boardTiles, $position): bool
    {
        try{
            if (count($boardTiles) && !$board->pieceHasNeighbour($position)) {
                throw new RulesException("Board position has no neighbour");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    private static function boardPositionHasNoOpposingNeighbour(Board $board, $hand, $playerNumber, $position): bool
    {
        try{
            if (array_sum($hand) < 11 && !$board->neighboursOfPieceAreTheSameColor($playerNumber, $position)) {
                throw new RulesException("Board position has opposing neighbour");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    private static function queenBeeIsPlayedBeforeTurnFour($hand): bool
    {
        try {
            if (array_sum($hand) <= 8 && array_key_exists("Q", $hand)) {
                throw new RulesException("Must play queen bee before turn four");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    public static function thereIsATileToMoveLegally($boardTiles, $hand, $playerNumber, $fromPosition): bool
    {
        return self::boardPositionIsNotEmpty($boardTiles, $fromPosition) &&
            self::tileIsOwnedByPlayer($boardTiles, $fromPosition, $playerNumber) &&
            self::handDoesNotContainQueen($hand);
    }

    public static function boardPositionIsNotEmpty($boardTiles, $position): bool
    {
        try {
            if (!isset($boardTiles[$position])) {
                throw new RulesException("Board position is empty");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    public static function tileIsOwnedByPlayer($boardTiles, $position, $playerNumber): bool
    {
        try {
            if ($boardTiles[$position][count($boardTiles[$position])-1][0] != $playerNumber) {
                throw new RulesException("Tile is not owned by player");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    public static function handDoesNotContainQueen($hand): bool
    {
        try {
            if ($hand['Q']) {
                throw new RulesException("Queen bee is not played");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    public static function tileMoveWontSplitHive(Board $board, $toPosition): bool
    {
        // todo var namen anders (begrijpen wat ermee bedoeld wordt)
        $boardTiles = $board->getBoardTiles();
        try{
            if (!$board->pieceHasNeighbour($toPosition)) {
                throw new RulesException("Move would split hive");
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
                    throw new RulesException("Move would split hive");
                }
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    public static function tileToMoveCanMove(Board $board, $fromPosition, $toPosition): bool
    {
        $boardTiles = $board->getBoardTiles();
        $tile = array_pop($boardTiles[$fromPosition]);

        return self::positionsAreNotTheSame($fromPosition, $toPosition) &&
            self::destinationTileIsEmpty($boardTiles, $toPosition, $tile) &&
            self::tileIsAbleToSlide($tile, $board, $fromPosition, $toPosition);
    }


    private static function positionsAreNotTheSame($fromPosition, $toPosition): bool
    {
        try {
            if ($fromPosition == $toPosition) {
                throw new RulesException("Tile must move");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    private static function destinationTileIsEmpty($boardTiles, $toPosition, $tile): bool
    {
        try {
            //todo tile[1] = B? check
            // tile needs to be like [0, "B"]
            if (isset($boardTiles[$toPosition]) && $tile[1] != "B"){
                throw new RulesException("Tile is not empty");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    private static function tileIsAbleToSlide($tile, $board, $fromPosition, $toPosition): bool
    {
        try{
            if (($tile[1] == "Q" || $tile[1] == "B") && !self::slide($board, $fromPosition, $toPosition)) {
                throw new RulesException("Tile is not able to slide");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    private static function len($tile): int
    {
        return $tile ? count($tile) : 0;
    }

    public static function slide(Board $board, $from, $to): bool
    {
        $boardTiles = $board->getBoardTiles();
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
        if (!$boardTiles[$common[0]] && !$boardTiles[$common[1]]
            && !$boardTiles[$from] && !$boardTiles[$to]) {
            return false;
        }
        return min(self::len($boardTiles[$common[0]]), self::len($boardTiles[$common[1]]))
            <= max(self::len($boardTiles[$from]), self::len($boardTiles[$to]));
    }

}
