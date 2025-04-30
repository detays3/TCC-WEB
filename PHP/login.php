<?php
// Inicia o buffer de saída para evitar problemas com headers
ob_start();

session_start();
header('Content-Type: text/html; charset=utf-8');

require_once 'conexao.php';
require_once 'validacao.php';
require_once 'mostrar_erros.php';

// Função auxiliar para redirecionamento
function redirect($url, $error = null) {
    if ($error) {
        $_SESSION['login_errors'] = (array)$error;
    }
    header("Location: $url");
    exit();
}

// Verifica se é uma requisição POST válida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_POST['botao'], $_POST['nome_login'], $_POST['log_pessoa'], $_POST['senha_log']) && 
    $_POST['botao'] === 'logar') 
{
    try {
        // Limpa e valida os dados de entrada
        $nome = trim($_POST['nome_login']);
        $identificador = preg_replace('/[^0-9]/', '', $_POST['log_pessoa']);
        $senha = $_POST['senha_log'];

        if (!isset($conn)) {
            throw new Exception("Erro na conexão com o banco de dados");
        }

        $validador = new ValidarLogin($nome, $identificador, $senha, $conn);
        $erros = $validador->validarDados();

        if (!empty($erros)) {
            redirect('login.php', $erros);
        }

        $sql = "SELECT id_cadastro, nome_cadastro, senha_cadastro FROM tb_cadastro WHERE ";
        $sql .= (strlen($identificador) === 11 ? "CPF_cadastro = ?" : "CNPJ_cadastro = ?");

        $comando = $conn->prepare($sql);
        $comando->bind_param("s", $identificador);
        $comando->execute();
        $result = $comando->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            
            if (password_verify($senha, $usuario['senha_cadastro'])) {
                $_SESSION['id_usuario'] = $usuario['id_cadastro'];
                $_SESSION['nome_usuario'] = $usuario['nome_cadastro'];
                $_SESSION['tipo_usuario'] = (strlen($identificador) == 11) ? 'CPF' : 'CNPJ';
                
                $_SESSION['login_success'] = 'Login realizado com sucesso!';
                
                // AJUSTE IMPORTANTE: Verifique o caminho correto para sua estrutura
                redirect('../pag_principal.html');
            } else {
                redirect('login.php', 'Senha incorreta.');
            }
        } else {
            redirect('login.php', 'Usuário não encontrado.');
        }
        
    } catch (Exception $e) {
        error_log('Erro no login: ' . $e->getMessage());
        redirect('login.php', 'Ocorreu um erro durante o login. Tente novamente.');
    }
} else {
    redirect('login.php', 'Requisição inválida.');
}