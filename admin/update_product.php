<?php

    include '../componentes/connect.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location: admin_login.php');
        exit; // Encerra o código após o redirecionamento
    }
    if(isset($_POST['update'])){
        $pid = $_POST['pid'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $details = $_POST['details'];
    
        // Verifica se todas as variáveis estão definidas
        if(isset($pid, $name, $price, $details)){
            // Valida e formata o campo de preço como um número
            $price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT);
    
            // Preparar e executar a consulta SQL
            $update_product = $conn->prepare("UPDATE `produtos` SET name = ?, details = ?, price = ? WHERE id = ?");
            $update_product->execute([$name, $details, $price, $pid]);

        } else {
            // Lida com campos ausentes
            echo "Campos obrigatórios ausentes.";
        }

        function updateImage($conn, $pid, $oldImageKey, $newImageKey, $imageFolder){
            $message = '';

            if (!empty($_FILES[$newImageKey]['name'])) {
                $imageSize = $_FILES[$newImageKey]['size'];
                $imageTmpName = $_FILES[$newImageKey]['tmp_name'];

                if ($imageSize > 200000) {
                    $message = 'A imagem é muito grande!';
                } else {
                    $newImageName = basename($_FILES[$newImageKey]['name']);
                    $updateImage = $conn->prepare("UPDATE `produtos` SET $newImageKey = ? WHERE id = ?");
                    $updateImage->execute([$newImageName, $pid]);

                    move_uploaded_file($imageTmpName, $imageFolder);
                    unlink('../uploaded_img/' . $_POST[$oldImageKey]);

                    $message = 'Imagem atualizada!';
                }
            }

            return $message;
        }

        if (isset($_POST['update'])) {
            $pid = $_POST['pid'];
            $messages = [];

            // Atualize cada imagem separadamente
            $messages[] = updateImage($conn, $pid, 'old_image_01', 'image_01', '../uploaded_img/' . $_FILES['image_01']['name']);
            $messages[] = updateImage($conn, $pid, 'old_image_02', 'image_02', '../uploaded_img/' . $_FILES['image_02']['name']);
            $messages[] = updateImage($conn, $pid, 'old_image_03', 'image_03', '../uploaded_img/' . $_FILES['image_03']['name']);
        }
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
        
        <section class="update-product">

            <h1 class="heading">Atualização de Produtos</h1>

            <?php
                $update_id = $_GET['update'];
                $show_products = $conn->prepare("SELECT * FROM `produtos` WHERE id = ?");
                $show_products->execute([$update_id]);
                if ($show_products->rowCount() > 0) {
                    while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
                <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
                <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_03']; ?>">

                <div class="image-container">
                    <div class="main-image">
                        <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                    </div>
                    <div class="sub-images">
                        <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                        <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
                        <img src="../uploaded_img/<?= $fetch_products['image_03']; ?>" alt="">
                    </div>
                </div>
                <span>Atualize o nome</span>
                <input type="text" class="box" placeholder="Digite o nome do produto" name="name" maxlength="100" required value="<?= $fetch_products['name']; ?>">
                <span>Atualize o preço</span>
                <input type="number" min="0" max="9999999999" class="box" placeholder="Digite o preço do produto" name="price" onkeypress="if(this.value.lenght == 10) return false;" required value="<?= $fetch_products['price']; ?>">
                <span>Atualize a descrição</span>
                <textarea name="details" class="box" placeholder="Digite os detalhes do produto" required maxlength="500" cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
                <span>Atualize a imagem 01</span>
                <input type="file" name="image_01" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
                <span>Atualize a imagem 02</span>
                <input type="file" name="image_02" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
                <span>Atualize a imagem 03</span>
                <input type="file" name="image_03" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
                <div class="flex-btn">
                    <input type="submit" value="Atualizar" class="btn" name="update">
                    <a href="products.php" class="option-btn">Voltar</a>
                </div>
            </form>
            <?php
                    }
                } else {
                echo '<p class="empty">Nenhum produto adicionado ainda!</p>';
                }
            ?>
        </section>

    <script src="../js/admin_script.js"></script>
        <script src="https://kit.fontawesome.com/cf6b52a942.js" crossorigin="anonymous"></script>

    </body>
</html>