<?php
require_once "seguranca.php";
verifySession();
require_once "config.php";
require_once "includes/header.php";
?>
<?php require_once "functions.php"; ?>
<? require_once "valida.php"; ?>

<?php

$emailDelete = $_POST['email'] ?? null;

$usersInfoArray = [];



if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST['buscar'])) {
        $usersInfoArray = listEmail($mysqli, $emailDelete);
    } elseif (isset($_POST['confirmarExclusao'])) {
        $deleteUser = deleteEmail($mysqli, $emailDelete);
    }


}

?>

<h1 class="mb-4">Excluir Pessoa</h1>


<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">Buscar Pessoa</h5>
        <form method="POST" action="" class="input-group">
            <input type="email" class="form-control" name="email" placeholder="Digite o email" required>
            <button type="submit" class="btn btn-danger" name="buscar">Buscar</button>
        </form>
    </div>
</div>


<div class="card shadow-sm border-danger">
    <div class="card-body">
        <div class="alert alert-danger">
            ⚠️ Tem certeza que deseja excluir este registro? Essa ação não pode ser desfeita.
        </div>
	<table class="table">
        <?php foreach ($usersInfoArray as $pessoa): ?>
                       <tr><th>Email</th><td><?= $pessoa['email'] ?></td></tr>
                        <tr><th>Nome</th><td><?= $pessoa['nome'] ?></td></tr>
                        <tr><th>CPF</th><td><?= $pessoa['cpf'] ?></td></tr>
                      	
                    <?php endforeach; ?>

	</table>

                    

                
            
        <?php if($usersInfoArray === []): ?>
                        <p style="display: flex; align-items: center; justify-content: center; color: #989595">achemo nada aqui piá</p>
                    <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="email" value="<?= $pessoa["email"] ?>">
            <button type="submit" class="btn btn-danger" name="confirmarExclusao" onclick="return confirm('Você tem certeza que deseja excluir este usuário?')" >Confirmar Exclusão</button>
            <a href="index.php" class="btn btn-outline-secondary" name="cancelar">Cancelar</a>
        </form>
    </div>
</div>

