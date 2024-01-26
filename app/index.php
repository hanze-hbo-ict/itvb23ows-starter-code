<?php
    require_once 'vendor/autoload.php';

    use app\Database;
    use app\Game;
use app\Moves;

//todo eventueel post actions op een andere manier?

    session_start();

    if (!isset($_SESSION['game'])) {
        $game = new Game();
    } else {
        $game = $_SESSION['game'];
    }

    $board = $game->getBoard();
    $currentPlayer = $game->getCurrentPlayer();
    $playerOne = $game->getPlayerOne();
    $playerTwo = $game->getPlayerTwo();
    $offsets = $board->getOffsets();

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
            //todo dit proberen te snappen, hier worden de tegels op het bord weergegeven? Wat zijn de var hier?
                $min_p = 1000;
                $min_q = 1000;
                foreach ($board->getBoardTiles() as $position => $tile) {
                    $pq = explode(',', $position); //pq = position als array
                    if ($pq[0] < $min_p) {
                        $min_p = $pq[0];
                    }
                    if ($pq[1] < $min_q) {
                        $min_q = $pq[1];
                    }
                }
                foreach (array_filter($board->getBoardTiles()) as $position => $tile) {
                    $pq = explode(',', $position);
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

        <form method="post" action="src/formPosts/play.php">
            <select name="piece">
                <?php
                    // dropdown player pieces
                    foreach ($game->getCurrentPlayer()->getHand() as $tileName => $count) {
                        echo "<option value=\"$tileName\">$tileName</option>";
                    }
                ?>
            </select>
            <select name="toPosition">
                <?php
                    // dropdown possible play positions
                    $possiblePlayPositions = $board->getPossiblePlayPositions($currentPlayer->getPlayerNumber(), $currentPlayer->getHand());
                    foreach ($possiblePlayPositions as $position) {
                        echo "<option value=\"$position\">$position</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Play">
        </form>

        <form method="post" action="src/formPosts/move.php">
            <select name="fromPosition">
                <?php
                    foreach (array_keys($board->getTilesFromPlayer($currentPlayer->getPlayerNumber())) as $position) {
                        echo "<option value=\"$position\">$position</option>";
                    }
                ?>
            </select>
            <select name="toPosition">
                <?php
                    foreach ($possiblePlayPositions as $position) {
                        echo "<option value=\"$position\">$position</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Move">
        </form>

        <form method="post" action="src/formPosts/pass.php">
            <input type="submit" value="Pass">
        </form>

        <form method="post" action="src/formPosts/restart.php">
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
                //todo bugfix, hij select alle moves van alle games
                $gameId = $game->getGameId();
                $result = Database::selectAllMovesFromGame($gameId);
                while ($row = $result->fetch_array()) {
                    echo '<li>'.$row[2].' '.$row[3].' '.$row[4].'</li>';
                }
            ?>
        </ol>

        <form method="post" action="src/formPosts/undo.php">
            <input type="submit" value="Undo">
        </form>

    </body>
</html>

