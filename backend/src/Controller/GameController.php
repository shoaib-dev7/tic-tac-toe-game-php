<?php

namespace Controller;

use Model\GameModel;
use Ramsey\Uuid\Uuid;

class GameController {
    private $model;

    public function __construct() {
        $this->model = new \Model\GameModel();
    }

    public function makeMove($col, $row, $player, $gameNumber) { 

        if (empty($gameNumber)) {
            $gameNumber = Uuid::uuid4()->toString();
        }

        // Load the current game state
        $board = $this->model->loadGameState($gameNumber);

        // Check if the move is valid
        if ($this->isValidMove($board, $row, $col)) { 
            // Update the cell with the player's symbol
            $board[$row][$col] = $player; // Adjust the order of parameters to match frontend

            // Save the updated game state
            $this->model->saveGameState($board, $gameNumber, $player);

            // Check for winner or draw
            $winner = $this->checkWinner($board);
            if ($winner) {                
                
                return ['winner' => $winner];
            } elseif ($this->isDraw($board)) {                
                return ['draw' => true];
            }

            return ['board' => $board, 'game_number' => $gameNumber];
        } else {
            // Invalid move, return error
            return ['error' => 'Invalid move'];
        }
    }

    private function isValidMove($board, $row, $col) {
        
        // Check if the specified row and column indices are within the bounds of the board
        if ($row >= 0 && $row < count($board) && $col >= 0 && $col < count($board[$row])) {
            
            return $board[$row][$col] === '';
        }
        
        return false;
    }

    private function checkWinner($board) {
        
        for ($i = 0; $i < 3; $i++) {
            if ($board[$i][0] !== '' && $board[$i][0] === $board[$i][1] && $board[$i][0] === $board[$i][2]) {
                return $board[$i][0]; 
            }
        }
    
        for ($j = 0; $j < 3; $j++) {
            if ($board[0][$j] !== '' && $board[0][$j] === $board[1][$j] && $board[0][$j] === $board[2][$j]) {
                return $board[0][$j];
            }
        }
    
        // Check diagonals
        if ($board[0][0] !== '' && $board[0][0] === $board[1][1] && $board[0][0] === $board[2][2]) {
            return $board[0][0]; // Diagonal from top-left to bottom-right has a winner
        }
        if ($board[0][2] !== '' && $board[0][2] === $board[1][1] && $board[0][2] === $board[2][0]) {
            return $board[0][2]; // Diagonal from top-right to bottom-left has a winner
        }
    
        return false;
    }
    
    private function isDraw($board) {
        // Check if all cells are filled
        foreach ($board as $row) {
            foreach ($row as $cell) {
                if ($cell === '') {
                    return false;
                }
            }
        }
    
        return true;
    }


}
