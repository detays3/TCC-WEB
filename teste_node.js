<?php

include 'validar_CPF.php';


$CPF_TESTE=75875187034;

$validar_cpf = new Validar($CPF_TESTE);



if ($validar_cpf->ValidarCPF($CPF_TESTE)) 
     {
         echo 'CPF válido';
     }
      else 
     {
         echo 'CPF inválido';
     }






?>