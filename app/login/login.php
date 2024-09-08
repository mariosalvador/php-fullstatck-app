<?php
session_start();


// Verifica se o usuário já está logado
if (isset($_SESSION['email'])) {
    header('Location: ./../home/home.php');
    exit();
}

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    include_once('./../conection.php');
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `user` WHERE email = '$email' AND senha = '$password'";
    $result = $conection->query($sql);

    if (mysqli_num_rows($result) < 1) {
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset( $_SESSION['idUsuario']);
        unset( $_SESSION['name']);
        header('Location: login.php');
    } else {
        $user = $result->fetch_assoc();
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        $_SESSION['idUsuario'] = $user['id'];
        $_SESSION['name'] = $user['nome'];

        print_r($user['id']);
        header('Location: ./../home/home.php');
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
    <main>
           <form action="login.php" method="post" >
                <h1>Login</h1>
                <div  >
                    <div id="div-input">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="digite o seu email...">
                    </div>

                    <div id="div-input">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="digite a senha...">
                        <a href="./../register/register.php">
                            <span>Criar conta?</span>
                        </a>
                        
                    </div>

                    <button type="submit" name="submit" >Sign In</button>
                </div>
           </form>
    </main>
</body>
</html>