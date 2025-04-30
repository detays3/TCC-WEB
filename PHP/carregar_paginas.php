<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minha Página</title>
    <!-- Carrega o arquivo CSS -->
    <link rel="stylesheet" href="../CSS/css_principal.css">
    <script src="https://kit.fontawesome.com/9010bd4d09.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
  
    if (isset($_GET['pagina'])) {
        $pagina = $_GET['pagina'];
    
        // Carrega a página correspondente
        if ($pagina === 'documento') 
        {
            include '../documento.html';
        } else 
        {
            include '../pag_principal.html';
        }
    } else {
        // Carrega a página principal por padrão
        include '../pag_principal.html';
    }
    



  
    ?>
        <script src="../JS/mudar_tema.js"></script>
</body>
</html>