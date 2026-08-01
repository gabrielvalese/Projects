<?php

require_once "seguranca.php";
verifySession();

if($_SERVER["REQUEST_METHOD"] === "POST") {
    removeUser();
    header("Location: /login.php");
}
    


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Pessoas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">CRUD Pessoas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="cadastro.php">Cadastrar</a></li>
                <li class="nav-item"><a class="nav-link" href="visualizar.php">Visualizar</a></li>
                <li class="nav-item"><a class="nav-link" href="editar.php">Editar</a></li>
                <li class="nav-item"><a class="nav-link" href="excluir.php">Excluir</a></li>
            <form action="" method="POST" class="d-inline"> 
                <li class="nav-item"><input class="nav-link" type="submit" value="Sair" name="logout">
            </form>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
