<?php
    include_once('./../conection.php');

    if(isset($_POST['update'])) {
        // Recebe os dados do formulário
        $id = $_POST['id'];
        $name = $_POST['name']; 
        $email = $_POST['email']; 
        $password = $_POST['password']; 
        $date = $_POST['date']; 

        // Verifica se uma imagem foi enviada
        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name']; 
            $imageData = addslashes(file_get_contents($imageTmpPath)); 
        } else {
            $imageData = NULL; 
        }

        // Validação básica (pode expandir se necessário)
        if (!empty($name) && !empty($email) && !empty($password) && !empty($date)) {
            // Atualiza os dados no banco de dados
            if ($imageData !== NULL) {
                $sqlUpdate = "UPDATE user SET nome='$name', email='$email', senha='$password', nascimento='$date', image='$imageData' WHERE id='$id'";
            } else {
                $sqlUpdate = "UPDATE user SET nome='$name', email='$email', senha='$password', nascimento='$date' WHERE id='$id'";
            }

            // Executa a query de atualização
            if ($conection->query($sqlUpdate) === TRUE) {
                // Redireciona após sucesso
                header('Location: ./dashboard.php');
            } else {
                echo "Erro ao atualizar os dados: " . $conection->error;
            }
        } else {
            echo "Todos os campos devem ser preenchidos!";
        }
    }
?>
