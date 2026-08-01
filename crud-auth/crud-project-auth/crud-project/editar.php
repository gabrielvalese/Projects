<?php require_once "config.php"; ?>
<?php require_once "includes/header.php"; ?>
<?php require_once "functions.php"; ?>

<?php
$emailUserSearch = $_POST['emailSearch'] ?? null;
$emailUserPK = $_POST['email'] ?? null;
$senhaEdit = $_POST['senha'] ?? null;
$nomeEdit = $_POST['nome'] ?? null;
$cpfEdit = $_POST['cpf'] ?? null;
$nascimentoEdit = $_POST['nascimento'] ?? null;

$usersInfoArray = [];
$mensagem = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['buscar'])) {
        $usersInfoArray = listEmail($mysqli, $emailUserSearch);
    } 

    if(isset($_POST['salvar'])) {
        if (!empty($emailUserPK)) {
            $userSearch = listEmail($mysqli, $emailUserPK);

            if(!empty($userSearch)) {
                $dataUser = $userSearch[0];

                $savePassword = !empty($senhaEdit) ? password_hash($senhaEdit, PASSWORD_BCRYPT) : $dataUser['senha'];
                $saveName = !empty($nomeEdit) ? $nomeEdit : $dataUser['nome'];
                $saveCpf = !empty($cpfEdit) ? $cpfEdit : $dataUser['cpf'];
                $saveDate = !empty($nascimentoEdit) ? $nascimentoEdit : $dataUser['nascimento'];

                $edit = editUser(
                    $mysqli,
                    $emailUserPK,
                    $savePassword,
                    $saveName,
                    $saveCpf,
                    $saveDate
                );

                if ($edit === true) {
                    $mensagem = "Dados atualizados com sucesso!";
                    $usersInfoArray = listEmail($mysqli, $emailUserPK);
                } else {
                    $mensagem = "Não foi possível atualizar os dados.";
                }
            } else {
                $mensagem = "Usuário não encontrado.";
            }
        }
    }
}



?>

<h1 class="mb-4">Editar Pessoa</h1>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">Buscar Pessoa</h5>
        <form method="POST" action="" class="input-group">
            <input type="email" class="form-control" name="emailSearch" placeholder="Digite o email" required>
            <button type="submit" class="btn btn-warning" name="buscar" value="1">Buscar</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-4">Editar Dados</h5>

        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-info"><?= $mensagem ?></div>
        <?php endif; ?>

        <form method="POST" action="">
        <?php if (!empty($usersInfoArray)): ?>
            <?php foreach ($usersInfoArray as $pessoa): ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $pessoa['email'] ?>" readonly>
                    <div class="form-text">O email não pode ser alterado.</div>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="A senha não pode ser visualizada. Caso deseja trocar, digite a nova senha.">
                </div>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= $pessoa['nome'] ?>">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" value="<?= $pessoa['cpf'] ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="nascimento" name="nascimento" value="<?= $pessoa['nascimento'] ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['buscar'])): ?>
            <div class="alert alert-warning">Nenhuma pessoa encontrada com o e-mail informado.</div>
        <?php endif; ?>

            <button type="submit" class="btn btn-warning" name="salvar" value="1" onclick="return confirm('Você tem certeza que deseja alterar as informações?')">Salvar Alterações</button>
            <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>

        </form>
    </div>
</div>
