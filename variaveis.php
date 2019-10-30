<?php
    $nomeArquivo = __DIR__."/produto.json";
    $produtos = json_decode(file_get_contents($nomeArquivo),true);
?>