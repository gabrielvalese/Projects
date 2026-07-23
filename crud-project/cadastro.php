<?php require_once "config.php"; ?>
<?php require_once "includes/header.php"; ?>
<?php require_once "functions.php"; ?>

<?php

$emailUser = $_POST['email'] ?? null;
$passwordUser = $_POST['senha'] ?? null;
$nameUser = $_POST['nome'] ?? null;
$cpfUser = $_POST['cpf'] ?? null;
$dateUser = $_POST['nascimento'] ?? null;




if($_SERVER["REQUEST_METHOD"] === 'POST'
&& !empty($emailUser)
&& !empty($passwordUser)
&& !empty($nameUser)
&& !empty($cpfUser)
&& !empty($dateUser)
) {
    $hash = password_hash($passwordUser, PASSWORD_BCRYPT);

    $registerComplete = registerUsers($mysqli, $emailUser, $hash, $nameUser, $cpfUser, $dateUser);

    if($registerComplete === true) {
        $mensagem = "Pessoa cadastrada com sucesso!";
    } else {
        $mensagem = "Não foi possível cadastrar. Verifique os dados e tente novamente.";
    }

}




?>

<h1 class="mb-4">Cadastrar Pessoa</h1>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-4">Novo Cadastro</h5>

            <?php if (isset($mensagem)): ?>
                <div class="alert alert-<?= $registerComplete ? 'success' : 'danger' ?>">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>

        <form method="POST" action="">

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00" maxlenght="14">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nascimento" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="nascimento" name="nascimento">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>

        </form>
    </div>
</div>

