<?php

$serverPath =  $_SERVER['PHP_SELF'];
// Inicialize a sessão
session_start();
 
// Verifique se o usuário já está logado, em caso afirmativo, redirecione-o para a página de boas-vindas
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 
// Incluir arquivo de configuração
require_once "connection.php";
 
// Defina variáveis e inicialize com valores vazios
$email = $password = "";
$email_err = $password_err = $login_err = "";
 
// Processando dados do formulário quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Verifique se o email de usuário está vazio
    if(empty(trim($_POST["email"]))){
        $email_err = "Por favor, insira o email de usuário.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Verifique se a senha está vazia
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, insira sua senha.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validar credenciais
    if(empty($email_err) && empty($password_err)){
        // Prepare uma declaração selecionada
        $sql = "SELECT id, name, email, password, is_active FROM users WHERE email = :email";
        
        if($stmt = $con->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            
            // Definir parâmetros
            $param_email = trim($_POST["email"]);
            
            // Tente executar a declaração preparada
            if($stmt->execute()){
                // Verifique se o nome de usuário existe, se sim, verifique a senha
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $name = $row["name"];
                        $email = $row["email"];
                        $is_active = $row["is_active"];
                        $hashed_password = $row["password"];
                
                        if(password_verify($password, $hashed_password) && $is_active == 1){
                            // A senha está correta, então inicie uma nova sessão
                            session_start();
                            
                            // Armazene dados em variáveis de sessão
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;                            
                            $_SESSION["email"] = $email;                            
                            
                            // Redirecionar o usuário para a página de boas-vindas
                            header("location: home.php");
                        }
                        else if($is_active == 0){
                            $login_err = "Solicite ativação do seu usuário ao administrador.";
                        } 
                        else{
                            // A senha não é válida, exibe uma mensagem de erro genérica
                            $login_err = "Nome de usuário ou senha inválidos.";
                        }
                    }
                } else{
                    // O nome de usuário não existe, exibe uma mensagem de erro genérica
                    $login_err = "Nome de usuário ou senha inválidos.";
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            // Fechar declaração
            unset($stmt);
        }
    }
    
    // Fechar conexão
    unset($con);
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
        <section id="login">
            <div class="container wrapper">
                <div class="row">
                    <div class="column center">
                        <img src="https://lets-burn.lippfy.com/assets/img/logo.png" class="logo" alt="logo">
                    </div>
                </div>
                <div class="row">
                    <div class="column">
                        <h2>login</h2>
                        <p>Por favor, preencha os</br> campos para fazer o login.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="column">
                        <?php 
                            if(!empty($login_err)){
                                echo '<h6>' . $login_err . '</h6>';
                            }        
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="center">
                                <input type="text" name="email" placeholder="E-MAIL DO USUÁRIO" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                <h6><?php echo $email_err; ?></h6>
                            </div class="center">    
                            <div class="center">
                                <input type="password" name="password" placeholder="SENHA" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                                <h6><?php echo $password_err; ?></h6>
                            </div>
                            <div class="center">
                                <button type="submit">Entrar</button>
                            </div>
                            <p class="info">Não tem uma conta? <a href="register.php">Inscreva-se agora</a>.</p>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="column center">
                        <a href="https://lippfy.com/" target="_blank">
                            <img src="https://lets-burn.lippfy.com/assets/img/assinatura.png" class="assinatura" alt="assinatura">
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>