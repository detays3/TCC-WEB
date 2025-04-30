<?php
function Mostrar_erros_log() {
    if (isset($_SESSION["login_errors"])) {
       

        $errors=$_SESSION["login_erro"];

        foreach($errors as $error){

            echo'<h2 style="color:red">'. $error.'</h2>';

        }
        
    }


    unset($_SESSION["login_errors"]);
}
?>