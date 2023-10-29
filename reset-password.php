<?php
// Inicialize a sessão
session_start();
 
// Verifique se o usuário está logado, caso contrário, redirecione para a página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Incluir arquivo de configuração
require_once "connection.php";
 
// Defina variáveis e inicialize com valores vazios
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processando dados do formulário quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validar nova senha
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Por favor insira a nova senha.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validar e confirmar a senha
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor, confirme a senha.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "A senha não confere.";
        }
    }
        
    // Verifique os erros de entrada antes de atualizar o banco de dados
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare uma declaração de atualização
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        
        if($stmt = $con->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            
            // Definir parâmetros
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Tente executar a declaração preparada
            if($stmt->execute()){
                // Senha atualizada com sucesso. Destrua a sessão e redirecione para a página de login
                session_destroy();
                header("location: login.php");
                exit();
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
        <section id="reset-password">
            <div class="wrapper">
                <div class="row">
                    <div class="column center">
                        <img src="https://ekballo.lippfy.com/assets/img/logo.png" class="logo" alt="logo">
                    </div>
                </div>
                <h2>Redefinir senha</h2>
                <p>Por favor, preencha este formulário para redefinir sua senha.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                    <div class="center">
                        <input type="password" name="new_password" placeholder="NOVA SENHA" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                        <h6 class="invalid-feedback"><?php echo $new_password_err; ?></h6>
                    </div>
                    <div class="center">
                        <input type="password" name="confirm_password" placeholder="CONFIRME SENHA" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                        <h6 class="invalid-feedback"><?php echo $confirm_password_err; ?></h6>
                    </div>
                    <div class="center">
                        <button type="submit">REDEFINIR</button>
                    </div>
                    <div class="center">
                        <p class="info">Deseja cancelar? <a href="home.php">Clique aqui</a>.</p>
                    </div>
                </form>
                <div class="row">
                    <div class="column center">
                        <a href="https://lippfy.com/" target="_blank">
                            <img src="https://ekballo.lippfy.com/assets/img/assinatura.png" class="assinatura" alt="assinatura">
                        </a>
                    </div>
                </div>
            </div>    
        </section>
    </body>
</html>