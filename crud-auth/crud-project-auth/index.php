<?php
require_once "seguranca.php";
verifySession();
require_once "includes/header.php";
?>


<h1>Fala, <?= $_SESSION['name'] ?>!</h1>

<h1 class="mb-4">Gerenciamento de Pessoas</h1>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">➕ Cadastrar</h5>
                <p class="card-text">Adicionar uma nova pessoa.</p>
                <a href="cadastro.php" class="btn btn-primary">Cadastrar</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">👁️ Visualizar</h5>
                <p class="card-text">Ver um registro ou todos.</p>
                <a href="visualizar.php" class="btn btn-info text-white">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">✏️ Editar</h5>
                <p class="card-text">Alterar dados de uma pessoa.</p>
                <a href="editar.php" class="btn btn-warning">Editar</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">🗑️ Excluir</h5>
                <p class="card-text">Remover uma pessoa.</p>
                <a href="excluir.php" class="btn btn-danger">Excluir</a>
            </div>
        </div>
    </div>
    <a href="login.php" class="btn btn-primary">login - test</a>
</div>

<?php print_r($_SESSION)
?>
