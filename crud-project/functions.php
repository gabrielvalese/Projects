<?php

function registerUsers($mysqli, $emailUser, $senhaUser, $nameUser, $cpfUser, $dateUser) {
    $sql = "INSERT INTO pessoas (email, senha, nome, cpf, nascimento) VALUES (?, ?, ?, ?, ?)";
    $register = $mysqli->prepare($sql);

    $register->bind_param("sssss", $emailUser, $senhaUser, $nameUser, $cpfUser, $dateUser);

    try {
        $register->execute();
        $register->close();
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
}

}

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

function deleteEmail($mysqli, $emailUserDelete) {
        $sql = "DELETE FROM pessoas WHERE email = ?";
        $deleteUser = $mysqli->prepare($sql);

        $deleteUser->bind_param("s", $emailUserDelete);

        try {
            $deleteUser->execute();
            $deleteUser->close();
            return true;
        } catch  (Exception $e) {
                error_log($e->getMessage());
                return false;

    }
}

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