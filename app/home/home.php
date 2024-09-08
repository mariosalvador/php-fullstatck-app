    <?php
        $content = $content ?? '';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        include_once('./../conection.php');

        // Verifica se o usuário está logado
        if (!isset($_SESSION['email']) || !isset($_SESSION['password'])) {
            // Redireciona para a página de login se não estiver logado
            header('Location: ./../login/login.php');
            exit();
        }

        $logado = $_SESSION['email'];
    ?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="home.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- <title>Pagina Principal</title> -->
        <title> <?php echo $title ?? 'Home'; ?> </title> 
    </head>

    <body>
        <main>
            <div id="div-content">
            
                <?php
                    include('./header.php');
                ?>
                <div id="main-content">
                    <?php echo $content; ?>
                </div>
               
            </div>

            <?php
                include('./aside.php');
            ?>
        </main>
       
    </body>
</html>
