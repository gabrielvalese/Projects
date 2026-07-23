
## 10 de julho, 2026

## Tags: #DB , #Backend , #SQL, #PHP 

## Início: 16/07
## Finalizado: 23/07

## TAGS: #Finished 

---
	Usuário e senha
	010910.Gv$corinthians


# Projeto CRUD

## 1. Ajustando o BD
- Criação de tabelas com chave primária em email
![[Pasted image 20260719122143.png]]

## 2. Montando o front-end

Como o objetivo nesse caso é o CRUD em si, utilizei o bootstrap + Claude.ia para a montagem do front. Ajustarei-o com minhas necessidades.

## 3. Conexão com arquivo config.php

Auto-explicativo:

```
<?php
// ========== Config ============
$_DB['server']   = 'localhost';         // Servidor MySQL
$_DB['user']     = 'root';              // Usuário MySQL
$_DB['password'] = '';                  // Senha MySQL (vazio = padrão no WAMP)
$_DB['database'] = 'cadastro';  // Banco de dados MySQL
// ==============================

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Faz o mysqli lançar exceção em erro

try {
    $mysqli = new mysqli($_DB['server'], $_DB['user'], $_DB['password'], $_DB['database']);
    $mysqli->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Alguma coisa estranha aconteceu...');
}
?>
```


É criado um array associativo chamado ``` $_DB ```, que guarda as informações de: servidor, que é o localhost ness caso, o usuário, que no meu caso é o root, o usuário padrao do xampp e mySQL; senha padrão '' (vazia) e a database de cadastro, pois é o nome dado no MySQL, que puxa com o PhpMyAdmin

#### Explicação da lógica do try e catch

```
try {
    $mysqli = new mysqli($_DB['server'], $_DB['user'], $_DB['password'], $_DB['database']);
    $mysqli->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Alguma coisa estranha aconteceu...');
}
?>
```
O try, primeiramente vai tentar executar o bloco com as informações. Nesse 'try', ele criará uma variável $mysqli, que guardará em forma de objeto da classe mysqli, que já é criada por padrão.

A classe mysqli é de Mysql improved, é como uma conexão, que é guardada na variável $mysqli, entre o PHP e o MySQL, e passa as conexões que queremos, que é o array associativo

Logo, o arquivo config.php cria conexao com o banco de dados, guardando dados de acesso, e para mostrar erro caso a conexão não dê .

## 4. Cadastro dos usuários

Cria-se uma função, em um arquivo separado. A finalidade é deixar organizado as funções, e ao meu ver, parece mais simple de controlá-las e deixar seguro. chama ela com

<?php require_once "functions.php"; ?>

A função em si tem essa cara:

```
function registerUsers($mysqli, $emailUser, $senhaUser, $nameUser, $cpfUser, $dateUser) {

    $sql = "INSERT INTO pessoas (email, senha, nome, cpf, nascimento) VALUES (?, ?, ?, ?, ?)";

    $register = $mysqli->prepare($sql);

  

    $register->bind_param("sssss", $emailUser, $senhaUser, $nameUser, $cpfUser, $dateUser);

  

    try {

        $register->execute();

        return true;

    } catch (Exception $e) {

        error_log($e->getMessage());

        return false;

}
```

Nela, chamamos as variáveis de PHP que iremos pegar para o cadatro, e, o mais importante, a varável $mysqli, encontrado supracitado, como uma variável que guarda o objeto mysqli, que é a conexão com o banco. Ela, possui diversos métodos, que iremos ver adiante.

A variável $sql guarda os parâmetros que queremos enviar para o SQL, a linha de código que será executada, onde dentro de pessoas, os parâmetros definidos. O "?" definido para cada valor, é o chamado de *placeholders*, o que evita SQL injection.

No $register, a gente prepara para receber os parâmetros e o código de SQL, com os placeholders, enviando ao MySQL antes de qualquer coisa ser enviada. No bind_params, ele vai definir os valores, com uma sigla de "s", pois tudo será uma string, e as variáveis em suas respectivas ordens. O execute, então, vai seguir toda essa lógica de raciocínio.

A função "close", fecha a conexão do mysqli com o banco de dados. É necessário? Não necessariamente, pois o PHP já fecha a conexão. Mas, de acordo com a aula de PHP + BD, é uma boa prática certificar.

Ele vai tentar realizar a execução com o método execute(), e retornará true.
Se não der certo a conexão, ele vai gerar uma mensagem de erro, e retornar false

Agora iremos jogar essa função a folha cadastro.php, que fica:

```
  

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
```

A lógica é simples, e foi a mais segura por enquanto e que me fez mais sentido logicamente para formulários, aplicados nos exercícios: o ?? é como uma condição ternária , que ele vai aplicar um ou outro; ou ele guarda o que tiver dentro do array *$POST, ou não faz nada* , deixa null, que é igual a nada. Na função empty, ele lê, se tiver retornado true, ele aplica o código. Senão, ele mostra que não deu bom

Então, a lógica é: se estiver tudo definido (com o símbolo &&), a gente chama a função que criamos anteriormente. 

### Importante:  
O hash, é uma encriptação de senhas muito importante. Ele codifica tudo em 64 caracteres. Não é a forma mais segura, mas já é uma dor de cabeça. Pode ser desencriptada por, como exemplo rainbow tables

## 5. Visualização de Usuários


A visualização é tranquila. De forma geral, ele vai procurar dentro do  método post se foi salva, jogar numa função que criamos, e visualiza-se com um foreach.

Entretanto, para melhorar o UX, decidi criar duas variáveis:

