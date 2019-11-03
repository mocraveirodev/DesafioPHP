<?php
    function cadastrarProduto($nomeProduto,$categoriaProduto,$descProduto,$qtdProduto,$precoProduto,$imgProduto){
        $nomeArquivo = "produto.json";
        if(file_exists($nomeArquivo)){
            $arquivo = file_get_contents($nomeArquivo);
            $produtos = json_decode($arquivo,true);
            $produtos[] = ["nome"=>$nomeProduto,"categoria"=>$categoriaProduto,"desc"=>$descProduto,"qtd"=>$qtdProduto,"preco"=>$precoProduto,"img"=>$imgProduto];
            // var_dump($produtos);
            $json = json_encode($produtos);
            $deuCerto = file_put_contents($nomeArquivo,$json);
        }else{
            $produtos = [];
            $produtos[] = ["nome"=>$nomeProduto,"categoria"=>$categoriaProduto,"desc"=>$descProduto,"qtd"=>$qtdProduto,"preco"=>$precoProduto,"img"=>$imgProduto];
            // var_dump($produtos);
            $json = json_encode($produtos);
            $deuCerto = file_put_contents($nomeArquivo,$json);
        }
    }
    if($_POST){
        // var_dump($_FILES);
        // exit;
        $nomeImg = $_FILES["imgProduto"]["name"];
        $locasTmp = $_FILES["imgProduto"]["tmp_name"];
        $dataAtual = date("d-m-y");
        $caminhoSalvo = "img/".$dataAtual.$nomeImg;

        $deuCerto = move_uploaded_file($locasTmp,$caminhoSalvo);
        // exit;
        echo cadastrarProduto($_POST["nomeProduto"],$_POST["categoriaProduto"],$_POST["descProduto"],$_POST["qtdProduto"],$_POST["precoProduto"],$caminhoSalvo);
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Desafio PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        include_once("variaveis.php");
        // var_dump($produtos);
    ?>
    <div class="container">
        <div class="row p-4">
            <div class="col-7">
                <h1 class="pb-3">Todos os produtos</h1>
                
                <?php if(isset($produtos) && $produtos != []) {?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">Preço</th>
                                <th scope="col">Editar</th>
                                <th scope="col">Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php foreach($produtos as $produto){ ?>
                        <tr>
                            <td><a href="produto.php?nome=<?php echo $produto["nome"]; ?>"><?php echo $produto["nome"]; ?></a></td>
                            <td><?php echo $produto["categoria"]; ?></td>
                            <td><?php echo "R$ ".$produto["preco"]; ?></td>
                            <td><a href="desafio.php?nome=<?php echo $produto["nome"]; ?>" class="btn btn-primary btn-sm">Editar</a></td>
                            <td><a href="desafio.php?nome=<?php echo $produto["nome"]; ?>" class="btn btn-primary btn-sm">Excluir</a></td>
                            <!-- <td><button type="button" class="btn btn-primary btn-sm">Editar</button></td>
                            <td><button type="button" class="btn btn-primary btn-sm">Excluir</button></td> -->
                        </tr>
                    <?php } ?>
                        </tbody>
                    </table>
                <?php }else{ 
                    echo "<h3>Não tem produtos cadastrados nessa sessão! :(</h3>";
                    }
                ?>
            </div>
            <div class="col-5 bg-light p-5">
                <?php if(isset($_GET['nome'])): ?>
                    <?php foreach($produtos as $produto){ ?>
                        <?php if ($_GET['nome'] == $produto['nome']): ?>    
                            <h5 class="pb-3">Editar produto</h5>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="nomeProduto">Nome</label>
                                    <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" value="<?= $produto['nome'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="categoriaProduto">Categoria</label>
                                    <select class="form-control" id="categoriaProduto" name="categoriaProduto" required>
                                        <?php foreach($categorias as $categoria){ ?>
                                            <?php if ($categoria == $produto['categoria']): ?>
                                                <option selected><?= $produto['categoria'] ?></option>
                                            <?php else: ?>
                                                <option><?= $categoria ?></option>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="descProduto">Descrição</label>
                                    <textarea class="form-control noresize" id="descProduto" name="descProduto" rows="3" required><?= $produto['desc'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="qtdProduto">Quantidade</label>
                                    <input type="number" class="form-control" id="qtdProduto" name="qtdProduto" value="<?= $produto['qtd'] ?>" required> 
                                </div>
                                <div class="form-group">
                                    <label for="precoProduto">Preço</label>
                                    <input type="number" class="form-control" id="precoProduto" name="precoProduto" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any" value="<?= $produto['preco'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="imgProduto">Foto do produto</label>
                                    <input type="file" class="form-control-file" id="imgProduto" name="imgProduto" value="<?= $produto['img'] ?>">
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary">Editar</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    <?php } ?>
                <?php else: ?>
                    <h5 class="pb-3">Cadastrar produto</h5>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nomeProduto">Nome</label>
                            <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" required>
                        </div>
                        <div class="form-group">
                            <label for="categoriaProduto">Categoria</label>
                            <select class="form-control" id="categoriaProduto" name="categoriaProduto" required>
                                <option selected disabled>Selecione uma categoria</option>
                                <?php foreach($categorias as $categoria){ ?>
                                    <option><?= $categoria ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descProduto">Descrição</label>
                            <textarea class="form-control noresize" id="descProduto" name="descProduto" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="qtdProduto">Quantidade</label>
                            <input type="number" class="form-control" id="qtdProduto" name="qtdProduto" required> 
                        </div>
                        <div class="form-group">
                            <label for="precoProduto">Preço</label>
                            <input type="number" class="form-control" id="precoProduto" name="precoProduto" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any" required>
                        </div>
                        <div class="form-group">
                            <label for="imgProduto">Foto do produto</label>
                            <input type="file" class="form-control-file" id="imgProduto" name="imgProduto" required>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary">Enviar</button>
                        </div>
                    </form>                
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>