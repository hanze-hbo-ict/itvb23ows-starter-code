<?php

namespace app;

class Moves
{
    //todo deze functies herschrijven
    public static function playPiece(String $piece, String $toPosition, Game $game): void
    {
        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $boardTiles = $board->getBoardTiles();
        $hand = $player->getHand();
        $playerNumber = $player->getPlayerNumber();

        // todo errors anders?
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
            Database::addMoveToDatabase($game, $toPosition, "play");

            //change last move to just done move
            $game->setLastMoveId(Database::getLastMoveId());
        }
    }

    public static function movePiece(String $fromPosition, String $toPosition, Game $game): void
    {
        //todo deze functie herschrijven

        $player = $game->getCurrentPlayer();
        $playerNumber = $player->getPlayerNumber();
        $hand = $player->getHand();
        $board = $game->getBoard();
        $boardTiles = $board->getBoardTiles();
        unset($_SESSION['error']);

        if (!isset($boardTiles[$fromPosition])) {
            $_SESSION['error'] = 'Board position is empty';
        }
        elseif ($boardTiles[$fromPosition][count($boardTiles[$fromPosition])-1][0] != $playerNumber) {
            $_SESSION['error'] = "Tile is not owned by player";
        }
        elseif ($hand['Q']) {
            $_SESSION['error'] = "Queen bee is not played";
        }
        else {
            $tile = array_pop($boardTiles[$fromPosition]);
            if (!$board->pieceHasNeighbour($toPosition)) {
                $_SESSION['error'] = "Move would split hive";
            } else {
                $all = array_keys($boardTiles);
                $queue = [array_shift($all)];
                while ($queue) {
                    $next = explode(',', array_shift($queue));
                    foreach ($GLOBALS['OFFSETS'] as $pq) {
                        list($p, $q) = $pq;
                        $p += $next[0];
                        $q += $next[1];
                        if (in_array("$p,$q", $all)) {
                            $queue[] = "$p,$q";
                            $all = array_diff($all, ["$p,$q"]);
                        }
                    }
                }
                if ($all) {
                    $_SESSION['error'] = "Move would split hive";
                } else {
                    if ($fromPosition == $toPosition) {
                        $_SESSION['error'] = 'Tile must move';
                    } elseif (isset($boardTiles[$toPosition]) && $tile[1] != "B") {
                        $_SESSION['error'] = 'Tile not empty';
                    } elseif ($tile[1] == "Q" || $tile[1] == "B") {
                        if (!slide($boardTiles, $fromPosition, $toPosition)) {
                            $_SESSION['error'] = 'Tile must slide';
                        }
                    }
                }
            }
            if (isset($_SESSION['error'])) {
                if (isset($boardTiles[$fromPosition])) {
                    array_push($boardTiles[$fromPosition], $tile);
                } else {
                    $boardTiles[$fromPosition] = [$tile];
                }
            } else {
                if (isset($boardTiles[$toPosition])) {
                    array_push($boardTiles[$toPosition], $tile);
                } else {
                    $boardTiles[$toPosition] = [$tile];
                }
                $game->switchTurn();

                Database::addMoveToDatabase($game, $toPosition, "play", fromPosition: $fromPosition);

                $game->setLastMoveId(Database::getLastMoveId());
            }
            //todo weet niet zeker of dit klopt
            $board->setBoardTiles($boardTiles);
        }
    }

    public function pass() {
        //todo
    }



    private function len($tile): int
    {
        return $tile ? count($tile) : 0;
    }

    //todo check en herschrijf dit met gebruik van Board
    function slide($board, $from, $to): bool
    {
        if ((!hasNeighbour($to, $board)) || (!isNeighbour($from, $to))){
            return false;
        }

        $b = explode(',', $to);
        $common = [];
        foreach ($GLOBALS['OFFSETS'] as $pq) {
            $p = $b[0] + $pq[0];
            $q = $b[1] + $pq[1];
            if (isNeighbour($from, $p.",".$q)) {
                $common[] = $p.",".$q;
            }
        }
        if (!$board[$common[0]] && !$board[$common[1]] && !$board[$from] && !$board[$to]) {
            return false;
        }
        return min(len($board[$common[0]]), len($board[$common[1]])) <= max(len($board[$from]), len($board[$to]));
    }



}