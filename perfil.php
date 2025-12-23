<?php
    session_start();
    if(isset($_SESSION['idUser']) && $_SESSION['idUser'] === ""){
        header('Location: index.php');
    }
    include 'Class/Perfil.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="CSS/perfil.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="imgs/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logoContainer">
            <img src="imgs/Logo_menor.png" alt="Logo">
            <p>Stock Hub</p>
        </div>
        <div class="containerLogoutButton">
            <input type="button" value="Sair" onclick="logout()">
        </div>
    </header>
    <main>
        <section class="leftContainer">
            <ul>
                <li class="pefilLine">
                    <img src="<?php echo $Perfil->imgURL?>" alt="Foto de perfil">
                    <p><?php echo $Perfil->userInfos[0]['usuario']?></>
                </li>
                <li class="optionsLine">
                    <a href="privacidade.php">
                        <h2>Privacidade</h2>
                        <p>Altere informações da conta</p>
                    </a>
                </li>
                <li class="optionsLine">
                    <a href="addProduto.php">
                        <h2>Adicionar produtos</h2>
                        <p>Clique para adicionar produtos ao estoque</p>
                    </a>
                </li>
            </ul>
        </section>
        <section class="rightContainer">
            <h1>Produtos adicionados:</h1>
            <div class="containerProducts">
                <?php
                    $Perfil->showProductCards();
                ?>
            </div>
        </section>  
    </main>
    <footer>
        <div class="containerCopyRight">
            <p><span>&copy;Stock Hub</span></p>
        </div>
    </footer>
    <script src="Scripts/perfil.js"></script>
</body>
</html>