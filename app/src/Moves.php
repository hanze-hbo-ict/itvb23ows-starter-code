<?php

namespace app;

class Moves
{
    public static function playPiece(String $piece, String $toPosition, Game $game): void
    {
        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $boardTiles = $board->getBoardTiles();
        $hand = $player->getHand();
        $playerNumber = $player->getPlayerNumber();

        // todo errors anders? / Dit zijn geen echte errors, maar meer zetten die niet mogen
        if (!$hand[$piece]) {
            $_SESSION['error'] = "Player does not have tile";
        } elseif (isset($boardTiles[$toPosition])) {
            $_SESSION['error'] = 'Board position is not empty';
        } elseif (count($boardTiles) && !$board->pieceHasNeighbour($toPosition)) {
            $_SESSION['error'] = "board position has no neighbour";
        } elseif (array_sum($hand) < 11 && !$board->neighboursOfPieceAreTheSameColor($playerNumber, $toPosition)) {
            $_SESSION['error'] = "Board position has opposing neighbour";
        } elseif (array_sum($hand) <= 8 && $hand['Q']) {
            $_SESSION['error'] = 'Must play queen bee';
        } else {
            $board->addPiece($piece, $playerNumber, $toPosition);
            $player->removePieceFromHand($piece);
            $game->switchTurn();
            Database::addMoveToDatabase($game,"play", toPosition: $toPosition);

            //change last move to just done move
            $game->setLastMoveId(Database::getLastMoveId());
        }
    }

    public static function movePiece(String $fromPosition, String $toPosition, Game $game): void
    {
        //todo checken of stapelen werkt
        // errors tonen opeens niet meer, checken
        // although, errors moeten eigenlijk sowieso anders
        // nja, geen prio

        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $boardTiles = $board->getBoardTiles();

        $tile = array_pop($boardTiles[$fromPosition]);
        //check if move is legal
        if (self::moveTileIsLegal($board, $player, $fromPosition, $toPosition)) {
            if (isset($boardTiles[$toPosition])) {
                array_push($boardTiles[$toPosition], $tile);
            } else {
                $boardTiles[$toPosition] = [$tile];
            }
            Database::addMoveToDatabase($game, "move", toPosition: $toPosition, fromPosition: $fromPosition);
            $game->setLastMoveId(Database::getLastMoveId());
            $game->switchTurn();
        } else {
            if (isset($boardTiles[$fromPosition])) {
                array_push($boardTiles[$fromPosition], $tile);
            } else {
                $boardTiles[$fromPosition] = [$tile];
            }
        }
        //todo weet niet zeker of dit klopt, de boardTiles moeten iig veranderd worden naar de nieuwe situatie
        $board->setBoardTiles($boardTiles);
    }

    private static function moveTileIsLegal(Board $board, Player $player, $fromPosition, $toPosition): bool
    {
        $boardTiles = $board->getBoardTiles();
        $playerNumber = $player->getPlayerNumber();
        $hand = $player->getHand();

        unset($_SESSION['error']);

        return self::thereIsATileToMoveLegally($boardTiles, $hand, $playerNumber, $fromPosition) &&
            self::tileMoveWontSplitHive($board, $fromPosition) &&
            self::tileToMoveCanMove($board, $fromPosition, $toPosition);
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
        if ($tile[1] == "Q" || $tile[1] == "B") {
            if (!self::slide($board, $fromPosition, $toPosition)) {
                $_SESSION['error'] = 'Tile must slide';
                return false;
            }
        }
        return true;
    }

    public static function pass(Game $game): void
    {
        Database::addMoveToDatabase($game, "pass");
        $game->setLastMoveId(Database::getLastMoveId());
        $game->switchTurn();
    }

    public static function undoLastMove(Game $game): void
    {
        //todo bugfix & werkt niet als de vorige beurt ongeldig was? Hij gaf iig een error
        $result = Database::selectLastMoveFromGame($game);
        $game->setLastMoveId($result[5]);
        $game->setState($result[6], $game);
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
