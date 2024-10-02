<?php
if (file_exists('../lib/PHP_conecta.php')) {
    require_once '../lib/jwt_auth_functions.php';
    require_once '../lib/PHP_conecta.php';
    require_once '../functions/helpers.php';
} else if (file_exists('../../lib/PHP_conecta.php')) {
    require_once '../../lib/jwt_auth_functions.php';
    require_once '../../lib/PHP_conecta.php';
    require_once '../../functions/helpers.php';
} else if (file_exists('../../../lib/PHP_conecta.php')) {
    require_once '../../../lib/jwt_auth_functions.php';
    require_once '../../../lib/PHP_conecta.php';
    require_once '../../../functions/helpers.php';
} else if (file_exists('../../../../lib/PHP_conecta.php')) {
    require_once '../../../../lib/jwt_auth_functions.php';
    require_once '../../../../lib/PHP_conecta.php';
    require_once '../../../../functions/helpers.php';
}

// --------------------------------------------------------------------
$emailUsuario_token = validateJWTToken();
// --------------------------------------------------------------------
$query = "SELECT A.NOME, A.EMAIL, A.ID, B.RAZAO_SOCIAL, A.EMPRESA_ID FROM BD_USUARIOS A 
          INNER JOIN BD_EMPRESAS B ON B.ID = A.EMPRESA_ID 
          WHERE A.ATIVO = 'S' AND B.ATIVO = 'S' AND A.EMAIL = ?";

$stmt = $conexao->prepare($query);
$stmt->bind_param('s', $emailUsuario_token);
$stmt->execute();
$result = $stmt->get_result();

if ($dadossiderbar = $result->fetch_assoc()) {
    $IDUSUARIOMODEL = $dadossiderbar['ID'];
    $NOMEUSUARIOMODEL = $dadossiderbar['NOME'];
    $EMAILUSUARIOMODEL = $dadossiderbar['EMAIL'];
    $EMPRESAUSUARIOMODEL = $dadossiderbar['RAZAO_SOCIAL'];
    $IDEMPRESAUSUARIOMODEL = $dadossiderbar['EMPRESA_ID'];
}
// --------------------------------------------------------------------

