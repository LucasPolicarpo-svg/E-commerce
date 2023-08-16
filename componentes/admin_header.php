<?php
    if(isset($message)){
        foreach($messages as $msg){
            echo '<div class="message">
                <span>'.$msg.'</span>
                <i class="fas fa-times" onclick="this.parentElement.remove()"></i>
            </div>';
        }
    }
    
?>

<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">Painel<span>Administrador</span></a>

        <nav class="navbar">
            <a href="dashboard.php">Home</a>
            <a href="produtos.php">Produtos</a>
            <a href="pedidos_feitos.php">Pedidos</a>
            <a href="admin_contas.php">Administradores</a>
            <a href="contas_usuarios.php">Usuários</a>
            <a href="mensagens.php">Mensagens</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <?php
            
                $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                $select_profile->execute([$admin_id]);

                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            
            ?>

            <p><?php echo $fetch_profile['name']; ?></p>

            <a href="update_profile.php" class="btn">Modificar Perfil</a>

            <div class="flex-btn">
                <a href="admin_login.php" class="option-btn">Entrar</a>
                <a href="register_admin.php" class="option-btn">Cadastrar</a>
            </div>

            <a href="../componentes/admin_logout.php" class="delete-btn">Sair</a>
        </div>
    </section>
</header>