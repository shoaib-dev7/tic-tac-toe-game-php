document.addEventListener("DOMContentLoaded", function() {
    let currentPlayer = 'X'; 

    // Function to initialize the game board
    function initializeBoard() {
        const boardElement = document.getElementById('board');
        const boardSize = 3; 

        // Clear the board
        boardElement.innerHTML = '';

        // Generate the game board
        for (let i = 0; i < boardSize; i++) {
            const row = document.createElement('div');
            row.classList.add('row');

            for (let j = 0; j < boardSize; j++) {
                const cell = document.createElement('div');
                cell.classList.add('cell');
                cell.dataset.row = i; 
                cell.dataset.col = j; 
                cell.textContent = '-';
                row.appendChild(cell);
            }

            boardElement.appendChild(row);
        }
    }

    // Initialize the game board
    initializeBoard();

    var gameNumber = "";

    // Function to handle player moves
    function makeMove(row, col, player) {
        fetch('http://localhost:8080/backend/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'makeMove',
                row: row,
                col: col,
                player: player,
                game_number: gameNumber
            })
        })
        .then(response => response.json())
        .then(data => {

            if (data.game_number) {
                gameNumber = data.game_number;
            }

            if (data.error) {
                console.error('Error:', data.error);
                alert('Error: Invalid Moves');
            } else if (data.winner) {
                // Display win alert with the winner's name
                alert(`${data.winner} wins!`);
                // Reset the board after a short delay
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            } else if (data.draw) {
                // Display draw alert
                alert('Game is a draw!');
                // Reset the board after a short delay
                window.location.reload();
            } else {
                // Update the game board with the new state
                updateBoard(data.board);
                // Toggle the current player for the next turn
                currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Function to update the game board with the new state
    function updateBoard(board) {
        const cells = document.querySelectorAll('.cell');
        cells.forEach(cell => {
            const row = cell.dataset.row;
            const col = cell.dataset.col;
            if (board[row] && board[row][col]) {
                cell.textContent = board[row][col];
            }
        });
    }

    // Event listener for player moves
    document.getElementById('board').addEventListener('click', function(event) {

        event.preventDefault();
        
        if (event.target.classList.contains('cell')) {
            const row = event.target.dataset.row; // Extract the row index from the dataset
            const col = event.target.dataset.col; // Extract the column index from the dataset
            console.log(`Player ${currentPlayer} clicked row ${row}, col ${col}`); // Log the player's move
            makeMove(row, col, currentPlayer);
        }
    });
});
