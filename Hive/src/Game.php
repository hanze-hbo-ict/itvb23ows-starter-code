<?php

namespace HiveGame;

use HiveGame\GameState;
use HiveGame\Utils;

class Game
{
    private Database $db;

    /**
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }


    public function startGame(): void
    {
        $utils = new Utils();

        $game = new GameState();
        $game->setGameId($this->db->createGame());

        $view = new GameView($game);
        $view->render();
    }

    public function continueGame($move): void
    {
        $utils = new Utils();

        $gameState = $this->db->getGame($move["game"]);
        $gameActions = new GameActions($this->db, $gameState);

        switch ($move["action"]) {
            case "Play":
                $gameActions->makePlay($move["piece"], $move["to"]);
                break;
            case "Move":
                $gameActions->makeMove($move["from"], $move["to"]);
                break;
        }

        $view = new GameView($gameActions->getGame());
        $view->render();
    }
}