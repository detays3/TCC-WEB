<?php

class Validar {


    // Função alternativa mais simples (opcional)
    function ValidarCPF($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if (strlen($cpf) != 11 || preg_match('/^(\d)\1*$/', $cpf)) {
            return false;
        }
        
        // Calcula primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Calcula segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Verifica se os dígitos calculados conferem com os informados
        return ($cpf[9] == $dv1 && $cpf[10] == $dv2);
    }
}

// Exemplo de uso:
// $validador = new Validar();
// if ($validador->ValidarCPF('123.456.789-09')) {
//     echo 'CPF válido';
// } else {
//     echo 'CPF inválido';
// }

?>