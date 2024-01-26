<?php

namespace app;

class Moves
{
    public static function playPiece(String $piece, String $toPosition, Game $game): void
    {
        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $hand = $player->getHand();
        $playerNumber = $player->getPlayerNumber();

        if (Rules::positionIsLegalToPlay($toPosition, $playerNumber, $hand, $board) && !Rules::tileNotInHand($hand, $piece)) {
            $board->addPiece($piece, $playerNumber, $toPosition);
            $player->removePieceFromHand($piece);
            $game->switchTurn();
            Database::addMoveToDatabase($game,"play", toPosition: $toPosition);

            //change last move to just done move
            $game->setLastMoveId(Database::getLastMoveId());
        }

    }

    public static function movePiece(String $fromPosition, String $toPosition, Game $game): void
    {
        //todo checken of stapelen werkt
        // errors tonen opeens niet meer, checken
        // although, errors moeten eigenlijk sowieso anders
        // nja, geen prio

        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $boardTiles = $board->getBoardTiles();

        $tile = array_pop($boardTiles[$fromPosition]);
        //check if move is legal
        if (Rules::positionIsLegalToMove($board, $player, $fromPosition, $toPosition)) {
            if (isset($boardTiles[$toPosition])) {
                array_push($boardTiles[$toPosition], $tile);
            } else {
                $boardTiles[$toPosition] = [$tile];
            }
            Database::addMoveToDatabase($game, "move", toPosition: $toPosition, fromPosition: $fromPosition);
            $game->setLastMoveId(Database::getLastMoveId());
            $game->switchTurn();
        } else {
            if (isset($boardTiles[$fromPosition])) {
                array_push($boardTiles[$fromPosition], $tile);
            } else {
                $boardTiles[$fromPosition] = [$tile];
            }
        }
        //todo weet niet zeker of dit klopt, de boardTiles moeten iig veranderd worden naar de nieuwe situatie
        $board->setBoardTiles($boardTiles);
    }

    public static function pass(Game $game): void
    {
        Database::addMoveToDatabase($game, "pass");
        $game->setLastMoveId(Database::getLastMoveId());
        $game->switchTurn();
    }

    public static function undoLastMove(Game $game): void
    {
        //todo bugfix & werkt niet als de vorige beurt ongeldig was? Hij gaf iig een error
        $result = Database::selectLastMoveFromGame($game);
        $game->setLastMoveId($result[5]);
        $game->setState($result[6], $game);
    }

}
