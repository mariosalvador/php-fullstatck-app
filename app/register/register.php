
<?php
session_start();

// Verifica se o usuário já está logado
if (isset($_SESSION['email'])) {
    header('Location: ./../login/login.php');
    exit();
}

include_once('./../conection.php');

// Função para validar o email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Função para validar a senha (mínimo de 6 caracteres)
function is_valid_password($password) {
    return strlen($password) >= 6;
}

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $date = trim($_POST['date']);

    // Verifica se todos os campos estão preenchidos
    if (empty($name) || empty($email) || empty($password) || empty($date)) {
        echo '<h1>Todos os campos são obrigatórios!</h1>';
    } elseif (!is_valid_email($email)) {
        echo '<h1>Email inválido!</h1>';
    } elseif (!is_valid_password($password)) {
        echo '<h1>A senha deve ter pelo menos 6 caracteres!</h1>';
    } else {
        // Protege contra SQL injection
        $name = mysqli_real_escape_string($conection, $name);
        $email = mysqli_real_escape_string($conection, $email);
        $password = mysqli_real_escape_string($conection, $password);
        $date = mysqli_real_escape_string($conection, $date);

        // Verifica se o email já está cadastrado
        $query = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($conection, $query);

        if (mysqli_num_rows($result) > 0) {
            echo '<h1>Este email já está cadastrado!</h1>';
        } else {
            // Insere os dados no banco de dados
            $query = "INSERT INTO user (nome, email, nascimento, senha,entidade_foreign_key) VALUES ('$name', '$email', '$date', '$password','Select tipo from entidade where id=2 ')";
            if (mysqli_query($conection, $query)) { 
                echo '<h1>Cadastro realizado com sucesso!</h1>';
            } else {
                echo '<h1>Erro ao cadastrar: ' . mysqli_error($conection) . '</h1>';
            }
        }
    }
} else {
    // echo '<h1>Erro</h1>';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Register</title>
</head>
<body>
    <main>
        <form action="register.php" method="post">
            <h1>Register</h1>
            <div>
                <div id="div-input">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" placeholder="Digite o seu nome...">
                </div>

                <div id="div-input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Digite o seu email...">
                </div>

                <div id="div-input">
                    <label for="date">Data de nascimento</label>
                    <input type="date" name="date" id="date">
                </div>

                <div id="div-input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Digite a senha...">
                </div>

                <a href="./../login/login.php">
                    <span>Possui uma conta?</span>
                </a>
                        

                <button type="submit" name="submit" id="submit">Register</button>
            </div>
        </form>
    </main>
</body>
</html>
