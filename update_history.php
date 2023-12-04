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

    // Escape and retrieve form data
    $playerName = $conn->real_escape_string($_POST['playerName']);
    $attempt = $conn->real_escape_string($_POST['attempt']);
    $guess = $conn->real_escape_string($_POST['guess']);

    // Retrieve the gameId from the cookie
    $gameId = isset($_COOKIE['gameId']) ? $conn->real_escape_string($_COOKIE['gameId']) : '';

    // Construct the game_history table name
    $gameHistoryTable = "game_history_" . $gameId;

    // Insert data into the dynamically named 'game_history' table
    $sql = "INSERT INTO $gameHistoryTable (player_name, attempt, guess) VALUES ('$playerName', $attempt, $guess)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }

    $conn->close();
}
?>
