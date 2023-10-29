<?php
// Inicialize a sessão
session_start();
 
// Verifique se o usuário está logado, se não, redirecione-o para uma página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Incluir arquivo de configuração
require_once "connection.php";

$page = 1;

if(isset($_GET['page']))
    $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);

if(!$page)
    $page = 1;

$size = 10;

$offset = ($page - 1) * $size;

$sql = "select id, name, cpf, email, age, phone, team_name, room_name, room_leader, check_in from customers limit $size offset $offset";

if(isset($_GET['q'])) {
    $q = filter_input(INPUT_GET, "q");

    if($q != "")
        $sql = "select id, name, cpf, email, age, phone, team_name, room_name, room_leader, check_in from customers where id like '%$q%' or lower(name) like lower('%$q%') or cpf like '%$q%' or age like '%$q%' or lower(team_name) like lower('%$q%') or lower(room_name) like lower('%$q%') or lower(room_leader) like lower('%$q%')";
}

$stmt = $con->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_total_pages = "select count(id) from customers;";

$stmt_count = $con->prepare($sql_total_pages);
$stmt_count->execute();
$result_count = $stmt_count->fetchColumn();

$total_pages = ceil($result_count / $size);


//Total customer make check-in
$sql_total_itens_make_check_in = "select count(id) from customers where check_in = 1;";

$stmt_count_make_check_in = $con->prepare($sql_total_itens_make_check_in);
$stmt_count_make_check_in->execute();


//Total customer not make check-in
$sql_total_itens_not_make_check_in = "select count(id) from customers where check_in = 0;";

$stmt_count_not_make_check_in = $con->prepare($sql_total_itens_not_make_check_in);
$stmt_count_not_make_check_in->execute();

?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ekballo - Acamp overflow 2023</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://ekballo.lippfy.com/assets/img/logo.png" />
    <link rel="stylesheet" href="https://ekballo.lippfy.com/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Noto+Sans+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
</head>
<body>
    <section id="home">
        <div class="container">
            <div class="row mb-5">
                <div class="column center">
                    <div class="icon">
                        <i data-feather="user"></i>
                    </div>
                    <h4>Bem-vindo, <b><?php echo ucfirst(htmlspecialchars($_SESSION["name"])); ?></b></h4>
                </div>
                <div class="column center">
                    <img src="https://ekballo.lippfy.com/assets/img/logo.png" class="logo" alt="logo">
                </div>
                <div class="column center">
                    <a href="reset-password.php" title="Alterar senha" class="icon">
                        <i data-feather="edit"></i>
                    </a>
                    <a href="logout.php" title="Finalizar sessão" class="icon">
                        <i data-feather="log-out"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <h4>Ekballo - Acamp overflow 2023</h4>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <h4>Total de Acampantes que <b>fizeram</b> check-in: <b><?php echo $stmt_count_make_check_in->fetchColumn();?></b></h4>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <h4>Total de Acampantes que <b>não fizeram</b> check-in: <b><?php echo $stmt_count_not_make_check_in->fetchColumn();?></b></h4>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <form action="">
                        <input name="q" type="text" placeholder="Pesquise um usuário">
                        <button type="submit">Pesquisar</button>
                    </form>
                </div>
            </div>
            <div class="row" style="overflow-x:auto;">
                <table class="table">
                    <thead class="thead">
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Cpf</th>
                            <th scope="col">Email</th>
                            <th scope="col">Telefone</th>
                            <th scope="col">Idade</th>
                            <th scope="col">Nome do Time</th>
                            <th scope="col">Nome da Sala</th>
                            <th scope="col">Líder da Sala</th>
                            <th scope="col">Fez Check-in</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($stmt->rowCount() >= 1):?>
                            <?php foreach($results as $index => $row):?>
                                <tr>
                                    <td scope="row" class="td-<?php echo intval($index) % 2;?>"><?php echo $row['id'];?></td>
                                    <td class="td-<?php echo intval($index) % 2;?>"><?php echo $row['name'];?></td>
                                    <td class="td-<?php echo intval($index) % 2;?>"><?php echo $row['cpf'];?></td>
                                    <td class="td-<?php echo intval($index) % 2;?>"><?php echo $row['email'];?></td>
                                    <td class="td-<?php echo intval($index) % 2;?>"><?php echo $row['phone'];?></td>
                                    <td class="td-<?php echo intval($index) % 2;?>"><?php echo $row['age'];?></td>
                                    <td class="td-<?php echo intval($index) % 2;?>"><?php echo $row['team_name'];?></td>
                                    <td class="td-<?php echo intval($index) % 2;?>"><?php echo $row['room_name'];?></td>
                                    <td class="td-<?php echo intval($index) % 2;?>"><?php echo $row['room_leader'];?></td>
                                    <?php if ($row['check_in'] == 1) :?>
                                        <td class="td-<?php echo intval($index) % 2;?> yes">
                                            Sim
                                        </td>
                                    <?php else:?>
                                        <td class="td-<?php echo intval($index) % 2;?> no">
                                            Não
                                        </td>
                                    <?php endif;?>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
            <?php if(!$q):?>
                <div class="row mt-5">
                    <div class="column">
                        <a href="?page=1">Primeira Página</a>
                        <?php if($page > 1):?>
                            <a href="?page=<?=$page-1?>" class="icon-arrow"><i data-feather="chevron-left"></i></a>
                        <?php endif;?>
                        <h6 class="m-"><?php echo $page;?></h6>
                        <?php if($page < $total_pages ):?>
                            <a href="?page=<?=$page+1?>" class="icon-arrow"><i data-feather="chevron-right"></i></a>
                        <?php endif;?>
                        <a href="?page=<?=$total_pages?>">Última Página</a>
                    </div>
                </div>
            <?php else:?>
                <div class="row">
                    <div class="column">
                        <a class="button" href="?page=<?=$page-1?>" class="m-">Limpar</a>
                    </div>
                </div>
            <?php endif;?>
            <div class="row">
                <div class="column center">
                    <a href="https://lippfy.com/" target="_blank">
                        <img src="https://ekballo.lippfy.com/assets/img/assinatura.png" class="assinatura" alt="assinatura">
                    </a>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <script>
            feather.replace(); // Inicializa os ícones Feather
        </script>
    </section>
</body>
</html>