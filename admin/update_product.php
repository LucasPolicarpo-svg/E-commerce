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
        <title>Atualizar Produtos</title>
        <link rel="stylesheet" href="../css/style_admin.css">
    </head>
    <body>

        <?php include '../componentes/admin_header.php' ?>
        

    <script src="../js/admin_script.js"></script>
        <script src="https://kit.fontawesome.com/cf6b52a942.js" crossorigin="anonymous"></script>

    </body>
</html>