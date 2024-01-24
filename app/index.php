<?php
    require_once(__DIR__ . "/src/Database.php");
    require_once(__DIR__ . "/src/Game.php");

    use app\Database;
    use app\Game;

    //todo deze file herschrijven met gebruik van classes
    //todo eventueel post actions op een andere manier?

    session_start();

    // Dit representeert de hexagon, de randen waar eventueel een tegel aankan.
    $GLOBALS['OFFSETS'] = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    if (!isset($_SESSION['game'])) {
        $db = new Database();
        $game = new Game($db);
    } else {
        $db = $_SESSION['db'];
        $game = $_SESSION['game'];
    }

    $board = $game->getBoard();
    $currentPlayer = $game->getCurrentPlayer();
    $playerOne = $game->getPlayerOne();
    $playerTwo = $game->getPlayerTwo();

    //todo dit proberen te snappen en eventueel aanpassen ????? Zijn dit de mogelijke play posities oid?
    $to = [];
    foreach ($GLOBALS['OFFSETS'] as $offset) {
        //todo, blijkbaar zijn de boardTiles strings
        foreach (array_keys($board->getBoardTiles()) as $pos) {
            //todo pq2 is hier een string[], aanpassen
            $pq2 = explode(',', $pos);
            $to[] = ($offset[0] + $pq2[0]).','.($offset[1] + $pq2[1]);
        }
    }
    $to = array_unique($to);
    if (!count($to)) {
        $to[] = '0,0';
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Hive</title>
        <style>
            div.board {
                width: 60%;
                height: 100%;
                min-height: 500px;
                float: left;
                overflow: scroll;
                position: relative;
            }

            div.board div.tile {
                position: absolute;
            }

            div.tile {
                display: inline-block;
                width: 4em;
                height: 4em;
                border: 1px solid black;
                box-sizing: border-box;
                font-size: 50%;
                padding: 2px;
            }

            div.tile span {
                display: block;
                width: 100%;
                text-align: center;
                font-size: 200%;
            }

            div.player0 {
                color: black;
                background: white;
            }

            div.player1 {
                color: white;
                background: black
            }

            div.stacked {
                border-width: 3px;
                border-color: red;
                padding: 0;
            }
        </style>
    </head>
    <body>
        <div class="board">
            <?php
            //todo dit proberen te snappen, hier worden de tegels op het bord weergegeven?
                $min_p = 1000;
                $min_q = 1000;
                foreach ($board as $pos => $tile) {
                    $pq = explode(',', $pos);
                    if ($pq[0] < $min_p) {
                        $min_p = $pq[0];
                    }
                    if ($pq[1] < $min_q) {
                        $min_q = $pq[1];
                    }
                }
                foreach (array_filter($board->getBoardTiles()) as $pos => $tile) {
                    $pq = explode(',', $pos);
                    $pq[0];
                    $pq[1];
                    $h = count($tile);
                    echo '<div class="tile player';
                    echo $tile[$h-1][0];
                    if ($h > 1) {
                        echo ' stacked';
                    }
                    echo '" style="left: ';
                    echo ($pq[0] - $min_p) * 4 + ($pq[1] - $min_q) * 2;
                    echo 'em; top: ';
                    echo ($pq[1] - $min_q) * 4;
                    echo "em;\">($pq[0],$pq[1])<span>";
                    echo $tile[$h-1][1];
                    echo '</span></div>';
                }
            ?>
        </div>
        <div class="hand">
            White:
            <?php
            //todo functie die dit kan printen? (want herhaling) (heeft geen prio)
                foreach ($playerOne->getHand() as $tile => $ct) {
                    for ($i = 0; $i < $ct; $i++) {
                        echo '<div class="tile player0"><span>'.$tile."</span></div> ";
                    }
                }
            ?>
        </div>
        <div class="hand">
            Black:
            <?php
            foreach ($playerTwo->getHand() as $tile => $ct) {
                for ($i = 0; $i < $ct; $i++) {
                    echo '<div class="tile player1"><span>'.$tile."</span></div> ";
                }
            }
            ?>
        </div>
        <div class="turn">
            Turn: <?php
            if ($currentPlayer->getPlayerNumber() == 0) {
                echo "White";
            } else {
                echo "Black";
            } ?>
        </div>

        <form method="post" action="src/form_posts/play.php">
            <select name="piece">
                <?php
                    foreach ($game->getCurrentPlayer()->getHand() as $tile => $ct) {
                        echo "<option value=\"$tile\">$tile</option>";
                    }
                ?>
            </select>
            <select name="toPosition">
                <?php
                    // deze to wordt bovenaan deze file geinstantieerd
                    foreach ($to as $pos) {
                        echo "<option value=\"$pos\">$pos</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Play">
        </form>

        <form method="post" action="src/form_posts/move.php">
            <select name="fromPosition">
                <?php
                    foreach (array_keys($board->getBoardTiles()) as $pos) {
                        echo "<option value=\"$pos\">$pos</option>";
                    }
                ?>
            </select>
            <select name="toPosition">
                <?php
                    foreach ($to as $pos) {
                        echo "<option value=\"$pos\">$pos</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Move">
        </form>

        <form method="post" action="src/form_posts/pass.php">
            <input type="submit" value="Pass">
        </form>

        <form method="post" action="src/form_posts/restart.php">
            <input type="submit" value="Restart">
        </form>

        <strong>
            <?php if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            } ?>
        </strong>
        <ol>
            <?php
                //todo wat is dit?
                $gameId = $game->getGameId();
                $result = $db->selectAllMovesFromGame($gameId);
                while ($row = $result->fetch_array()) {
                    echo '<li>'.$row[2].' '.$row[3].' '.$row[4].'</li>';
                }
            ?>
        </ol>

        <form method="post" action="src/form_posts/undo.php">
            <input type="submit" value="Undo">
        </form>

    </body>
</html>

