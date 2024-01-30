<?php

namespace Lucas\Hive;

class Board
{
    public static $OFFSETS = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    private array $board;

    public function __construct(array $board = [])
    {
        $this->board = $board;
    }

    public function getLastTile($position)
    {
        return end($this->board[$position]);
    }

    public function allTiles()
    {
        return array_keys($this->board);
    }

    public function emptyTile(string $position): bool
    {
        return !isset($this->board[$position]);
    }

    public function setTile(string $position, string $piece, int $player)
    {
        $this->board[$position] = array(array($player, $piece));
    }

    public function pushTile(string $position, string $piece, int $player)
    {
        array_push($this->board[$position], array($player, $piece));
    }

    public function popTile(string $position): array
    {
        return array_pop($this->board[$position]);
    }

    public function getNonEmptyTiles()
    {
        return array_filter($this->board, function ($tileStack) {
            return !empty($tileStack);
        });
    }

    public static function isNeighbour($a, $b)
    {
        $a = explode(',', $a);
        $b = explode(',', $b);
        if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) {
            return true;
        }
        if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) {
            return true;
        }
        if ($a[0] + $a[1] == $b[0] + $b[1]) {
            return true;
        }
        return false;
    }

    public function hasNeighBour($a)
    {
        foreach (array_keys($this->board) as $b) {
            if (self::isNeighbour($a, $b)) {
                return true;
            }
        }
        return false;
    }

    public function neighboursAreSameColor($player, $a)
    {
        foreach ($this->board as $b => $st) {
            if (!$st) {
                continue;
            }
            $c = $st[count($st) - 1][0];
            if ($c != $player && self::isNeighbour($a, $b)) {
                return false;
            }
        }
        return true;
    }

    public static function len($tile)
    {
        return $tile ? count($tile) : 0;
    }

    public function boardCount()
    {
        return count($this->board);
    }

    public function slide($from, $to)
    {
        $isValidMove = true;

        if (!self::hasNeighBour($to) || !self::isNeighbour($from, $to)) {
            $isValidMove = false;
        }

        if ($isValidMove) {
            $b = explode(',', $to);
            $common = [];
            foreach (self::$OFFSETS as $pq) {
                $p = $b[0] + $pq[0];
                $q = $b[1] + $pq[1];
                if (self::isNeighbour($from, $p . "," . $q)) {
                    $common[] = $p . "," . $q;
                }
            }

            if (
                count($common) < 2 ||
                !$this->board[$common[0]] && !$this->board[$common[1]] &&
                !$this->board[$from] && !$this->board[$to]
            ) {
                $isValidMove = false;
            } else {
                $isValidMove = min(
                    self::len($this->board[$common[0]]),
                    self::len($this->board[$common[1]])
                ) <= max(self::len($this->board[$from]), self::len($this->board[$to]));
            }
        }

        return $isValidMove;
    }

    public function calculatePositions(): array
    {
        $to = [];
        $offsets = Board::$OFFSETS;
        foreach ($offsets as $pq) {
            foreach (array_keys($this->board) as $pos) {
                $pq2 = explode(',', $pos);
                $to[] = ($pq[0] + $pq2[0]) . ',' . ($pq[1] + $pq2[1]);
            }
        }
        $to = array_unique($to);
        if (!count($to)) {
            $to[] = '0,0';
        }

        return $to;
    }

    public function getBoard()
    {
        return $this->board;
    }
}
