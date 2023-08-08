<?php

include '../componentes/connect.php';

session_start();

//  var_dump($_SESSION['admin_id']);


$messages = array();

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $password = $_POST['password']; 
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
    $select_admin->execute([$name]);


    if($select_admin->rowCount() > 0){
        $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

        if(sha1($password) === $fetch_admin['password']){
            $_SESSION['admin_id'] = $fetch_admin['id'];
            header('location:dashboard.php');
            exit();
        } else {
            $messages[] = 'Senha ou Usuário incorreto!';
        }
    } else {
        $messages[] = 'Usuário não encontrado!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../css/style_admin.css">
    </head>
    <body>

        <?php
        foreach($messages as $msg){
            echo '<div class="message">
                    <span>'.$msg.'</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove()"></i>
                </div>';
        }
        ?>

        <section class="form-container">
            <form action="" method="POST">
                <img src="../imagens/Logo Horizontal Preta.png" alt="">
                <h3>ENTRAR</h3>
                <p>Usuario padrão: admin & senha: admin</p>
                <input type="text" name="name" class="box" placeholder="Entre com seu usuario" maxlength="30" required oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="password" class="box" placeholder="Entre com sua senha" maxlength="30" required oninput="this.value = this.value.replace(/\s/g, '')">

                <input type="submit" value="Entrar" name="submit" class="btn">

                
            </form>
        </section><!--form-container-->
    </body>
</html>