<?php
include("seguranca.php");
require_once "config.php";

$email = $_POST['emailLogin'] ?? null;
$passwordUser = $_POST['passwordLogin'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === 'POST' && !empty($email) && !empty($passwordUser)) {
    if (userValidade($email, $passwordUser, $mysqli) === true) {
        header("Location: ./index.php");
        exit;
    }

    echo "Email ou senha incorretos";
    header("Location: ./login.php");
    exit;
}

?>