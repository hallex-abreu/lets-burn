<?php
// Inicialize a sessão
session_start();

// Incluir arquivo de configuração
require_once "connection.php";

if(isset($_GET['id']))
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if($id){
    $sql = "update customers set check_in = true where id = $id";

    $stmt = $con->prepare($sql);
    $stmt->execute();
}
    
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lets Burn - Acampamento Up Eusébio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://lets-burn.lippfy.com/assets/img/logo.png" />
    <link rel="stylesheet" href="https://lets-burn.lippfy.com/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Noto+Sans+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
</head>
<body>
    <section id="search">
        <div class="wrapper">
            <div class="row">
                <div class="column center">
                    <img src="https://lets-burn.lippfy.com/assets/img/logo.png" class="logo" alt="logo">
                </div>
            </div>
            <div class="row">
                <div class="column center">
                    <?php if($stmt->rowCount() > 0):?>
                        <i data-feather="check-circle"></i>
                        <h2>CHECK-IN</br>REALIZADO</br>COM</br>SUCESSO</h2>
                        <div class="center">
                            <a href="https://www.instagram.com/lippfytech" target="_blank" class="search"><i data-feather="instagram"></i>siga-nos: @lippfy</a>
                        </div>
                    <?php else:?>
                        <h2>ERRO AO</br>TENTAR</br>FAZER</br>CHECK-IN</h2>
                        <div class="center">
                            <a href="check-in.php?id=<?php echo $id;?>" class="search">Voltar</a>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="row">
                <div class="column center mt-80">
                    <a href="https://lippfy.com/" target="_blank">
                        <img src="https://lets-burn.lippfy.com/assets/img/assinatura.png" class="assinatura" alt="assinatura">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace(); // Inicializa os ícones Feather
    </script>
</body>
</html>