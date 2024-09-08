<link rel="stylesheet" href="header.css">

<?php 
  
    include_once('./../conection.php');

    $logado;
    $idUsuario=$_SESSION['idUsuario']; 

 
    $sql = "SELECT image FROM user WHERE id='$idUsuario'";
    $result = $conection->query($sql);
    $imageSrc = '';

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (!empty($user['image'])) {
            
            // Gerar uma URL base64 para a imagem
            $imageData = base64_encode($user['image']);
            $imageSrc = 'data:image/jpeg;base64,' . $imageData;
        }
    }
?>


<header class='header'>
    <div class="div-header-content">
        <section id="section-profile">
            <?php
                echo "<span style=display:flex; align-items:center; >" . htmlspecialchars($logado, ENT_QUOTES, 'UTF-8') . "</span>";
            ?>
            <span id="circle-img">
                <?php if (!empty($imageSrc)): ?>
                    <img src="<?php echo $imageSrc; ?>" alt="Imagem do perfil" id="profile-img">
                <?php else: ?>
                    <img src="default-avatar.png" alt="Imagem padrão" id="profile-img"> 
                <?php endif; ?>
            </span>
        </section>
    </div>
</header>



