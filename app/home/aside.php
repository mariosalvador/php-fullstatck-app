<?php 
    include_once('./../conection.php');

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $idUsuario = $_SESSION['idUsuario']; 
    $sql = "SELECT user.nome, entidade.tipo AS entidade_tipo FROM user 
            LEFT JOIN entidade ON user.entidade_foreign_key = entidade.id 
            WHERE user.id='$idUsuario'";
    $result = $conection->query($sql);

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $user_name = $user_data['nome']; 
        $entidade_tipo = $user_data['entidade_tipo'];
    }
?>

<aside class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px; height:100%; overflow:;">
        <h1 class="fs-4 d-flex align-items-center mb-3 mb-md-0 me-md-auto" >SOCARTAO</h1>
        <hr>
        <ul class="nav nav-pills flex-column" style="margin-bottom:auto" id="nav-list">
            <li class="nav-item ">
                <a href="./home.php" class="nav-link d-flex align-items-center gap-3" aria-current="page" data-link="home">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
                    </svg>
                Home
                </a>
            </li>

            <?php if ($entidade_tipo === 'admin'): ?>
            <li>
                <a href="./dashboard.php" class="nav-link d-flex align-items-center gap-3" data-link="dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16">
                    <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1zm1 12h2V2h-2zm-3 0V7H7v7zm-5 0v-3H2v3z"/>
                    </svg>
                Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link d-flex align-items-center gap-3" data-link="add">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>
                Add
                </a>
            </li>
            <?php endif; ?>
            </ul>

            <div style="margin-bottom:100px;" >
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2" style="">
                    <!-- <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li> -->
                    <li><a class="dropdown-item" href="./profile.php">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="./sair.php">Sign out</a></li>
                </ul>
            </div> <!-- Elementos que da div dropdown -->

        <hr>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mariosalvador.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong> <?php echo $user_name; ?> </strong>
            </a>
        </div>
</aside>

<script>
    // Dropdown toggle functionality
    const dropdownToggle = document.querySelector('#dropdownUser2');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    dropdownToggle.addEventListener('click', function(event) {
        event.preventDefault();
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
        dropdownMenu.style.display = isExpanded ? 'none' : 'block'; // Toggle display
    });

    // Função para fechar o dropdown ao clicar fora dele
    window.addEventListener('click', function(event) {
        if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none';
        dropdownToggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Função para marcar o link ativo
    const navLinks = document.querySelectorAll('.nav-link');

    function setActiveLink(link) {
        navLinks.forEach(link => link.classList.remove('active')); // Remove a classe 'active' de todos
        link.classList.add('active'); // Adiciona a classe 'active' ao link clicado
        localStorage.setItem('activeLink', link.getAttribute('data-link')); // Armazena no localStorage
    }

    // Ao clicar em um link
    navLinks.forEach(link => {
        link.addEventListener('click', function(event) {
        setActiveLink(this);
        });
    });

    // Quando a página for carregada, verifica se há um item salvo no localStorage
    window.addEventListener('load', function() {
        const activeLink = localStorage.getItem('activeLink');
        if (activeLink) {
        const linkToActivate = document.querySelector(`[data-link="${activeLink}"]`);
        if (linkToActivate) {
            setActiveLink(linkToActivate); // Define o link ativo com base no valor armazenado
        }
        }
    });
</script>
