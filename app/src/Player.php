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

    public function getPlayerNumber(): int
    {
        return $this->playerNumber;
    }

    public function playPiece($board, $database) {
        //todo deze functie herschrijven (beurt wisselen enzo)

        $piece = $_POST['piece'];
        $toPosition = $_POST['toPosition'];

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

    public function movePiece(Board $board, Database $database) {
        //todo deze functie herschrijven (beurt wisselen enzo)

        $from = $_POST['from'];
        $toPosition = $_POST['toPosition'];

        $player = $this->playerNumber;
        $hand = $this->getHand();
        unset($_SESSION['error']);

        if (!isset($board->getBoard()[$from])) {
            $_SESSION['error'] = 'Board position is empty';
        }
        elseif ($board->getBoard()[$from][count($board[$from])-1][0] != $player) {
            $_SESSION['error'] = "Tile is not owned by player";
        }
        elseif ($hand['Q']) {
            $_SESSION['error'] = "Queen bee is not played";
        }
        else {
            $tile = array_pop($board->getBoard()[$from]);
            if (!$board->pieceHasNeighbour($toPosition)) {
                $_SESSION['error'] = "Move would split hive";
            } else {
                $all = array_keys($board->getBoard());
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
                    if ($from == $toPosition) {
                        $_SESSION['error'] = 'Tile must move';
                    } elseif (isset($board[$toPosition]) && $tile[1] != "B") {
                        $_SESSION['error'] = 'Tile not empty';
                    } elseif ($tile[1] == "Q" || $tile[1] == "B") {
                        if (!slide($board, $from, $toPosition)) {
                            $_SESSION['error'] = 'Tile must slide';
                        }
                    }
                }
            }
            if (isset($_SESSION['error'])) {
                if (isset($board[$from])) {
                    array_push($board[$from], $tile);
                } else {
                    $board[$from] = [$tile];
                }
            } else {
                if (isset($board[$toPosition])) {
                    array_push($board[$toPosition], $tile);
                } else {
                    $board[$toPosition] = [$tile];
                }
                $_SESSION['player'] = 1 - $_SESSION['player'];
                $db = new Database();
                $stmt = $db->getDatabase()->prepare('insert into moves
                    (game_id, type, move_from, move_to, previous_id, state)
                    values (?, "move", ?, ?, ?, ?)');
                $state = $db->getState();
                $stmt->bind_param('issis', $_SESSION['game_id'], $from, $toPosition, $_SESSION['last_move'], $state);
                $stmt->execute();
                $_SESSION['last_move'] = $db->getDatabase()->insert_id;
            }
            $_SESSION['board'] = $board;
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