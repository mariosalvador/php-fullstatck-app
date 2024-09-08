<link rel="stylesheet" href="profile.css">

<?php
        session_start();
        include_once('./../conection.php');

        $idUsuario=$_SESSION['idUsuario']; 
        $sql = "SELECT * FROM user WHERE id='$idUsuario'";
        $result = $conection->query($sql);

        if($result->num_rows > 0){

           $user_data = $result->fetch_assoc();
           $name=$user_data['nome'];
           $email=$user_data['email'];
           $date=$user_data['nascimento'];
           $password=$user_data['senha'];

           if (!empty($user_data['image'])) {
            $image=$user_data['image'];
            // Gerar uma URL base64 para a imagem
            $imageData = base64_encode($image);
            $imageSrc = 'data:image/jpeg;base64,' . $imageData;
        }

        }

    $title = "Profile"; // Definindo o título para esta página
    ob_start(); // Inicia o buffer de saída
    ?>
            <section id="section-edit-content" >
                <form action="./edit.php?id=<?php echo $idUsuario; ?>" method="POST" enctype="multipart/form-data">
                  
                    <section id="section-form"  >
                        <article id="article-edit-photo">
                            <img src="<?php echo $imageSrc; ?>"  alt="Preview da Image " id="preview-image">
                        </article>
                    </section>

                    <section id="section-form-edit">
                        <h2>Informações</h2>
                        <div id="div-form-edit">
                            <div id="div-input">
                                <label for="name">Nome</label>
                                <input disabled type="text" name="name" id="name" value="<?php echo $name ?>" >
                            </div>
                            <div id="div-input">
                                <label for="email">Email</label>
                                <input disabled type="email" name="email" id="email" value="<?php echo $email ?>" >
                            </div>
                            <div id="div-input">
                                <label for="date">Data de nascimento</label>
                                <input disabled type="date" name="date" id="date" value="<?php echo $date ?>">
                            </div>
                            <div id="div-input">
                                <label for="password">Password</label>
                                <input disabled type="text" name="password" id="password" value="<?php echo $password ?>" >
                            </div>

                            <input name="id" id="id" value="<?php echo $id ?>" type="hidden"  >

                        </div>

                        <button type="submit" name="update" id="update">Editar</button>
                    </section>
                </form>
            </section>

    <?php
    $content = ob_get_clean(); // Pega o conteúdo da página
    include('home.php'); // Inclui o layout base
?>