if ($_POST["JQueryFunction"] == 'cadastrarOrdemDeComposicao') {
    $response = array();

    $descricao = $_POST['descricao'];

    $query = "INSERT INTO EST_ORDEM_COMPOSICAO (DESCRICAO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
    VALUES ('$descricao', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

    if (mysqli_query($conexao, $query)) {
        $last_id = mysqli_insert_id($conexao);

        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro Realizado com Sucesso!',
            'id' => $last_id,
            'descricao' => $descricao,
            'ativo' => 'S',
        );
    } else {
        $response = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'inativarOrdemDeComposicao') {
    $response = array();

    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }

    $query = "UPDATE EST_ORDEM_COMPOSICAO SET
        ATIVO = '$status',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        // Após o update, buscar os dados atualizados incluindo a coluna ATIVO
        $selectQuery = "SELECT DESCRICAO, ATIVO FROM EST_ORDEM_COMPOSICAO 
        WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $id,
                'descricao' => $dados['DESCRICAO'],
                'ativo' => $dados['ATIVO']
            );
        } else {
            $response = array(
                'status' => 'error',
                'msg' => 'Erro ao buscar dados atualizados.'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'msg' => mysqli_error($conexao)
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'adicionarProdutoUsado') {
    $response = array();

    $produto = $_POST['produto'];
    $ordem = $_POST['ordem'];
    $quantidade = str_replace(",", ".", (str_replace(".", "", $_POST["quantidade"])));

    $query = "INSERT INTO EST_ORDEM_COMPOSICAO_PRODUTOS (ORDEM, PRODUTO, QUANTIDADE, TIPO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
    VALUES ('$ordem', '$produto', '$quantidade', '2', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro realizado com sucesso!.',
        );
    } else {

        $response = array(
            'status' =>  'danger',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'adicionarProdutoGerado') {
    $response = array();

    $produto = $_POST['produto'];
    $ordem = $_POST['ordem'];
    $quantidade = str_replace(",", ".", (str_replace(".", "", $_POST["quantidade"])));

    $query = "INSERT INTO EST_ORDEM_COMPOSICAO_PRODUTOS (ORDEM, PRODUTO, QUANTIDADE, TIPO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
    VALUES ('$ordem', '$produto', '$quantidade', '1', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro realizado com sucesso!.',
        );
    } else {

        $response = array(
            'status' =>  'danger',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'editarProdutoUsado') {
    $response = array();

    $produto = $_POST['produto'];
    $ordem = $_POST['ordem'];
    $id = $_POST['id'];
    $quantidade = str_replace(",", ".", (str_replace(".", "", $_POST["quantidade"])));

    // Atualizando o registro existente
    $query = "UPDATE EST_ORDEM_COMPOSICAO_PRODUTOS SET 
        QUANTIDADE = '$quantidade', 
        PRODUTO = '$produto', 
        ALTERADOPOR = '$IDUSUARIOMODEL', 
        ALTERADOEM = NOW()
    WHERE ID = '$id' AND ORDEM = '$ordem' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' =>  'success',
            'msg' =>  'Produto atualizado com sucesso!',
        );
    } else {
        $response = array(
            'status' =>  'danger',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'editarProdutoGerado') {
    $response = array();

    $produto = $_POST['produto'];
    $ordem = $_POST['ordem'];
    $id = $_POST['id'];
    $quantidade = str_replace(",", ".", (str_replace(".", "", $_POST["quantidade"])));

    // Atualizando o registro existente
    $query = "UPDATE EST_ORDEM_COMPOSICAO_PRODUTOS SET 
        QUANTIDADE = '$quantidade', 
        PRODUTO = '$produto', 
        ALTERADOPOR = '$IDUSUARIOMODEL', 
        ALTERADOEM = NOW()
    WHERE ID = '$id' AND ORDEM = '$ordem' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' =>  'success',
            'msg' =>  'Produto atualizado com sucesso!',
        );
    } else {
        $response = array(
            'status' =>  'danger',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'deletarProdutoUsado') {
    $array = array();

    $ID = $_POST['id'];

    $query = "DELETE FROM EST_ORDEM_COMPOSICAO_PRODUTOS WHERE ID = '$ID' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";


    if (mysqli_query($conexao, $query)) {
        $array = array(
            'status' =>  'success',
            'msg' =>  'Cadastro removido com Sucesso!.',
        );
    } else {

        $array = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($array);
}

if ($_POST["JQueryFunction"] == 'deletarProdutoGerado') {
    $array = array();

    $ID = $_POST['id'];

    $query = "DELETE FROM EST_ORDEM_COMPOSICAO_PRODUTOS WHERE ID = '$ID' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";


    if (mysqli_query($conexao, $query)) {
        $array = array(
            'status' =>  'success',
            'msg' =>  'Cadastro removido com Sucesso!.',
        );
    } else {

        $array = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($array);
}

if ($_POST["JQueryFunction"] == 'processarComposicao') {
    $array = array();

    // Coletando dados do formulário
    $id = $_POST['id'];
    $quantidade = str_replace(",", ".", (str_replace(".", "", $_POST["quantidade"])));

    // Inserir na tabela EST_ORDEM_COMPOSICAO_HISTORICO
    $queryHistorico = "INSERT INTO EST_ORDEM_COMPOSICAO_HISTORICO (ORDEM, QUANTIDADE, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
                       VALUES ('$id', '$quantidade', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

    if (!mysqli_query($conexao, $queryHistorico)) {
        $array = array(
            'status' => 'error',
            'msg'    => mysqli_error($conexao),
        );
        echo json_encode($array);
        exit;
    }

    // Recuperar o ID gerado pela inserção na tabela EST_ORDEM_COMPOSICAO_HISTORICO
    $idHistorico = mysqli_insert_id($conexao);

    $produtosUsadosArray = array();
    $custoTotal = 0;
    $resultado = $conexao->query("SELECT
        A.ID, 
        A.QUANTIDADE,
        B.DESCRICAO,
        A.PRODUTO AS PRODUTO_ID,
        (SELECT SUM(QUANTIDADE) FROM EST_PRODUTOS_HISTORICO HIS WHERE HIS.PRODUTO = A.PRODUTO) AS QUANTIDADE_ESTOQUE,
        B.CUSTO
    FROM EST_ORDEM_COMPOSICAO_PRODUTOS A 
        INNER JOIN EST_PRODUTOS B ON B.ID = A.PRODUTO
    WHERE B.ATIVO = 'S' AND A.TIPO = 2 AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL' AND A.ORDEM = " . $id);
    if (!$resultado) {
        die("Erro na consulta: " . $conexao->error);
    }

    while ($row = $resultado->fetch_assoc()) {
        $produtosUsadosArray[] = $row;
    }

    // Acumula o custo dos produtos utilizados
    foreach ($produtosUsadosArray as $produto) {
        $idProduto = $produto['PRODUTO_ID'];
        $quantidadeUsada = $produto['QUANTIDADE'] * $quantidade;
        $custoProduto = $produto['CUSTO'] * $quantidadeUsada;
        $custoTotal += $custoProduto;
        $diferenca = $quantidadeUsada * -1;

        $query = "INSERT INTO EST_PRODUTOS_HISTORICO (DESCRICAO, PRODUTO, QUANTIDADE, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
                  VALUES ('Produto utilizado na ordem de composição $id processo: $idHistorico', '$idProduto', '$diferenca', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

        if (!mysqli_query($conexao, $query)) {
            $array = array(
                'status' => 'error',
                'msg'    => mysqli_error($conexao),
            );
            echo json_encode($array);
            exit;
        }
    }

    $produtosGeradosArray = array();
    $resultado = $conexao->query("SELECT
        A.ID, 
        A.QUANTIDADE,
        B.DESCRICAO,
        A.PRODUTO AS PRODUTO_ID,
        (SELECT SUM(QUANTIDADE) FROM EST_PRODUTOS_HISTORICO HIS WHERE HIS.PRODUTO = A.PRODUTO) AS QUANTIDADE_ESTOQUE,
        B.CUSTO
    FROM EST_ORDEM_COMPOSICAO_PRODUTOS A 
        INNER JOIN EST_PRODUTOS B ON B.ID = A.PRODUTO
    WHERE B.ATIVO = 'S' AND A.TIPO = 1 AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL' AND A.ORDEM = " . $id);
    if (!$resultado) {
        die("Erro na consulta: " . $conexao->error);
    }

    // Processar o produto gerado e calcular o custo unitário
    while ($row = $resultado->fetch_assoc()) {
        $produtosGeradosArray[] = $row;
    }

    foreach ($produtosGeradosArray as $produto) {
        $idProduto = $produto['PRODUTO_ID'];
        $quantidadeGerada = $produto['QUANTIDADE'] * $quantidade;

        // Calcular o custo unitário do produto gerado
        $custoUnitario = $custoTotal / $quantidadeGerada;

        $query = "INSERT INTO EST_PRODUTOS_HISTORICO (DESCRICAO, PRODUTO, QUANTIDADE, CUSTO, CUSTO_UNITARIO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
                  VALUES ('Produto gerado na ordem de composição $id processo: $idHistorico', '$idProduto', '$quantidadeGerada', '$custoTotal', '$custoUnitario', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

        if (!mysqli_query($conexao, $query)) {
            $array = array(
                'status' => 'error',
                'msg'    => mysqli_error($conexao),
            );
            echo json_encode($array);
            exit;
        }
    }

    // Retornar sucesso
    $array = array(
        'status' => 'success',
        'msg'    => 'Produto foi atualizado com sucesso!',
    );
    echo json_encode($array);
}
