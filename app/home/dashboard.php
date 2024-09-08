<?php
    session_start();
    include_once('./../conection.php');

    // Verifica se o usuário está logado
    if (!isset($_SESSION['email']) || !isset($_SESSION['password'])) {
        header('Location: ./../login/login.php');
        exit();
    }

    // Recupera o tipo de entidade do usuário logado
    $logado = $_SESSION['email'];
    $sql = "SELECT user.entidade_foreign_key, entidade.tipo AS entidade_tipo 
            FROM user 
            LEFT JOIN entidade ON user.entidade_foreign_key = entidade.id 
            WHERE user.email = ?";

    $stmt = $conection->prepare($sql);
    $stmt->bind_param('s', $logado);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Usuário não encontrado
        header('Location: ./../login/login.php');
        exit();
    }

    $user_data = $result->fetch_assoc();
    if ($user_data['entidade_tipo'] !== 'admin') {
            header('Location: ./home.php');
        exit();
    }

    // Consulta principal
    $sql = "SELECT user.id, user.nome, user.email, user.nascimento, entidade.tipo AS entidade_tipo 
            FROM user 
            LEFT JOIN entidade ON user.entidade_foreign_key = entidade.id";
    
    if (isset($_POST['search']) && !empty($_POST['inputSearch'])) {
        $searchValue = '%' . $_POST['inputSearch'] . '%';
        $sql .= " WHERE user.nome LIKE ? OR user.email LIKE ?";
    }

    $stmt = $conection->prepare($sql);

    if (isset($searchValue)) {
        $stmt->bind_param('ss', $searchValue, $searchValue);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $title = "Dashboard"; // Definindo o título para esta página
    ob_start(); // Inicia o buffer de saída
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div style="width:95%; height:95%; display:flex; gap:30px; align-items:start; justify-content:start;">
        <div style="width:95%; height:80%; background-color:white; display:flex; flex-direction:column; align-items:center; justify-content:start; box-shadow: 1px 8px 20px -10px black;">
            <form method="POST" action="" class="input-container">
                <input class="custom-input" placeholder="Pesquisar..." id="inputSearch" name="inputSearch" value="<?php echo isset($_POST['inputSearch']) ? htmlspecialchars($_POST['inputSearch'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                <button type="submit" name="search" id="search">Pesquisar</button>
            </form>

            <table class="table" style="border-radius: 10px; width:90%;">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Nascimento</th>
                        <th scope="col">Entidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($show_user_data = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($show_user_data['id'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($show_user_data['nome'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($show_user_data['email'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($show_user_data['nascimento'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($show_user_data['entidade_tipo'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>
                                    <a class='btn btn-primary btn-sm' href='./edit.php?id=" . htmlspecialchars($show_user_data['id'], ENT_QUOTES, 'UTF-8') . "'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                                            <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325'/>
                                        </svg>
                                    </a>
                                    <a href='./delete.php?id=" . htmlspecialchars($show_user_data['id'], ENT_QUOTES, 'UTF-8') . "' class='btn btn-danger btn-sm'>
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                            <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                                        </svg>
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Nenhum usuário encontrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    $content = ob_get_clean(); // Pega o conteúdo da página
    include('home.php'); // Inclui o layout base
    ?>
</body>
</html>
