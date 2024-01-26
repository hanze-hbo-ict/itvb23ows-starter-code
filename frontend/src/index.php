<?php
require_once 'vendor/autoload.php';

use Classes\Game;

session_start();

$hiveGame = new Game();

if (!isset($_SESSION['board'])) {
    $hiveGame->restart();
}

$hiveGame->executeAction();

$board = $_SESSION['board'];

    $player = $_SESSION['player'];
    $hand = $_SESSION['hand'];

    $to = [];
    foreach ($GLOBALS['OFFSETS'] as $pq) {
        foreach (array_keys($board) as $pos) {
            $pq2 = explode(',', $pos);
            $to[] = ($pq[0] + $pq2[0]).','.($pq[1] + $pq2[1]);
        }
    }
    $to = array_unique($to);
    if (!count($to)) $to[] = '0,0';
?>
<!DOCTYPE html>
<html>
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
                $min_p = 1000;
                $min_q = 1000;
                foreach ($board as $pos => $tile) {
                    $pq = explode(',', $pos);
                    if ($pq[0] < $min_p) $min_p = $pq[0];
                    if ($pq[1] < $min_q) $min_q = $pq[1];
                }
                foreach (array_filter($board) as $pos => $tile) {
                    $pq = explode(',', $pos);
                    $pq[0];
                    $pq[1];
                    $h = count($tile);
                    echo '<div class="tile player';
                    echo $tile[$h-1][0];
                    if ($h > 1) echo ' stacked';
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
                foreach ($hand[0] as $tile => $ct) {
                    for ($i = 0; $i < $ct; $i++) {
                        echo '<div class="tile player0"><span>'.$tile."</span></div> ";
                    }
                }
            ?>
        </div>
        <div class="hand">
            Black:
            <?php
            foreach ($hand[1] as $tile => $ct) {
                for ($i = 0; $i < $ct; $i++) {
                    echo '<div class="tile player1"><span>'.$tile."</span></div> ";
                }
            }
            ?>
        </div>
        <div class="turn">
            Turn: <?php if ($player == 0) echo "White"; else echo "Black"; ?>
        </div>
        <form method="post">
            <select name="piece">
                <?php
                foreach ($hiveGame->getAvailablePieces() as $tile) {
                    echo "<option value='$tile'>$tile</option>";
                }
                ?>
            </select>
            <select name="pos">
                <?php
                foreach ($hiveGame->getValidPlayMoves() as $pos) {
                    echo "<option value='$pos'>$pos</option>";
                }
                ?>
            </select>
            <button type="submit" name="action" value="play">Play</button>
        </form>
        <?php if ($_SESSION["turn_counter"] > 1) { ?>
            <form method="post">
                <select name="from">
                    <?php
                    foreach ($hiveGame->getOccupiedPositions() as $pos) {
                        echo "<option value='$pos'>$pos</option>";
                    }
                    ?>
                </select>
                <select name="to">
                    <?php
                    foreach ($hiveGame->getBoundaries() as $pos) {
                        echo "<option value=\"$pos\">$pos</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="action" value="move">Move</button>
            </form>
            <form method="post">
                <button type="submit" name="action" value="pass">Pass</button>
            </form>
        <?php } ?>
        <form method="post">
            <button type="submit" name="action" value="undo">Undo</button>
        </form>
        <form method="post">
            <button type="submit" name="action" value="ai_play">AI Play</button>
        </form>
        <form method="post">
            <button type="submit" name="action" value="restart">Restart</button>
        </form>
        <strong>
            <?= $_SESSION["error"] ?>
        </strong>
        <strong>
            <?php
            if ($_SESSION["game_status"] == 1) {
                echo "White has won!";
            } elseif ($_SESSION["game_status"] == 2) {
                echo "Black has won!";
            } elseif ($_SESSION["game_status"] == 3) {
                echo "The game ended in a draw!";
            }
            ?>
        </strong>
        <ol>
            <?php
                $db = include 'database.php';
                $stmt = $db->prepare('SELECT * FROM moves WHERE game_id = '.$_SESSION['game_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_array()) {
                    echo '<li>'.$row[2].' '.$row[3].' '.$row[4].'</li>';
                }
            ?>
        </ol>
        <form method="post" action="undo.php">
            <input type="submit" value="Undo">
        </form>
    </body>
</html>

