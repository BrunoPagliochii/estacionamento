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

if ($_POST["JQueryFunction"] == 'preencherTabelaDeProdutos') {
    $response = array();

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
    WHERE A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
    if (!$resultado) {
        die("Erro na consulta: " . $conexao->error);
    }

    while ($dadosProduto = $resultado->fetch_assoc()) {

        $response[] = array(
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

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'buscarProduto') {
    $response = array();

    $id = $_POST['id'];

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
    WHERE A.ID = '$id' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
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

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'buscarHistoricoProduto') {
    $response = array();

    $id = $_POST['id'];

    $resultado = $conexao->query("SELECT 
        A.ID,
        A.DESCRICAO,
        A.QUANTIDADE,
        A.CUSTO,
        A.CUSTO_UNITARIO,
        B.NOME AS INCLUIDOPOR,
        A.INCLUIDOEM
    FROM EST_PRODUTOS_HISTORICO A
        INNER JOIN BD_USUARIOS B ON B.ID = A.INCLUIDOPOR
    WHERE A.PRODUTO = '$id' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'");
    if (!$resultado) {
        die("Erro na consulta: " . $conexao->error);
    }

    while ($dadosProduto = $resultado->fetch_assoc()) {

        $response[] = array(
            'id' => $dadosProduto['ID'],
            'descricao' => $dadosProduto['DESCRICAO'],
            'quantidade' => number_format($dadosProduto['QUANTIDADE'], 2, ',', '.'),
            'custo' => number_format($dadosProduto['CUSTO'], 2, ',', '.'),
            'custo_unitario' => number_format($dadosProduto['CUSTO_UNITARIO'], 2, ',', '.'),
            'incluidopor' => $dadosProduto['INCLUIDOPOR'],
            'incluidoem' => $dadosProduto['INCLUIDOEM'],
        );
    }

    echo json_encode($response);
}
