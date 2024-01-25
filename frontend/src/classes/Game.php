<?php

namespace classes;

class Game
{
    private DatabaseHandler $databaseHandler;

    private int $gameId;
    private int $player;
    private array $hand;
    private array $board;
    private int | null $prevMoveId;
    private int $turnCounter;
    private string | null $error;
    private int $gameStatus = 0;

    public function __construct(DatabaseHandler $databaseHandler = null) {
        $this->databaseHandler = $databaseHandler ?? new DatabaseHandler();
    }

    /**
     * Plays a piece on the specified position on the board.
     *
     * @param string $pos   Coordinates of the board position where the piece is to be played (in the format "x,y").
     * @param string $piece The piece to be played.
     * @return void
     */
    public function play(string $pos, string $piece): void {

    }

    /**
     * Retrieves an array of available pieces for the current player from their hand.
     *
     * @return array An array containing the keys of available pieces (pieces with a count greater than 0).
     */
    public function getAvailablePieces(): array {
        return [];
    }

    /**
     * Retrieves the valid play moves for the current player based on the Hive game rules.
     *
     * @return array Returns an array containing the valid positions on the board where the current
     * player can place a piece.
     */
    public function getValidPlayMoves(): array {
        return [];
    }

    public function getHand(int | null $player = null): array {
        return [];
    }

    /**
     * Restarts the game by resetting the board, player hands, and other relevant game state variables.
     * This method initializes a new game and updates the session with the updated state.
     */
    public function restart(): void {

    }

    /**
     * Initializes the game state by retrieving necessary information from the session.
     * This method should be called to set up the game state at the beginning of each request.
     */
    public function initializeGame(): void {
    }

    /**
     * Updates the session with the current game state.
     * This method should be called to persist the game state in the session after each move.
     */
    public function updateSession(): void {

    }
}