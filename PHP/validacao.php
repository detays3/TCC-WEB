<?php
declare(strict_types=1);
require_once 'mostrar_erros.php';

class ValidarCadastro 
{
    private $nome;
    private$CPF;
    private $CNPJ;
    private $dta_nasc;
    private $email;
    private $senha;
    private $conf_email;
    private $conf_senha;
    private $conn;

    public function __construct( $nome,$CPF,$CNPJ,$dta_nasc,$email,$conf_email,$senha, $conf_senha,$conn )
    {
      
        $this->nome = trim($nome);
        $this->CPF = preg_replace('/[^0-9]/', '', $CPF);
        $this->CNPJ = preg_replace('/[^0-9]/', '', $CNPJ);
        $this->dta_nasc = $dta_nasc;
        $this->email = trim($email);
        $this->conf_email = trim($conf_email);
        $this->senha = $senha;
        $this->conf_senha = $conf_senha;
        $this->conn = $conn;
    }

    public function validacao()
    {
        $errors = [];

        // Validação do nome
        if (empty($this->nome)) {
            $errors['nome'] = 'Nome é obrigatório';
        } elseif (strlen($this->nome) < 3) {
            $errors['nome'] = 'Nome deve ter pelo menos 3 caracteres';
        }

        // Validação do CPF/CNPJ
        if (empty($this->CPF) && empty($this->CNPJ)) {
            $errors['documento'] = 'CPF ou CNPJ é obrigatório';
        } else {
            if (!empty($this->CPF) && !$this->validarCPF($this->CPF)) {
                $errors['CPF'] = 'CPF inválido';
            }
            if (!empty($this->CNPJ) && !$this->validarCNPJ($this->CNPJ)) {
                $errors['CNPJ'] = 'CNPJ inválido';
            }
        }

        // Validação da data de nascimento
        if (empty($this->dta_nasc)) {
            $errors['dta_nasc'] = 'Data de nascimento é obrigatória';
        } 

        // Validação do email
        if (empty($this->email)) {
            $errors['email'] = 'Email é obrigatório';
        } 
        elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        } elseif ($this->email !== $this->conf_email)
         {
            $errors['conf_email'] = 'Emails não coincidem';
        }

        // Validação da senha
        if (empty($this->senha)) {
            $errors['senha'] = 'Senha é obrigatória';
        } elseif (strlen($this->senha) < 8) {
            $errors['senha'] = 'Senha deve ter pelo menos 8 caracteres';
        } elseif ($this->senha !== $this->conf_senha) {
            $errors['conf_senha'] = 'Senhas não coincidem';
        }

        if (!empty($errors)) {
            $_SESSION["login_errors"] = $errors;
            header("Location: ../login.php");
            die();
        }

        return $errors;
    }

    public function validarCPF($CPF)
    {
        $CPF = preg_replace('/[^0-9]/', '', $CPF);
        
        if (strlen($CPF) != 11) {
            return false;
        }
        
        if (preg_match('/^(\d)\1{10}$/', $CPF)) {
            return false;
        }
        
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $CPF[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : 11 - $resto;
        
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $CPF[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : 11 - $resto;
        
        return ($CPF[9] == $dv1 && $CPF[10] == $dv2);
    }

    public function validarCNPJ($CNPJ)
    {
        $CNPJ = preg_replace('/[^0-9]/', '', $CNPJ);
        
        if (strlen($CNPJ) != 14) {
            return false;
        }
        
        if (preg_match('/^(\d)\1{13}$/', $CNPJ)) {
            return false;
        }
        
        $soma = 0;
        $pesos = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 12; $i++) {
            $soma += $CNPJ[$i] * $pesos[$i];
        }
        $digito1 = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);
        
        $soma = 0;
        $pesos = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 13; $i++) {
            $soma += $CNPJ[$i] * $pesos[$i];
        }
        $digito2 = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);
        
        return ($CNPJ[12] == $digito1 && $CNPJ[13] == $digito2);
    }
}

class ValidarLogin 
{
    private $nome;
    private $identificador;
    private $senha;
    private $conn;

    public function __construct($nome, $identificador, $senha, $conn) 
    {
        $this->nome = trim($nome);
        $this->identificador = preg_replace('/[^0-9]/', '', $identificador);
        $this->senha = $senha;
        $this->conn = $conn;
    }

    public function validarDados()
    {
        $errors = [];
        
        if (empty($this->nome)) {
            $errors['nome'] = 'Nome é obrigatório';
        }
        
        if (empty($this->identificador)) {
            $errors['identificador'] = 'CPF/CNPJ é obrigatório';
        } elseif (strlen($this->identificador) !== 11 && strlen($this->identificador) !== 14) {
            $errors['identificador'] = 'CPF deve ter 11 dígitos ou CNPJ 14 dígitos';
        }
        
        if (empty($this->senha)) {
            $errors['senha'] = 'Senha é obrigatória';
        } elseif (strlen($this->senha) < 8) {  // CORREÇÃO: $senha em vez de $senha
            $errors['senha'] = 'Senha deve ter pelo menos 8 caracteres';
        }
        
        return $errors; // Retorna os erros em vez de redirecionar
    }
}