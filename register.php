<?php
// Incluir arquivo de configuração
require_once "connection.php";
 
// Defina variáveis e inicialize com valores vazios
$email = $name = $password = $confirm_password = "";
$email_err = $name_err = $password_err = $confirm_password_err = "";
 
// Processando dados do formulário quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validar nome de usuário
    if(empty(trim($_POST["name"]))){
        $name_err = "Por favor coloque um nome de usuário.";
    } elseif(preg_match('/\d/', trim($_POST["name"]))){
        $name_err = "O nome de usuário pode conter apenas letras, números e sublinhados.";
    } else{
        // Prepare uma declaração selecionada
        $sql = "SELECT id FROM users WHERE name = :name";
        
        if($stmt = $con->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            
            // Definir parâmetros
            $param_name = trim($_POST["name"]);
            
            // Tente executar a declaração preparada
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $name_err = "Este nome de usuário já está em uso.";
                } else{
                    $name = trim($_POST["name"]);
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            // Fechar declaração
            unset($stmt);
        }
    }

     // Validar email de usuário
     if(empty(trim($_POST["email"]))){
        $email_err = "Por favor coloque um email de usuário.";
    } else{
        // Prepare uma declaração selecionada
        $sql = "SELECT id FROM users WHERE email = :email";
        
        if($stmt = $con->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            
            // Definir parâmetros
            $param_email = trim($_POST["email"]);
            
            // Tente executar a declaração preparada
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $email_err = "Este email de usuário já está em uso.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            // Fechar declaração
            unset($stmt);
        }
    }
    
    // Validar senha
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor insira uma senha.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validar e confirmar a senha
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor, confirme a senha.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "A senha não confere.";
        }
    }
    
    // Verifique os erros de entrada antes de inserir no banco de dados
    if(empty($name_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare uma declaração de inserção
        $sql = "INSERT INTO users (name, email, password, is_active) VALUES (:name, :email, :password, false)";
         
        if($stmt = $con->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            // Definir parâmetros
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Tente executar a declaração preparada
            if($stmt->execute()){
                // Redirecionar para a página de login
                header("location: login.php");
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
        <section id="register">
            <div class="wrapper">
                <div class="row">
                    <div class="column center">
                        <img src="https://lets-burn.lippfy.com/assets/img/logo.png" class="logo" alt="logo">
                    </div>
                </div>
                <h2>Cadastro</h2>
                <p>Por favor, preencha este formulário para criar uma conta.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="center">
                        <input type="text" name="name" placeholder="NOME DO USUÁRIO" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                        <h6 class="invalid-feedback"><?php echo $name_err; ?></h6>
                    </div>   
                    <div class="center">
                        <input type="text" name="email" placeholder="EMAIL DO USUÁRIO" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                        <h6 class="invalid-feedback"><?php echo $email_err; ?></h6>
                    </div>    
                    <div class="center">
                        <input type="password" name="password" placeholder="SENHA" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                        <h6 class="invalid-feedback"><?php echo $password_err; ?></h6>
                    </div>
                    <div class="center">
                        <input type="password" name="confirm_password" placeholder="CONFIRME SENHA" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                        <h6 class="invalid-feedback"><?php echo $confirm_password_err; ?></h6>
                    </div>
                    <div class="center">
                        <button type="submit">CRIAR CONTA</button>
                    </div>
                    <p class="info">Já tem uma conta? <a href="login.php">Entre aqui</a>.</p>
                </form>
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