<?php
// Inicialize a sessão
session_start();

// Incluir arquivo de configuração
require_once "connection.php";

if(isset($_GET['q']))
    $q = filter_input(INPUT_GET, "q");

if($q && $q != ""){
    $sql = "select id, name from customers where lower(name) like lower('%$q%')";

    $stmt = $con->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <div class="column">
                    <h2>CHECK-IN</br>ACAMPAMENTO</h2>
                </div>
            </div>
                <div class="row">
                    <div class="column">
                        <?php if($q && $stmt->rowCount() >= 1):?>
                            <p>Resultados para</br><u><?php echo $q;?></u></p>
                            <?php foreach($results as $row):?>
                                <div class="customer">
                                    <input name="<?php echo $row['name'];?>" type="text" value="<?php echo $row['name'];?>" disabled>
                                    <a href="check-in.php?id=<?php echo $row['id'];?>" class="see-more">+</a>
                                </div>
                            <?php endforeach;?>
                            <div class="center">
                                <a href="search.php" class="search">Pesquisar novamente</a>
                            </div>
                        <?php elseif($q && $stmt->rowCount() == 0): ?>
                            <p>Usuário não encontrado</p>
                            <div class="center">
                                <a href="search.php" class="search">Pesquisar novamente</a>
                            </div>
                        <?php else: ?>
                            <form action="">
                                <p>Pesquise seu nome</p>
                                <div class="center">
                                    <input name="q" type="text" placeholder="NOME DO ACAMPANTE">
                                </div class="center">    
                                <div class="center">
                                    <button type="submit">Buscar</button>
                                </div>
                            </form>
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
    </body>
</html>