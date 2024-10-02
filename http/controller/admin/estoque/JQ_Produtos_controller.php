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

if ($_POST["JQueryFunction"] == 'novoProduto') {
    $response = array();

    $descricao = $_POST['descricao'];
    $quantidade = str_replace(",", ".", (str_replace(".", "", $_POST["quantidade"])));
    $custo = str_replace(",", ".", (str_replace(".", "", $_POST["custo"])));
    $valorVenda = str_replace(",", ".", (str_replace(".", "", $_POST["valorVenda"])));
    $peso = (!isset($_POST["peso"]) || $_POST["peso"] == '') ? 'NULL' : "'" . str_replace(",", ".", (str_replace(".", "", $_POST["peso"]))) . "'";
    $tipoMercadoria = $_POST['tipoMercadoria'];
    $unidadeMedida = $_POST['unidadeMedida'];
    $grupo = (!isset($_POST["grupo"]) || $_POST["grupo"] == '') ? 'NULL' : "'" . $_POST["grupo"] . "'";
    $cor = (!isset($_POST["cor"]) || $_POST["cor"] == '') ? 'NULL' : "'" . $_POST["cor"] . "'";
    $tamanho = (!isset($_POST["tamanho"]) || $_POST["tamanho"] == '') ? 'NULL' : "'" . $_POST["tamanho"] . "'";

    $query = "INSERT INTO EST_PRODUTOS (DESCRICAO, GRUPO, COR, TAMANHO, UNIDADE_MEDIDA, TIPO_MERCADORIA, VALOR_VENDA, CUSTO, PESO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
    VALUES ('$descricao', $grupo, $cor, $tamanho, '$unidadeMedida', '$tipoMercadoria', '$valorVenda', '$custo', $peso, '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

    if (!mysqli_query($conexao, $query)) {

        $response = array(
            'status' => 'error',
            'msg'    => mysqli_error($conexao),
        );

        echo json_encode($response);
        exit;
    }

    // Coletar o ID do último registro inserido
    $lastInsertId = mysqli_insert_id($conexao);

    if ($quantidade > 0) {
        $custoTotal = $custo * $quantidade;

        $query = "INSERT INTO EST_PRODUTOS_HISTORICO (DESCRICAO, PRODUTO, QUANTIDADE, CUSTO, CUSTO_UNITARIO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
        VALUES ('Cadastro do produto', '$lastInsertId', '$quantidade', '$custoTotal', '$custo', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

        if (!mysqli_query($conexao, $query)) {
            $response = array(
                'status' =>  'error',
                'msg' =>  mysqli_error($conexao),
            );
            echo json_encode($response);
            exit;
        }
    }

    $response = array(
        'status' =>  'success',
        'msg' =>  'Cadastro foi Alterado com Sucesso!.',
    );
    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'editarProduto') {
    $response = array();

    // Coletando dados do formulário
    $idProduto = $_POST['idProduto'];
    $descricao = $_POST['descricao'];
    $quantidade = str_replace(",", ".", (str_replace(".", "", $_POST["quantidade"])));
    $quantidadeAntiga = str_replace(",", ".", (str_replace(".", "", $_POST["quantidadeAntiga"])));

    $custo = str_replace(",", ".", (str_replace(".", "", $_POST["custo"])));
    $valorVenda = str_replace(",", ".", (str_replace(".", "", $_POST["valorVenda"])));
    $peso = (!isset($_POST["peso"]) || $_POST["peso"] == '') ? 'NULL' : "'" . str_replace(",", ".", (str_replace(".", "", $_POST["peso"]))) . "'";
    $tipoMercadoria = $_POST['tipoMercadoria'];
    $unidadeMedida = $_POST['unidadeMedida'];
    $grupo = (!isset($_POST["grupo"]) || $_POST["grupo"] == '') ? 'NULL' : "'" . $_POST["grupo"] . "'";
    $cor = (!isset($_POST["cor"]) || $_POST["cor"] == '') ? 'NULL' : "'" . $_POST["cor"] . "'";
    $tamanho = (!isset($_POST["tamanho"]) || $_POST["tamanho"] == '') ? 'NULL' : "'" . $_POST["tamanho"] . "'";

    // Query de UPDATE ao invés de INSERT
    $query = "UPDATE EST_PRODUTOS 
              SET DESCRICAO = '$descricao', 
                  GRUPO = $grupo, 
                  COR = $cor, 
                  TAMANHO = $tamanho, 
                  UNIDADE_MEDIDA = '$unidadeMedida', 
                  TIPO_MERCADORIA = '$tipoMercadoria', 
                  VALOR_VENDA = '$valorVenda', 
                  CUSTO = '$custo', 
                  PESO = $peso, 
                  ALTERADOPOR = '$IDUSUARIOMODEL', 
                  ALTERADOEM = NOW()
              WHERE ID = '$idProduto' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    // Executando a query de UPDATE
    if (!mysqli_query($conexao, $query)) {
        $response = array(
            'status' => 'error',
            'msg' => mysqli_error($conexao),
        );

        echo json_encode($response);
        exit;
    }

    // Se a quantidade for maior que 0, atualizar histórico
    if ($quantidade > 0 && $quantidade != $quantidadeAntiga) {

        $diferenca = $quantidade - $quantidadeAntiga;
        $custoTotal = $custo * $diferenca;

        $query = "INSERT INTO EST_PRODUTOS_HISTORICO (DESCRICAO, PRODUTO, QUANTIDADE, CUSTO, CUSTO_UNITARIO, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
                  VALUES ('Alteração manual no produto', '$idProduto', '$diferenca', '$custoTotal', '$custo', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

        if (!mysqli_query($conexao, $query)) {
            $response = array(
                'status' => 'error',
                'msg'    => mysqli_error($conexao),
            );
            echo json_encode($response);
            exit;
        }
    }

    // Retornar sucesso
    $response = array(
        'status' => 'success',
        'msg'    => 'Produto foi atualizado com sucesso!',
    );
    echo json_encode($response);
}


if ($_POST["JQueryFunction"] == 'inativarProduto') {
    $response = array();

    $ID = $_POST['ID'];
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }
    
    $query = "UPDATE EST_PRODUTOS SET
        ATIVO = '$status',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$ID' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";


    if (mysqli_query($conexao, $query)) {
        $resultado = $conexao->query("SELECT 
            A.ID, 
            A.DESCRICAO, 
            A.GRUPO AS GRUPO_ID, 
            A.COR AS COR_ID, 
            A.TAMANHO AS TAMANHO_ID, 
            A.UNIDADE_MEDIDA AS UNIDADE_MEDIDA_ID, 
            A.TIPO_MERCADORIA AS TIPO_MERCADORIA_ID,
            A.VALOR_VENDA, 
            A.CUSTO,
            A.PESO,
            B.DESCRICAO AS GRUPO,
            C.DESCRICAO AS UNIDADE_MEDIDA,
            C.SIGLA AS UNIDADE_MEDIDA_SIGLA,
            D.DESCRICAO AS TIPO_MERCADORIA,
            E.DESCRICAO AS COR,
            E.HEXADECIMAL,
            F.DESCRICAO AS TAMANHO,
            F.SIGLA AS TAMANHO_SIGLA,
            (SELECT SUM(QUANTIDADE) FROM EST_PRODUTOS_HISTORICO HIS WHERE HIS.PRODUTO = A.ID) AS QUANTIDADE,
            (SELECT AVG(CUSTO_UNITARIO) FROM EST_PRODUTOS_HISTORICO HIS WHERE HIS.PRODUTO = A.ID) AS CUSTO_MEDIO,
            (SELECT CUSTO_UNITARIO FROM EST_PRODUTOS_HISTORICO HIS WHERE HIS.PRODUTO = A.ID ORDER BY HIS.ID DESC LIMIT 1) AS CUSTO_ULTIMA_COMPRA,
            A.ATIVO
        FROM EST_PRODUTOS A 
            LEFT JOIN EST_GRUPOS B ON B.ID = A.GRUPO
            LEFT JOIN EST_UNIDADES_MEDIDA C ON C.ID = A.UNIDADE_MEDIDA
            LEFT JOIN EST_TIPOS_MERCADORIA D ON D.ID = A.TIPO_MERCADORIA
            LEFT JOIN EST_CORES E ON E.ID = A.COR
            LEFT JOIN EST_TAMANHOS F ON F.ID = A.TAMANHO
        WHERE A.ID = '$ID' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
        if (!$resultado) {
            die("Erro na consulta: " . $conexao->error);
        }

        while ($dadosProduto = $resultado->fetch_assoc()) {

            $response = array(
                'status' => 'success',
                'id' => $dadosProduto['ID'],
                'descricao' => $dadosProduto['DESCRICAO'],
                'grupo_id' => $dadosProduto['GRUPO_ID'],
                'cor_id' => $dadosProduto['COR_ID'],
                'tamanho_id' => $dadosProduto['TAMANHO_ID'],
                'unidade_medida_id' => $dadosProduto['UNIDADE_MEDIDA_ID'],
                'tipo_mercadoria_id' => $dadosProduto['TIPO_MERCADORIA_ID'],
                'quantidade' => number_format($dadosProduto['QUANTIDADE'], 2, ',', '.'),
                'valor_venda' => number_format($dadosProduto['VALOR_VENDA'], 2, ',', '.'),
                'custo' => number_format($dadosProduto['CUSTO'], 2, ',', '.'),
                'peso' => number_format($dadosProduto['PESO'], 2, ',', '.'),
                'grupo' => $dadosProduto['GRUPO'],
                'unidade_medida' => $dadosProduto['UNIDADE_MEDIDA'],
                'unidade_medida_sigla' => $dadosProduto['UNIDADE_MEDIDA_SIGLA'],
                'tipo_mercadoria' => $dadosProduto['TIPO_MERCADORIA'],
                'cor' => $dadosProduto['COR'],
                'tamanho' => $dadosProduto['TAMANHO'],
                'tamanho_sigla' => $dadosProduto['TAMANHO_SIGLA'],
                'quantidade' => number_format($dadosProduto['QUANTIDADE'], 2, ',', '.'),
                'custo_medio' => number_format($dadosProduto['CUSTO_MEDIO'], 2, ',', '.'),
                'custo_ultima_compra' => number_format($dadosProduto['CUSTO_ULTIMA_COMPRA'], 2, ',', '.'),
                'hexadecimal' => $dadosProduto['HEXADECIMAL'],
                'ativo' => $dadosProduto['ATIVO'],
            );
        }
    } else {

        $response = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}
