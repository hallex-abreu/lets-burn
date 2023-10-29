<?php
// Inicialize a sessão
session_start();

// Incluir arquivo de configuração
require_once "connection.php";

if(isset($_GET['id']))
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if($id){
    $sql = "select id, name, cpf, email, age, phone, team_name, room_name, room_leader, check_in from customers where id = $id";

    $stmt = $con->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ekballo - Acamp overflow 2023</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://ekballo.lippfy.com/assets/img/logo.png" />
    <!-- <link rel="stylesheet" href="https://ekballo.lippfy.com/ekballoekballo/assets/css/style.css"> -->
    <link rel="stylesheet" href="https://ekballo.lippfy.com/assets/css/style.css">
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
                    <img src="https://ekballo.lippfy.com/assets/img/logo.png" class="logo" alt="logo">
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <?php if($id && $stmt->rowCount() >= 1):?>
                        <?php foreach($results as $row):?>
                            <p>Olá, <u><?php echo $row['name'];?></u></br>Bem-vindo(a) ao</br>Check-in Ekballo</p>
                            <div class="center">
                                <input name="<?php echo $row['room_name'];?>" type="text" value="<?php echo $row['room_name'];?>" disabled>
                            </div>
                            <div class="center">
                                <input name="<?php echo $row['team_name'];?>" type="text" value="<?php echo $row['team_name'];?>" disabled>
                            </div>
                            <div class="center">
                                <input name="<?php echo $row['room_leader'];?>" type="text" value="<?php echo $row['room_leader'];?>" disabled>
                            </div>
                            <?php 
                                $check_in = $row['check_in'];
                                if($check_in == "0"):
                            ?>
                                <div class="center">
                                    <a href="finished.php?id=<?php echo $row['id'];?>" class="search">Fazer check-in</a>
                                </div>
                            <?php else:?>
                                <p>Chek-in já realizado</p>
                            <?php endif;?>
                            <div class="center">
                                <a href="search.php" class="search">Voltar</a>
                            </div>
                        <?php endforeach;?>
                    <?php else:?>
                        <p>Usuário não encontrado</p>
                        <div class="center">
                            <a href="search.php" class="search">Pesquisar novamente</a>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="row">
                <div class="column center mt-80">
                    <a href="https://lippfy.com/" target="_blank">
                        <img src="https://ekballo.lippfy.com/assets/img/assinatura.png" class="assinatura" alt="assinatura">
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