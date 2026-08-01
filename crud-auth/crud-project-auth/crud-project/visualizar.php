<?php require_once "config.php"; ?>
<?php require_once "includes/header.php"; ?>
<?php require_once "functions.php"; ?>

<?php

$emailUserSearch = $_POST['email'] ?? null;

$usersInfoArray = listAllUsers($mysqli);

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($emailUserSearch)) {
    $usersInfoArray = listEmail($mysqli, $emailUserSearch);
}

?>



<h1 class="mb-4">Visualizar Pessoas</h1>


<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">Buscar por Email</h5>
        <form method="POST" action="" class="input-group">
            <input type="email" class="form-control" name="email" placeholder="email@exemplo.com" required>
            <button type="submit" class="btn btn-info text-white">Buscar</button>
        </form>
    </div>
</div>


<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Todos os Registros</h5>
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Email</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Nascimento</th>
                </tr>
            </thead>
            <tbody>
                    <?php foreach ($usersInfoArray as $pessoa): ?>
                        <tr>
                        <td><?= $pessoa['email'] ?></td>
                        <td><?= $pessoa['nome'] ?></td>
                        <td><?= $pessoa['cpf'] ?></td>
                        <td><?= $pessoa['nascimento'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                    

                
            </tbody>
            
        </table>
        <?php if($usersInfoArray === []): ?>
                        <p style="display: flex; align-items: center; justify-content: center; color: #989595">achemo nada aqui piá</p>
                    <?php endif; ?>
    </div>
</div>


