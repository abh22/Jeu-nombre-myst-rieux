<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a MySQL database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "guessgame";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape and retrieve player names from POST data
    $player1Name = $conn->real_escape_string($_POST['player1Name']);
    $player2Name = $conn->real_escape_string($_POST['player2Name']);

    // Insert game instance into the 'game' table
    $sql = "INSERT INTO game (player1, player2) VALUES ('$player1Name', '$player2Name')";

    if ($conn->query($sql) === TRUE) {
        // Retrieve the ID of the inserted game instance
        $gameId = $conn->insert_id;

        // Create a table for the game history
        $createTableSql = "CREATE TABLE game_history_$gameId (
                            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            player VARCHAR(50) NOT NULL,
                            attempt int(2) UNSIGNED,
                            guess int(2) UNSIGNED,
                            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                          )";

        if ($conn->query($createTableSql) === TRUE) {
            echo "New game instance and history table added successfully";
        } else {
            echo "Error creating game history table: " . $conn->error;
        }
    } else {
        echo "Error inserting game instance: " . $conn->error;
    }

    $conn->close();
}
?>
