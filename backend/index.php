<?php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

require_once __DIR__ . '/vendor/autoload.php';

use Controller\GameController;

$gameController = new GameController();

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $json_data = file_get_contents('php://input');

    // Decode the JSON data into a PHP associative array
    $data = json_decode($json_data, true);

    if ($data !== null) {

        $action = $data['action'] ?? null;
        $row = $data['row'] ?? null;
        $col = $data['col'] ?? null;
        $player = $data['player'] ?? null;
        $gameNumber = $data['game_number'] ?? "";

        // Check if all required parameters are present
        if ($action !== null && $row !== null && $col !== null && $player !== null) {
            // Perform action based on the request
            if ($action === 'makeMove') {
                
                $response = $gameController->makeMove($col, $row, $player, $gameNumber); 
            }

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    // Invalid request parameters or JSON data, return error response
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request parameters']);
    exit;
}
