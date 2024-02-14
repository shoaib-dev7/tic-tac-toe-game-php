<?php

namespace Model;

use Helper\Database;

class GameModel {
    private $db;

    public function __construct() {
        $this->db = new \Helper\Database();
    }

    public function saveGameState($board, $gameNumber, $player) {
        // Convert the board to JSON format
        $json_board = json_encode($board);

        // Save the game state to the database
        $conn = $this->db->connect();
        $stmt = $conn->prepare('INSERT INTO game_state (state, game_number, player) VALUES (:state, :gameNumber, :player)');
        $stmt->bindParam(':state', $json_board);
        $stmt->bindParam(':gameNumber', $gameNumber);
        $stmt->bindParam(':player', $player);
        $stmt->execute();
    }

    public function loadGameState($game_number) {
        // Retrieve the latest game state from the database
        $conn = $this->db->connect();
        $stmt = $conn->prepare('SELECT state FROM game_state WHERE game_number = :gameNumber ORDER BY id DESC LIMIT 1');
        $stmt->bindParam(':gameNumber', $game_number);
        $stmt->execute();

        // Check if any rows were returned
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            $board = json_decode($result['state'], true);
        } else {
            $board = [
                ['', '', ''],
                ['', '', ''],
                ['', '', '']
            ];
        }
    
        return $board;
    }

    public function truncateGameState() {
        $conn = $this->db->connect();
        $stmt = $conn->prepare('TRUNCATE TABLE game_state');
        $stmt->execute();
    }
}
