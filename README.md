# Tic Tac Toe Game

This is a simple implementation of the Tic Tac Toe game using PHP and JavaScript, following modern development practices.

## Features

- Two players taking turns (Player X and Player O).
- Display the current state of the board after each move.
- Detect and announce the winner or a draw.
- Handle invalid moves gracefully.

## Usage

1. Start the PHP inbuilt web server from the root directory using the following command:

   ```bash
   php -S localhost:8080

Project Structure

    frontend: Contains the HTML, CSS, and JavaScript files for the user interface.
    backend: Implements the server-side logic using PHP, following the MVC (Model-View-Controller) architecture.
    vendor: Contains the Composer dependencies.
    database.sql: SQL file for database setup.

Development Setup

    Clone the repository:

    bash

git clone https://github.com/your-username/tic-tac-toe-game.git

Navigate to the project directory and install Composer dependencies:

bash

    composer install

    Configure your web server to serve the frontend directory.

    Import the provided SQL file database.sql into your MySQL database.

    Update the database configuration in backend/src/Helper/Database.php with your database credentials.

Contributions

Contributions are welcome! Fork the repository, make your changes, and submit a pull request.
License

This project is licensed under the MIT License.
