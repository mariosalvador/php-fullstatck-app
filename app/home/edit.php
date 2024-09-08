<link rel="stylesheet" href="edit.css">

<?php
    session_start();
    include_once('./../conection.php');

    // Verifica se o usuário está logado
    if (!isset($_SESSION['email']) || !isset($_SESSION['password'])) {
        header('Location: ./../login/login.php');
        exit();
    }

    // Recupera o email e tipo de entidade do usuário logado
    $logado = $_SESSION['email'];
    $sql = "SELECT user.id AS user_id, user.entidade_foreign_key, entidade.tipo AS entidade_tipo 
            FROM user 
            LEFT JOIN entidade ON user.entidade_foreign_key = entidade.id 
            WHERE user.email = ?";

    $stmt = $conection->prepare($sql);
    $stmt->bind_param('s', $logado);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header('Location: ./../login/login.php');
        exit();
    }

    $current_user_data = $result->fetch_assoc();
    $current_user_id = $current_user_data['user_id'];
    $current_user_type = $current_user_data['entidade_tipo'];

    // Processo de edição
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        // Se o usuário logado não for admin e tentar editar os dados de outro usuário, redireciona para a edição dos próprios dados
        if ($current_user_type !== 'admin' && $id != $current_user_id) {
            header("Location: ./edit.php?id=$current_user_id");
            exit();
        }

        // Busca os dados do usuário para edição
        $sqlSelect = "SELECT * FROM user WHERE id = ?";
        $stmt = $conection->prepare($sqlSelect);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $name = $user_data['nome'];
            $email = $user_data['email'];
            $password = $user_data['senha'];
            $date = $user_data['nascimento'];
        } else {
            header('Location: ./dashboard.php');
            exit();
        }
    }

    $title = "Edit"; // Definindo o título para esta página
    ob_start(); // Inicia o buffer de saída
?>

<section id="section-edit-content">
    <form action="./editSave.php" method="POST" enctype="multipart/form-data">

        <section id="section-form-edit">
            <h1>Editar</h1>
            <div id="div-form-edit">
                <div id="div-input">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Digite o seu nome...">
                </div>
                <div id="div-input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Digite o seu email...">
                </div>
                <div id="div-input">
                    <label for="date">Data de nascimento</label>
                    <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div id="div-input">
                    <label for="password">Password</label>
                    <input type="text" name="password" id="password" value="<?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Digite a senha...">
                </div>

                <input name="id" id="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>" type="hidden">
            </div>
        </section>

        <section id="section-form">
            <article id="article-edit-photo">
                <input id="input-file" type="file" name="image" accept="image/*" onchange="loadFile(event)">
                <img id="preview-image" alt="Preview da Imagem" style="display: none;" />
            </article>

            <script>
                function loadFile(event) {
                    const output = document.getElementById('preview-image');
                    output.src = URL.createObjectURL(event.target.files[0]);
                    output.onload = function() {
                        URL.revokeObjectURL(output.src) // Libera a memória após carregar a imagem
                    }
                    output.style.display = 'block';
                }
            </script>

            <button type="submit" name="update" id="update">Editar</button>
        </section>

    </form>
</section>

<?php
    $content = ob_get_clean(); // Pega o conteúdo da página
    include('home.php'); // Inclui o layout base
?>
