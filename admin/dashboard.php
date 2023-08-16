<?php

    include '../componentes/connect.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];

    if(!isset($admin_id)){
        header('location:admin_login.php');
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>dashboard</title>
        <link rel="stylesheet" href="../css/style_admin.css">
    </head>
    <body>
        




        <script src="../js/admin_script.js"></script>
        <script src="https://kit.fontawesome.com/cf6b52a942.js" crossorigin="anonymous"></script>

    </body>
</html>


<?php

    include '../componentes/admin_header.php'

?>

<section class="dashboard">

<h1 class="heading">Dashboard</h1>

    <div class="box-container">

        <div class="box">
            <h3>Bem Vindo!</h3>
            <p><?php echo $fetch_profile['name']; ?></p>
            <a href="update_profile.php" class="btn">Atualizar Perfil</a>
        </div><!--box-->

        <div class="box">
            <?php
                $total_pendings = 0;
                $select_pendings = $conn->prepare("SELECT * FROM `ordens_pagamento` WHERE status_pagamento = ?");
                $select_pendings->execute(['pending']);
                while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                    $total_pendings += $fetch_pendings ['preco_total'];
                }
            ?>

            <h3><span>R$</span><?= $total_pendings; ?><span>/-</span></h3>
            <p>Total concluído</p>
            <a href="placed_orders.php" class="btn">Ver Ordens</a>
        </div><!--box-->

        <div class="box">
            <?php
                $total_completes = 0;
                $select_completes = $conn->prepare("SELECT * FROM `ordens_pagamento` WHERE status_pagamento = ?");
                $select_completes->execute(['completed']);
                while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                    $total_completes += $fetch_completes ['preco_total'];
                }
            ?>

            <h3><span>R$</span><?= $total_completes; ?><span>/-</span></h3>
            <p>Total concluído</p>
            <a href="placed_orders.php" class="btn">Ver Ordens</a>
        </div><!--box-->
        

        <div class="box">
            <?php
                $select_orders = $conn->prepare("SELECT * FROM `ordens_pagamento`");
                $select_orders->execute();
                $numbers_of_orders = $select_orders->rowCount();
            ?>
            <h3><?= $numbers_of_orders;?></h3>
            <p>Total Ordens</p>
            <a href="placed_orders.php" class="btn">Ver Ordens</a>
        </div><!--box-->

        <div class="box">
            <?php
                $select_products = $conn->prepare("SELECT * FROM `produtos`");
                $select_products->execute();
                $numbers_of_products = $select_products->rowCount();
            ?>
            <h3><?= $numbers_of_products;?></h3>
            <p>Produtos adicionados</p>
            <a href="products.php" class="btn">Ver Produtos</a>
        </div><!--box-->

        <div class="box">
            <?php
                $select_users = $conn->prepare("SELECT * FROM `usuarios`");
                $select_users->execute();
                $numbers_of_users = $select_users->rowCount();
            ?>
            <h3><?= $numbers_of_users;?></h3>
            <p>Contas de usuario</p>
            <a href="users_accounts.php" class="btn">Ver Contas</a>
        </div><!--box-->

        <div class="box">
            <?php
                $select_admins = $conn->prepare("SELECT * FROM `admins`");
                $select_admins->execute();
                $numbers_of_admins = $select_admins->rowCount();
            ?>
            <h3><?= $numbers_of_admins;?></h3>
            <p>Contas de Admin</p>
            <a href="admin_accounts.php" class="btn">Ver Admins</a>
        </div><!--box-->

        <div class="box">
            <?php
                $select_messages = $conn->prepare("SELECT * FROM `mensagens`");
                $select_messages->execute();
                $numbers_of_messages = $select_messages->rowCount();
            ?>
            <h3><?= $numbers_of_messages;?></h3>
            <p>Novas mensagens</p>
            <a href="messages.php" class="btn">Ver Mensagens</a>
        </div><!--box-->

    </div><!--box-container-->

</section><!--dashboard-->