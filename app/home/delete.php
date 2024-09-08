<?php
    include_once('./../conection.php');

    if (!empty($_GET['id'])) {
        $id = intval($_GET['id']);
 
        $sqlSelect = "SELECT * FROM user WHERE id=$id";
        $result = $conection->query($sqlSelect);
 
        if ($result->num_rows > 0) {
            // Deleta o usuário
            print_r('ola');
            $sqlDelete = "DELETE FROM user WHERE id=$id";
            $conection->query($sqlDelete);
        }
    }

    header('Location: ./dashboard.php');
?>
