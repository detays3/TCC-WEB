<?php
declare(strict_types=1);



function inserir_cadastro(
    object $conn, 
    string $nome, 
    string $CPF, 
    string $CNPJ, 
    string $dta, 
    string $email, 
    string $senha
){
    // Validar dados antes de inserir
    if (empty($nome) || (empty($CPF) && empty($CNPJ)) || empty($dta) || empty($email) || empty($senha)) {
        throw new InvalidArgumentException("Todos os campos obrigatórios devem ser preenchidos");
    }
    
    try {
        // Preparar a query
        $comando = $conn->prepare("INSERT INTO tb_cadastro 
                                 (nome_cadastro, CPF_cadastro, CNPJ_cadastro, 
                                  dta_nasc_cadastro, email_cadastro, senha_cadastro) 
                                 VALUES (?, ?, ?, ?, ?, ?)");
        
        // Vincular parâmetros (senha em texto puro)
        $comando->bind_param(
            "ssssss", 
            $nome, 
            $CPF, 
            $CNPJ, 
            $dta, 
            $email, 
            $senha // Senha sem hash
        );
        
        // Executar e retornar resultado
        return $comando->execute();
        
    } catch (mysqli_sql_exception $e) {
        error_log("Erro ao inserir cadastro: " . $e->getMessage());
        throw new RuntimeException("Erro ao processar cadastro");
    }
}
function login_usuario(object $conn, string $identificador, string $senha): ?array 
{
    // Verifica se o identificador é CPF ou CNPJ
    $campo = (strlen($identificador)) === 11 ? "CPF_cadastro" : "CNPJ_cadastro";
    
    try {
        // Prepara a query (usando PDO)
        $query = "SELECT nome_cadastro, senha_cadastro 
                 FROM tb_cadastro 
                 WHERE $campo = :identificador";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":identificador", $identificador);
        $stmt->execute();

        $usuario = $stmt->fetch();

        // Se não encontrou o usuário
        if (!$usuario) {
            return null;
        }

        // Verifica a senha (em texto puro - NÃO RECOMENDADO)
        if ($senha === $usuario['senha_cadastro']) {
            return $usuario; // Retorna os dados do usuário
        }

        return null; // Senha incorreta

    } catch (PDOException $e) {
        error_log("Erro no login: " . $e->getMessage());
        throw new RuntimeException("Erro ao processar login");
    }
}
?>