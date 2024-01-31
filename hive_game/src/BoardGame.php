<?php
namespace Joyce0398\HiveGame;

class BoardGame {
    private static $OFFSETS = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];
    public array $board; 

    public function __construct(array $board){
        $this->board = $board;
    }

    public static function getOffsets() {
        return self::$OFFSETS;
    }

    public function getBoard()
    {
        return $this->board;
    }

    public static function getState() {
        return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
    }

    public static function setState($state) {
        list($_SESSION['hand'], $_SESSION['board'], $_SESSION['player']) = unserialize($state);
    }

    public function isEmpty() {
        return count($this->board) === 0;
    }

    public function getMoves()
    {
        return Database::getMoves($this->gameId);
    }

    public function getKeys() {
        return array_keys($this->board);
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

    public function isPlayerOccupying($from, $player) {
        if (isset($this->board[$from]) && count($this->board[$from]) > 0) {
            return $this->board[$from][count($this->board[$from])-1][0] == $player;
        }

        return false;
    }

    public function isNeighbour($a, $b) {
        $a = explode(',', $a);
        $b = explode(',', $b);

        if (($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) || ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1)) {
            return true;
        }

        if ($a[0] + $a[1] == $b[0] + $b[1]) {
            return true;
        }

        return false;
    }

    public function hasNeighbour($a) {
        foreach (array_keys($this->board) as $b) {
            if ($this->isNeighbour($a, $b)) {
                return true;
            }
        }
        return false;
    }

    public function neighboursAreSameColor($player, $a) {
        foreach ($this->board as $b => $st) {
            if (!$st) { continue; }
            $c = $st[count($st) - 1][0];
            if ($c != $player && $this->isNeighbour($a, $b)) { return false; }
        }
        return true;
    }

    public function isOccupied(string $position): bool
    {
        return isset($this->board[$position]);
    }

    public function len($tile) {
        return $tile ? count($tile) : 0;
    }

    public function pushTile(string $position, string $piece, int $player)
    {
        array_push($this->board[$position], array($player, $piece));
    }

    public function slide($from, $to) {
        if (!$this->hasNeighbour($to) || !$this->isNeighbour($from, $to)) {
            return false;
        }
    
        $b = explode(',', $to);
        $common = [];
        foreach (self::$OFFSETS as $pq) {
            $p = $b[0] + $pq[0];
            $q = $b[1] + $pq[1];
            if ($this->isNeighbour($from, $p . "," . $q)) {
                $common[] = $p . "," . $q;
            }
        }
    
        if (count($common) < 2 || !$this->board[$common[0]] && !$this->board[$common[1]] && !$this->board[$from] && !$this->board[$to]) {
            return false;
        }
    
        return min(count($this->board[$common[0]]), count($this->board[$common[1]])) <= max(count($this->board[$from]), count($this->board[$to]));
    }
    
}