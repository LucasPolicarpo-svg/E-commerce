<?php
include '../componentes/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location: admin_login.php');
    exit; // Termina o script após redirecionar
}

if (isset($_POST['add_product'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    // Verifique se o nome do produto já existe
    $select_products = $conn->prepare("SELECT * FROM `produtos` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $messages[] = 'O nome do produto já existe!';
    } else {
        $image_01_folder = '../uploaded_img/' . basename($_FILES['image_01']['name']);
        $image_02_folder = '../uploaded_img/' . basename($_FILES['image_02']['name']);
        $image_03_folder = '../uploaded_img/' . basename($_FILES['image_03']['name']);

        $image_01_size = $_FILES['image_01']['size'];
        $image_02_size = $_FILES['image_02']['size'];
        $image_03_size = $_FILES['image_03']['size'];

        // Verifique o tamanho das imagens
        if ($image_01_size > 2000000 || $image_02_size > 2000000 || $image_03_size > 2000000) {
            $messages[] = 'A imagem é muito grande!';
        } else {
            // Faça o upload das imagens
            move_uploaded_file($_FILES['image_01']['tmp_name'], $image_01_folder);
            move_uploaded_file($_FILES['image_02']['tmp_name'], $image_02_folder);
            move_uploaded_file($_FILES['image_03']['tmp_name'], $image_03_folder);

            // Insira o produto no banco de dados usando prepared statement
            $insert_product = $conn->prepare("INSERT INTO `produtos` (name, details, price, image_01, image_02, image_03) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_product->execute([$name, $details, $price, $image_01_folder, $image_02_folder, $image_03_folder]);

            $messages[] = 'Novo produto adicionado!';
        }
    }
}

// Exiba as mensagens, se houverem
if (isset($messages)) {
    foreach ($messages as $message) {
        echo $message . '<br>';
    }
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    try {
        $conn->beginTransaction();

        // Seleciona as informações do produto para obter os nomes das imagens
        $delete_product_image = $conn->prepare("SELECT image_01, image_02, image_03 FROM `produtos` WHERE id = ?");
        $delete_product_image->execute([$delete_id]);
        $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);

        // Exclui as imagens do diretório
        unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
        unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
        unlink('../uploaded_img/'.$fetch_delete_image['image_03']);

        // Exclui o produto
        $delete_product = $conn->prepare("DELETE FROM `produtos` WHERE id = ?");
        $delete_product->execute([$delete_id]);

        // Exclui o produto do carrinho
        $delete_cart = $conn->prepare("DELETE FROM `carrinho` WHERE pid = ?");
        $delete_cart->execute([$delete_id]);

        // Exclui o produto da lista de desejos
        $delete_whishlist = $conn->prepare("DELETE FROM `lista_de_desejos` WHERE pid = ?");
        $delete_whishlist->execute([$delete_id]);

        $conn->commit(); // Confirma todas as operações
        header('location: products.php');
    } catch (PDOException $e) {
        $conn->rollBack(); // Reverte as operações em caso de erro
        echo "Erro: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Produtos</title>
        <link rel="stylesheet" href="../css/style_admin.css">
    </head>
    <body>

        <?php include '../componentes/admin_header.php' ?>

        <!--Seção que adiciona os produtos-->
        <section class="add-products">
            <h1 class="heading">Adicionar Produtos</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="flex">
                    <div class="inputBox">
                        <span>Nome Produto (obrigatório)</span>
                        <input type="text" class="box" placeholder="Digite o nome do produto" name="name" maxlength="100" required>
                    </div>
                    <div class="inputBox">
                        <span>Preço Produto (obrigatório)</span>
                        <input type="number" min="0" max="9999999999" class="box" placeholder="Digite o preço do produto" name="price" onkeypress="if(this.value.lenght == 10) return false;" required>
                    </div>
                    <div class="inputBox">
                        <span>Imagem 01 (obrigatório)</span>
                        <input type="file" name="image_01" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                    </div>
                    <div class="inputBox">
                        <span>Imagem 02 (obrigatório)</span>
                        <input type="file" name="image_02" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                    </div>
                    <div class="inputBox">
                        <span>Imagem 03 (obrigatório)</span>
                        <input type="file" name="image_03" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                    </div>
                    <div class="inputBox">
                        <span>Detalhes Produtos</span>
                        <textarea name="details" class="box" placeholder="Digite os detalhes do produto" required maxlength="500" cols="30" rows="10"></textarea>
                        <input type="submit" value="Adicionar Produto" name="add_product" class="btn">
                    </div>
                </div>
            </form>
        </section>

        <!--Seção que visualiza os produtos-->
        <section class="show-products">
            <h1 class="heading">Produtos adicionados</h1>
            <div class="box-container">
            <?php
            $show_products = $conn->prepare("SELECT * FROM `produtos`");
            $show_products->execute();
            if ($show_products->rowCount() > 0) {
                 while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="box">
                <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                <div class="name"><?= $fetch_products['name']; ?></div>
                <div class="price">R$<?= $fetch_products['price']; ?></div>
                <div class="details"><?= $fetch_products['details']; ?></div>
                <div class="flex-btn">
                    <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Atualizar</a>
                    <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Deletar esse produto?');">Deletar</a>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">Nenhum produto adicionado ainda!</p>';
            }
            ?>
        </div>
    </section>



    </body>

</html>