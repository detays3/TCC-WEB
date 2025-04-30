<?php
session_start();
include 'conexao.php';
require 'selects.php';
include 'validacao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber e sanitizar os dados
    $nome = filter_input(INPUT_POST, 'nome_usuario', FILTER_SANITIZE_STRING);
    $CPF = filter_input(INPUT_POST, 'CPF_usuario', FILTER_SANITIZE_STRING);
    $CNPJ = filter_input(INPUT_POST, 'CNPJ_usuario', FILTER_SANITIZE_STRING);
    $dta_nasc = filter_input(INPUT_POST, 'dta_nascimento', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email_usuario', FILTER_SANITIZE_EMAIL);
    $email_conf = filter_input(INPUT_POST, 'conf_email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha_cad'];  // Não sanitizar a senha para não alterar caracteres
    $senha_conf = $_POST['conf_senha'];

    // Criar instância do validador
    $validador = new ValidarCadastro($nome, $CPF, $CNPJ, $dta_nasc, $email, $email_conf, $senha, $senha_conf, $conn);

    // Realizar validação e capturar os erros
    $erros = $validador->validacao();

    if (empty($erros)) {
        // Se validação OK, preparar dados para inserção
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $dta_nasc_mysql = date('Y-m-d', strtotime($dta_nasc));

        try {
            $comando = inserir_cadastro($conn, $nome, $CPF, $CNPJ, $dta_nasc_mysql, $email, $senha_hash);

            if($comando && $comando->execute()) {
                // Limpar dados sensíveis
                unset($senha, $senha_conf, $senha_hash);
                
                // Cadastro bem-sucedido (mantendo seu HTML original)
                echo "<!DOCTYPE html>
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
                exit();
            } else {
                throw new Exception("Erro na execução do SQL");
            }
        } catch (Exception $e) {
            error_log("Erro no cadastro: " . $e->getMessage());
            $erro = "Erro ao processar seu cadastro. Por favor, tente novamente.";
        }
    } else {
        // Caso existam erros de validação
        $_SESSION['erros_cadastro'] = $erros;  // Armazena erros na sessão
        $erro = "Dados inválidos. Verifique os campos.";
    }

    // Se chegou aqui, houve algum erro
    if (!headers_sent()) {
        header("Location: ../cadastro.html?erro=" . urlencode($erro));
    }
    exit();
}

$conn->close();
?>