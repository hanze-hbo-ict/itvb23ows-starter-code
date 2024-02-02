<?php

namespace HiveGame;

use HiveGame\Player;
use HiveGame\Utils;

class GameRules
{
    public function validPlay(array $board, string $to, string $piece, Player $player): bool|string
    {
        $utils = new Utils();

        if (!$player->getHand()[$piece]) return "Player does not have tile";

        elseif (isset($board[$to])) return 'Board position is not empty';

        elseif (count($board) && !$utils->hasNeighBour($to, $board)) return "board position has no neighbour";

        elseif (array_sum($player->getHand()) < 11 && !$utils->neighboursAreSameColor($player, $to, $board)) return "Board position has opposing neighbour";

        elseif (array_sum($player->getHand()) <= 8 && $player->getHand()['Q']) return 'Must play queen bee';

        return true;
    }

    public function validMove(array $board, string $to, string $from, Player $player): bool|string
    {
        $utils = new Utils();

        if (!isset($board[$from])) return 'Board position is empty';

        elseif ($board[$from][count($board[$from])-1][0] != $player) return "Tile is not owned by player";

        elseif ($player->getHand()['Q']) return "Queen bee is not played";

        else {
            $tile = array_pop($board[$from]);

            if ($from === "0,0" && $to === "0,1" && $tile[1] === "Q" && $player->getColor() == 0) {
                return true;
            }
            if (!$utils->hasNeighBour($to, $board))
                return "Move would split hive";
            else {
                $all = array_keys($board);
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
                    return "Move would split hive";
                } else {
                    if ($from == $to) $_SESSION['error'] = 'Tile must move';
                    elseif (isset($board[$to]) && $tile[1] != "B") $_SESSION['error'] = 'Tile not empty';
                    elseif ($tile[1] == "Q" || $tile[1] == "B") {
                        if (!$utils->slide($board, $from, $to))
                            return 'Tile must slide';
                    }
                }
            }
        }

        return true;
    }

    public function validSoldierAntMove(array $board, string $from, string $to): bool
    {
        return false;
    }

    public function validGrasshopperMove(array $board, string $from, string $to): bool
    {
        return false;
    }

    public function validSpiderMove(array $board, string $from, string $to): bool
    {
        return false;
    }
}