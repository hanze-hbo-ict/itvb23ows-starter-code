<?php

namespace HiveGame;

use HiveGame\GameRules;

class GameActions
{
    private Database $db;
    private GameState $game;

    /**
     * @param Database $db
     * @param GameState $game
     */
    public function __construct(Database $db, GameState $game)
    {
        $this->db = $db;
        $this->game = $game;
    }

    public function makePlay(string $piece, string $to): bool|string
    {
        $board = $this->game->getBoard();
        var_dump($board);

        $rules = new GameRules();
        $validity = $rules->validPlay($board, $to, $piece, $this->game->getCurrentPlayer());

        if ($validity) {
            $board[$to] = [[$this->game->getCurrentPlayer(), $piece]];
            var_dump($board);


            $hand  = $this->game->getCurrentPlayer()->getHand();
            $hand[$piece]--;
            $this->game->getCurrentPlayer()->setHand($hand);
            $this->swapPlayer();

            $this->game->setLastMove($this->db->storeMove($this->game->getGameId(), "play", $piece, $to, $this->game->getLastMove(), $this->getState()));


            $this->game->setBoard($board);

        }

        return $validity;
    }

    public function makeMove(string $from, string $to): string|bool
    {
        $board = $this->game->getBoard();

        $rules = new GameRules();
        $validity = $rules->validMove($this->game->getBoard(), $to, $from, $this->game->getCurrentPlayer());

        if (!$validity) {
            return $validity;
        }

        if (isset($board[$from]) && !empty($board[$from])) {
            $tile = array_pop($board[$from]);

            if (isset($board[$to])) {
                array_push($board[$to], $tile);
            } else {
                $board[$to] = [$tile];
            }

            // Add debugging
            var_dump("Board after move:", $board);

            $this->swapPlayer();
            $this->game->setLastMove($this->db->storeMove($this->game->getGameId(), "move", $from, $to, $this->game->getLastMove(), $this->getState()));
        }

        $this->game->setBoard($board);

        return $validity;
    }


    public function undoMove(): void
    {
        $result = $this->db->getMoves($this->game->getLastMove())->fetch_array();
        $this->game->setLastMove($result[5]);
        $this->setState($result[6]);
    }

    public function getState(): string
    {
        return serialize([$this->game->getPlayer1()->getHand(), $this->game->getPlayer2()->getHand(), $this->game->getBoard(), $this->game->getCurrentPlayer()]);
    }

    public function setState($state): void
    {
        if ($state != '') {
            list($a, $b, $c, $currentPlayer) = unserialize($state);

            $this->game->getPlayer1()->setHand($a);
            $this->game->getPlayer2()->setHand($b);
            var_dump($c);
            $this->game->setBoard($c);

            if ($currentPlayer->getColor() == 0) {
                $this->game->setCurrentPlayer($this->game->getPlayer1());
            } else {
                $this->game->setCurrentPlayer($this->game->getPlayer2());
            }
        }
    }

    private function swapPlayer(): void
    {
        if ($this->game->getCurrentPlayer() === $this->game->getPlayer1()) {
            $this->game->setCurrentPlayer($this->game->getPlayer2());
        } else {
            $this->game->setCurrentPlayer($this->game->getPlayer1());
        }
    }

    /**
     * @return GameState
     */
    public function getGame(): GameState
    {
        return $this->game;
    }

    /**
     * @param GameState $game
     */
    public function setGame(GameState $game): void
    {
        $this->game = $game;
    }
}