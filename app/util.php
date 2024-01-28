<?php

$GLOBALS['OFFSETS'] = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

function isNeighbour($a, $b)
{
    $a = explode(',', $a);
    $b = explode(',', $b);
    if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) return true;
    if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) return true;
    if ($a[0] + $a[1] == $b[0] + $b[1]) return true;
    return false;
}

function hasNeighBour($a, $board)
{
    foreach (array_keys($board) as $b) {
        if (isNeighbour($a, $b)) return true;
    }
}

function neighboursAreSameColor($player, $a, $board)
{
    foreach ($board as $b => $st) {
        if (!$st) continue;
        $c = $st[count($st) - 1][0];
        if ($c != $player && isNeighbour($a, $b)) return false;
    }
    return true;
}

function len($tile)
{
    return $tile ? count($tile) : 0;
}

function slide($board, $from, $to)
{
    if (!hasNeighBour($to, $board)) return false;
    if (!isNeighbour($from, $to)) return false;
    $b = explode(',', $to);
    $common = [];
    foreach ($GLOBALS['OFFSETS'] as $pq) {
        $p = $b[0] + $pq[0];
        $q = $b[1] + $pq[1];
        if (isNeighbour($from, $p . "," . $q)) $common[] = $p . "," . $q;
    }
    if (!$board[$common[0]] && !$board[$common[1]] && !$board[$from] && !$board[$to]) return false;
    return min(len($board[$common[0]]), len($board[$common[1]])) <= max(len($board[$from]), len($board[$to]));
}

function AvailablePosition($board, $hand, $player, $to){
    if (isset($board[$to])){
        return false;
    }
    elseif (count($board) && !hasNeighBour($to, $board)){
        return false;
    }
    elseif (array_sum($hand) < 11 && !neighboursAreSameColor($player, $to, $board)){
        return false;
    }
    return true;
}

function MoveToCurrentPos($from, $to):bool{
    if($from == $to){
        $_SESSION['error'] = 'Tile must move';
       return true;
    }
    else{
        return false;
    }
}

function Grasshopper($from, $to, $board){
    $jump = false;
    //Regels voor springkhaan:
        //1. Sprinkhaan sprint in een rechte lijn achter een andere steen in de richting van de tegenstander
        //2. Sprinkhaan moet verplaatsen naar een plek waar hij niet staat
        //3. Sprinkhaan moet over minimaal over 1 steen springen
        //4. Sprinkhaan mag niet naar een bezet veld springen
        //5. Sprinkhaan mag niet over lege velden springen, alle velden moeten bezet zijn.
    
        if(MoveToCurrentPos($from, $to)== false){
        $fromPositions = explode(',', $from);
        $toPositions = explode(',', $to);
        
        $xDifference = $toPositions[0] - $fromPositions[0];

        $yDifference = $toPositions[1] - $fromPositions[1];
        
        if(!($fromPositions[0] == $toPositions[0] || $fromPositions[1] == $toPositions[1] || ($fromPositions[0]+$fromPositions[1]) == ($toPositions[0]+$fromPositions[1]))){
            return false;
        }

        if($xDifference > 0){
            $xPlacement = 1;    
        }
        elseif($xDifference < 0 ){
            $xPlacement = -1;
        }
        else{
            $xPlacement = 0;
        }

        if($yDifference > 0){
            $yPlacement = 1;
        }
        elseif($yDifference < 0 ){
            $yPlacement = -1;
        }
        else{
            $yPlacement = 0;
        }
        $q = $fromPositions[1] + $yPlacement;
        $p = $fromPositions[0] + $xPlacement;
        
        if(!isset($board["$p,$q"])){
            return false;
        }

        while(isset($board["$p,$q"])){
            $jump = true;
            $p += $xPlacement;
            $q += $yPlacement;
        }
        return $jump;
    }
    $_SESSION['error'] = 'Tile must move';
}