```
function listEmail($mysqli, $emailUserSearch) {

    if (empty($emailUserSearch)) {

        return [];

    }

  

    $sql = "SELECT * FROM pessoas WHERE email = ?";

    $searchUser = $mysqli->prepare($sql);

    $searchUser->bind_param("s", $emailUserSearch);

  

    try {

        $searchUser->execute();

        $resultSearch = $searchUser->get_result();

        return $resultSearch->fetch_all(MYSQLI_ASSOC);

    } catch (Exception $e) {

        error_log($e->getMessage());

        return [];

    }

}
```

iniciando pela lisEmail, ela recebe o parâmetro $emailUserSearch e a conexão do banco. Se estiver vazia, ela retorna nada, pois não quebra o código e é fácil impor a lógica de que se estiver vazio, aparecerá uma mensagem. Nela, a gente envia o código ao SQL, e envia por meio do método 'fetch_all(MYSQLI_ASSOC);' um array associativo da consulta feita dentro da função. Senão, ele envia um erro.

Com esta lógica, ela não se limita a apenas a visualização (como adiante será visto), mas ela poderá ser usada a qualquer hora que necessite realizar essa visuaização, como no caso de exclusão, que far-se-á uma busca, assim como na edição

 ```
 if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($emailUserSearch)) {

    $usersInfoArray = listEmail($mysqli, $emailUserSearch);

}
 ```
 Dentro do visualizar.php fica assim: ela lista apenas o que o POST receber, e não deixa ficar null, pois há o required do HTML.



Entretanto, e se um administrador não tiver os dados, e querer visualizar todos? Devido isso, cria-se uma função que irá listar todos os usuários, com lógica similar

```
function listAllUsers($mysqli) {

    $sql = "SELECT * FROM pessoas";

    $searchUsers = $mysqli->prepare($sql);

  

    try {

        $searchUsers->execute();

        $resultSearch = $searchUsers->get_result();

        return $resultSearch->fetch_all(MYSQLI_ASSOC);

    } catch (Exception $e) {

        error_log($e->getMessage());

        return [];

    }

}
```

E dentro do visualizar.php: 

```
$usersInfoArray = listAllUsers($mysqli);
```

E, de resto, usamos if e foreach para que apareça tudo corretamente, e se o $usersInfoArray chegar vazio, aparece uma mensagem. O código fica:

```
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
```



## 6. Exclusão de usuários

A exclusão de usuário, como citado anteriormente, usaremos a lógica de visualização da função listEmails. Simplesmente, chama a função e os parâmetros e email, e deleta.

```
function deleteEmail($mysqli, $emailUserDelete) {

        $sql = "DELETE FROM pessoas WHERE email = ?";

        $deleteUser = $mysqli->prepare($sql);

  

        $deleteUser->bind_param("s", $emailUserDelete);

  

        try {

            $deleteUser->execute();

            $deleteUser->close();

            return true;

        } catch  (Exception $e) {

                error_log($e->getMessage());

                return false;

  

    }
```

No código, então, excluir.php:

```
<?php require_once "config.php"; ?>

<?php require_once "includes/header.php"; ?>

<?php require_once "functions.php"; ?>

  

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
```

## 7. Edição de usuários.

A parte mais complexa de um CRUD, é a edição. Nela, precisamos criar uma função de edição com conexão ao banco; listar os usuários via email; criar toda a condição diante do botão salvar, recolher os dados do usuário a se mudar, e que salva apenas o que for alterado e, se enviar nulo, recupera as informações que recolhemos.

Sobre a função:

```
function editUser($mysqli, $emailUserPK, $senhaEdit, $nomeEdit, $cpfEdit, $nascimentoEdit) {

    $sql = "UPDATE pessoas SET senha = ?, nome = ?, cpf = ?, nascimento = ? WHERE email = ?";

    $editUser = $mysqli->prepare($sql);

  

    $editUser->bind_param("sssss", $senhaEdit, $nomeEdit, $cpfEdit, $nascimentoEdit, $emailUserPK);

  

    try {

        $editUser->execute();

        $editUser->close();

        return true;

    } catch (Exception $e) {

        error_log($e->getMessage());

        return false;

}

  

}

?>
```

Como todas as estruturas anteriores, usamos uma lógica de mistura com o cadastro e as demais, mas que não foge do cotidiano deste projeto.

A lógica em si do código:

```
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
```

O usuário fará uma pesquisa. Para facilitar a visualização e alteração, no "value" do HTML, já enviamos os valores do array de listEmail, que citamos. Apenas o email que não poderá ser alterado --- pois trata-se da chave principal, embora poderia ser trocado por um id --- e a senha, pois trata-se, no banco de dados, de uma criptografia hash.

Então, caso o usuário aperte no botão, aparecerá um alerta básico de HTML, confirmando. Porém, no back-end, essa lógica funciona neste código da seguinte forma:
Se a variável que guarda o email dentro do input de readonly não estiver vazio, criamos, de antemão uma variável que receberá a consulta do email, com a função listEmail, em que, diferente da anterior, isolamos uma para busca e outra para valor, pois uma lê o que o usuário recebe, e outra de onde o array associativo entrega.

Com isso, criamos toda a lógica de, uma variável para cada valor (que já está pré-definido) em que, numa condição ternária, se não estiver vazio os dados enviados, a variável recebe a mudança informada no início do código. Senão, ela recebe o que já está gravado no array de variável $dataUser. A lógica da senha é a mesma. Porém, transformamos em hash novamente.

Então, ela é trazida para a função que criamos de edição, todos os valores que recebemos das variáveis.

Como retorna true ou false na nossa função de edição, se der certo a edição dos dados, guardamos na variável $mensagem que foi concluído. Vice-versa para se a edição não ocorrer com êxito. 

Por fim, no HTML, é feito as condições de se não achar.

