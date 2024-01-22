<?php

namespace app;

require_once(__DIR__ . "/database/Database.php");
require_once(__DIR__ . "/Board.php");
require_once(__DIR__ . "/Player.php");
use app\database\Database;

class Player
{
    private array $hand;
    private int $playerNumber;

    public function __construct($playerNumber)
    {
        $this->hand = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $this->playerNumber = $playerNumber;
    }

    public function getHand(): array
    {
        return $this->hand;
    }

    public function playPiece($piece, $toPosition, $board, $database) {
        //todo deze functie herschrijven
        if (!$this->hand[$piece]) {
            $_SESSION['error'] = "Player does not have tile";
        } elseif (isset($board[$toPosition])) {
            $_SESSION['error'] = 'Board position is not empty';
        } elseif (count($board) && !$board->pieceHasNeighbour($toPosition, $board)) {
            $_SESSION['error'] = "board position has no neighbour";
        } elseif (array_sum($this->hand) < 11 && !neighboursAreSameColor($this->playerNumber, $toPosition, $board)) {
            $_SESSION['error'] = "Board position has opposing neighbour";
        } elseif (array_sum($this->hand) <= 8 && $this->hand['Q']) {
            $_SESSION['error'] = 'Must play queen bee';
        } else {
            $_SESSION['board'][$toPosition] = [[$_SESSION['player'], $piece]];
            $_SESSION['hand'][$this->playerNumber][$piece]--;
            $_SESSION['player'] = 1 - $_SESSION['player'];
            $stmt = $database->getDatabase()->prepare('insert into moves
                (game_id, type, move_from, move_to, previous_id, state)
                values (?, "play", ?, ?, ?, ?)');
            $state = $database->getState();
            $stmt->bind_param('issis', $_SESSION['game_id'], $piece, $toPosition, $_SESSION['last_move'], $state);
            $stmt->execute();
            $_SESSION['last_move'] = $database->getDatabase()->insert_id;
        }
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