<?php
    require_once 'configDB.php'; #Para usar Banco de Dados
    $msg = "";
    if(isset($_GET['email']) && isset($_GET['token'])){
        $email = $_GET['email'];
        $token = $_GET['token'];
        
        $sql = $conexão->prepare("SELECT * FROM usuario WHERE email=? AND token=? AND tokenExpirado>now()");
        $sql->bind_param("ss", $email, $token);
        $sql->execute();
        $resultado = $sql->get_result();
        if($resultado->num_rows > 0){
            if(isset($_POST['gerar'])){#Botão cadastrar senha
                $novaSenha = sha1($_POST['senha']);
                $confirmarSenha = sha1($_POST['csenha']);
                if($novaSenha == $confirmarSenha){
                    $sql = $conexão->prepare("UPDATE usuario SET token='', senha=? WHERE email=?");
                    $sql->bind_param('ss', $novaSenha, $email);
                    $sql->execute();
                    $msg = "<em class='text-success'>Senha Alterada com sucesso.</em>";
                    
                }else{
                    $msg = "<em class='text-danger'>Senhas não conferem.</em>";
                }
            }
        }else{#email ou token inválidos
            header("location:index.php");
            exit();
        }
    }else{#digitou a pagina sem email nem token
        header("location:index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Gerar uma nova senha</title>
  </head>
  <body>
      <main class="container">
          <section class="row justify-content-center">
              <div class="col-lg-5 mt-5">
                  <h3 class="text-center bg-dark text-light p-2 rounded">
                      Gere uma nova senha
                  </h3>
                  <h4 class="bf-info text-center ">
                      <?= @$msg ?>
                  </h4>
                  <form method="post"> <!--- envio para propria pagina-->
                      <div class="form-group">
                          <label for="senha">Nova Senha</label>  
                          <input type="password" name="senha" id="senha" class="form-control" 
                                 placeholder="Nova Senha" required>
                      </div>
                      <div class="form-group">
                          <label for="csenha">Confirmar Senha</label>  
                          <input type="password" name="csenha" id="csenha" class="form-control" 
                                 placeholder="Confirma Senha" required>
                      </div>
                      <div class="form-group">
                          <input type="submit" value="Cadastrar Senha" class="btn btn-block btn-primary" name="gerar">
                      </div>              
                  </form>
              </div>
          </section>
      </main>

    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>


