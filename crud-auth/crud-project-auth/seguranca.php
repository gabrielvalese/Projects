<?php
session_start();
require_once "config.php";

function userValidade ($email, $password, $mysqli) {
    $sql = "SELECT * FROM pessoas WHERE email = ?";
    $consult = $mysqli->prepare($sql);
    $consult->bind_param("s", $email);
    $consult->execute();

    $result = $consult->get_result();   

    if($result->num_rows > 0) { /* font: https://www.youtube.com/watch?v=LiomRvK7AM8&t=1759s */
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['senha'])) {
            $_SESSION['user'] = $user['email'];
            $_SESSION['name'] = $user['nome'];
            return true;

        }
        
    }

    return false;
}

function removeUser(): void {
    session_start();
    session_destroy();
    header("Location: ./login.php");
}

function verifySession(): void {
    if (empty($_SESSION['user'])) {
        removeUser();
    }
}
?>

