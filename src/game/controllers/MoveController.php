<?php

namespace controllers;

use objects\Board;
use database\DatabaseService;

class MoveController
{
    private $from;
    private $to;
    private $player;
    private Board $board;
    private $hand;
    private DatabaseService $database;

    public function __construct($from, $to, Board $board, DatabaseService $database)
    {
        $this->from = $from;
        $this->to = $to;
        $this->player = $_SESSION['player'];
        $this->board = $board;
        $this->hand = $_SESSION['hand'][$this->player];
        $this->database = $database;

    }

    public function executeMove()
    {
        unset($_SESSION['error']);

        $thisBoard = $this->board->getBoard();
        if (!isset($thisBoard[$this->from])) {
            $_SESSION['error'] = 'Board position is empty';
        } elseif ($thisBoard[$this->from][count($thisBoard[$this->from])-1][0] != $this->player) {
            $_SESSION['error'] = "Tile is not owned by player";
        } elseif ($this->hand['Q']) {
            $_SESSION['error'] = "Queen bee is not played";
        } else {
            $tile = array_pop($thisBoard[$this->from]);

            if (!$this->board->hasNeighBour($this->to)) {
                $_SESSION['error'] = "Move would split hive";
            } else {
                $all = array_keys($thisBoard);
                $queue = [array_shift($all)];

                while ($queue) {
                    $next = explode(',', array_shift($queue));
                    foreach ($this->board->getOffset() as $pq) {
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
                    if ($this->from == $this->to) {
                        $_SESSION['error'] = 'Tile must move';
                    } elseif (isset($thisBoard[$this->to]) && $tile[1] != "B") {
                        $_SESSION['error'] = 'Tile not empty';
                    } elseif ($tile[1] == "Q" || $tile[1] == "B") {
                        if (!$this->board->slide($this->from, $this->to)) {
                            $_SESSION['error'] = 'Tile must slide';
                        }
                    }
                }
            }

            if (isset($_SESSION['error'])) {
                if (isset($thisBoard[$this->from])) {
                    array_push($thisBoard[$this->from], $tile);
                } else {
                    $thisBoard[$this->from] = [$tile];
                }
            } else {
                if (isset($thisBoard[$this->to])) {
                    array_push($thisBoard[$this->to], $tile);
                } else {
                    $thisBoard[$this->to] = [$tile];
                }

                $_SESSION['player'] = 1 - $_SESSION['player'];
                unset($thisBoard[$this->from]);
                $lastMove = $this->database->move($_SESSION['game_id'], $this->from, $this->to, $_SESSION['last_move']);
                $_SESSION['last_move'] = $lastMove;
            }
        }

        $_SESSION['board'] = $thisBoard;
    }
}

