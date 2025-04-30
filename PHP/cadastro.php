<?php
session_start();
include 'conexao.php';






if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_usuario'];
    $CPF = $_POST['CPF_usuario'];
    $CNPJ = $_POST['CNPJ_usuario'];
    $dta = $_POST['dta_nascimento'];
    $email = $_POST['email_usuario'];
    $email_conf = $_POST['conf_email'];
    $senha = $_POST['senha_cad'];
    $senha_conf = $_POST['conf_senha'];

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $erro=" ";

    // Validações
    if ($senha != $senha_conf) 
    {
        $erro = "Erro. As senhas estão diferentes.";
    } 
    elseif (strlen($CPF) == 0 || strlen($CPF) < 11) 
    {
        $erro = "Número de carácteres insuficiente no campo CPF/CNPJ.";
    }
     elseif ($email != $email_conf)
    {
        $erro = "Erro. Os emails não coincidem.";
    } 

    else
    {
        // Inserir no banco de dados
        $comando = $conn->prepare("INSERT INTO tb_cadastro (nome_cadastro, CPF_cadastro, CNPJ_cadastro, dta_nasc_cadastro, email_cadastro, senha_cadastro) 
                                   VALUES (?, ?, ?, ?, ?, ?)");

        if ($comando === false) 
        {
            $erro = "Erro ao preparar a query: " . $conn->error;
        } 
        else
        {
            $comando->bind_param("ssssss", $nome, $CPF, $CNPJ, $dta, $email, $senha_hash);

            if ($comando->execute()) 
            {
                
                
                
                echo"
                <!DOCTYPE html>
                      <html lang='pt-BR'>
                      <head>
                          <meta charset='UTF-8'>
                          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                          <link rel='stylesheet' type='text/css' href='../CSS/css_cadastro_login.css'>
                          <title>Cadastro realizado com sucesso!</title>
                      </head>
                      <body>

                      <div class='main'>
                          <div class='caixa_texto'>
                              <h1>Cadastro realizado com sucesso</h1><br>
                              <a href='../login.html'>Fazer login</a>
                          </div>
                       </div>
                      </body>
                      </html>";
                // Redireciona para uma página de sucesso
                exit();
            } 

            elseif($comando = 1 ){

                $erro="CPF já em uso";

            }


            
            else
            {
                $erro = "Erro ao cadastrar: " ;
            }
         

            $conn->close();
        }

        
    }

    // Se houver erro, redireciona de volta para o formulário com a mensagem de erro
    if (!empty($erro)) {
      
        header("Location: ../cadastro.html?erro=" . urlencode($erro));
     
      
        exit();
    }
}

$conn->close();
?>