<?php

namespace HiveGame;

class Utils
{
    public function __construct()
    {
        $GLOBALS['OFFSETS'] = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];
    }

    public function isNeighbour($a, $b): bool
    {
        $a = explode(',', $a);
        $b = explode(',', $b);

        if (count($a) != 2 || count($b) != 2) {
            return false;
        }

        if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) return true;
        if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) return true;
        if ($a[0] + $a[1] == $b[0] + $b[1]) return true;

        return false;
    }

    public function hasNeighBour($a, $board) {
        foreach (array_keys($board) as $b) {
            if ($this->isNeighbour($a, $b)) return true;
        }
    }

    public function neighboursAreSameColor($player, $a, $board): bool
    {
        foreach ($board as $b => $st) {
            if (!$st) continue;
            $c = $st[count($st) - 1][0];
            if ($c != $player && $this->isNeighbour($a, $b)) return false;
        }
        return true;
    }

    public function len($tile): int
    {
        return $tile ? count($tile) : 0;
    }

    public function slide($board, $from, $to): bool
    {
        if (!$this->hasNeighBour($to, $board)) return false;
        if (!$this->isNeighbour($from, $to)) return false;
        $b = explode(',', $to);
        $common = [];
        foreach ($GLOBALS['OFFSETS'] as $pq) {
            $p = $b[0] + $pq[0];
            $q = $b[1] + $pq[1];
            if ($this->isNeighbour($from, $p.",".$q)) $common[] = $p.",".$q;
        }
        if (!$board[$common[0]] && !$board[$common[1]] && !$board[$from] && !$board[$to]) return false;
        return min($this->len($board[$common[0]]), $this->len($board[$common[1]])) <= max($this->len($board[$from]), $this->len($board[$to]));
    }

}