<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['botao']) && $_POST['botao'] == 'logar')
     {
        if (isset($_POST['nome_login']) && isset($_POST['log_pessoa']) && isset($_POST['senha_log'])) {
            $nome = $_POST['nome_login'];
            $tipo = $_POST['log_pessoa'];
            $senha = $_POST['senha_log'];

            if (strlen($tipo) == 0)
            {
                echo "Preencha seu CPF ou CNPJ!";
            } 
            else if (strlen($nome) == 0) 
            {
                echo "Preencha seu nome!";
            }
             
            else if (strlen($senha) == 0) 
            {
                echo "Preencha sua senha!";
            } 


            else 
            {
                $sql = "SELECT id_cadastro, nome_cadastro, CPF_cadastro, senha_cadastro FROM tb_cadastro ";

                if (strlen($tipo) == 11) 
                {
                    // Se for CPF (11 caracteres)
                    $sql .= " WHERE CPF_cadastro = ?";
                } else if (strlen($tipo) == 14)
                 {
                    // Se for CNPJ (14 caracteres)
                    $sql .= " WHERE CNPJ_cadastro = ?";
                } 
                else 
                {
                    echo "CPF ou CNPJ inválido!";
                    exit;
                }

                $comando = $conn->prepare($sql);
                $comando->bind_param("s", $tipo);
                $comando->execute();
                $result = $comando->get_result();

                if ($result->num_rows > 0) {
                    // Usuário encontrado, verifica a senha
                    $usuario = $result->fetch_assoc();

                    if (password_verify($senha, $usuario['senha_cadastro'])) {
                        // Senha correta, salva os dados na sessão
                        $_SESSION['id_usuario'] = $usuario['id_cadastro'];
                        $_SESSION['nome_usuario'] = $usuario['nome_cadastro'];
                        $_SESSION['tipo_usuario'] = (strlen($tipo) == 11) ? 'CPF' : 'CNPJ';

                        echo "<meta HTTP-EQUIV='refresh' CONTENT='5;URL=../pag_principal.html'>";
                        exit();
                    }
                    else 
                    {
                        echo "Senha incorreta!";
                        exit;
                    }
                } 
                else
                {
                    echo "Usuário não encontrado!";
                    exit;
                }
            }
        }
    }
}
?>