<?php

namespace objects;

class Board {
    private array $offset;
    private array $board;

    public function __construct($board)
    {
        $this->offset = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];
        $this->board = $board;
    }

    public function getBoard(): array
    {
        return $this->board;
    }

    public function getOffset(): array
    {
        return $this->offset;
    }

    public function setBoard(array $board): void
    {
        $this->board = $board;
    }

    public function getPossiblePositions()
    {
        $to = [];
        foreach ($this->getOffset() as $pq) {
            foreach (array_keys($this->board) as $pos) {
                $pq2 = explode(',', $pos);
                $to[] = ($pq[0] + $pq2[0]).','.($pq[1] + $pq2[1]);
            }
        }
        $to = array_unique($to);
        if (!count($to)) $to[] = '0,0';
        return $to;
    }

    public function hasNeighbour($to, $board): bool
    {
        foreach (array_keys($board) as $b) {
            if ($this->isNeighbour($to, $b)) return true;
        }
        return false;
    }

    public function neighboursAreSameColor($player, $to): bool
    {
        foreach ($this->board as $b => $st) {
            if (!$st) continue;
            $c = $st[count($st) - 1][0];
            if ($c != $player && $this->isNeighbour($to, $b)) return false;
        }
        return true;
    }

    public function slide($from, $to, $board): bool
    {
        if (!$this->hasNeighbour($to, $board)) return false;
        if (!$this->isNeighbour($from, $to)) return false;
        $b = explode(',', $to);
        $common = [];
        foreach ($this->offset as $pq) {
            $p = $b[0] + $pq[0];
            $q = $b[1] + $pq[1];
            if ($this->isNeighbour($from, $p.",".$q)) $common[] = $p.",".$q;
        }
        if (
            (!isset($board[$common[0]]) || !$board[$common[0]]) &&
            (!isset($board[$common[1]]) || !$board[$common[1]]) &&
            (!isset($board[$from]) || !$board[$from]) &&
            (!isset($board[$to]) || !$board[$to])
        ) {
            return false;
        }
        return min($this->len($board[$common[0]]), $this->len($board[$common[1]])) <= max($this->len($board[$from]), $this->len($board[$to]));
    }

    private function isNeighbour($a, $b): bool
    {
        $a = explode(',', $a);
        $b = explode(',', $b);
        if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) return true;
        if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) return true;
        if ($a[0] + $a[1] == $b[0] + $b[1]) return true;
        return false;
    }

    private function len($tile): int
    {
        return $tile ? count($tile) : 0;
    }
}

?>