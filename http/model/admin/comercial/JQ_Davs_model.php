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

if ($_POST["JQueryFunction"] == 'preencherTabelaDeDavs') {
    $response = array();

    $query = "SELECT
        A.ID AS ID_DAV ,
        A.VALIDADE,
        A.OBSERVACOES,
        A.OUTRAS_DESPESAS,
        A.FRETE,
        C.ID AS STATUS_ID,
        C.DESCRICAO AS STATUS,
        C.CLASSE,
        C.PERMITE_ALTERAR,
        A.ATIVO,
        B.NOME,
        B.DOCUMENTO
    FROM DAVS_DAV A 
        INNER JOIN BD_PESSOAS B ON B.ID = A.CLIENTE
        INNER JOIN DAVS_STATUS C ON C.ID = A.STATUS 
    WHERE A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";
    $resultado = $conexao->query($query);
    while ($dadosDavs = $resultado->fetch_assoc()) {
        $response['davs'][] = array(
            'id_dav' => $dadosDavs['ID_DAV'],
            'validade' => $dadosDavs['VALIDADE'],
            'observacoes' => $dadosDavs['OBSERVACOES'],
            'outras_despesas' => $dadosDavs['OUTRAS_DESPESAS'],
            'frete' => $dadosDavs['FRETE'],
            'status_id' => $dadosDavs['STATUS_ID'],
            'status' => $dadosDavs['STATUS'],
            'classe' => $dadosDavs['CLASSE'],
            'permite_alterar' => $dadosDavs['PERMITE_ALTERAR'],
            'ativo' => $dadosDavs['ATIVO'],
            'nome' => $dadosDavs['NOME'],
            'documento' => $dadosDavs['DOCUMENTO'],
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'buscarDav') {
    $response = array();

    $id = $_POST['id'];

    $query = "SELECT
        A.ID AS ID_DAV ,
        A.VALIDADE,
        A.OBSERVACOES,
        A.OUTRAS_DESPESAS,
        A.FRETE,
        C.ID AS STATUS_ID,
        C.DESCRICAO AS STATUS,
        C.PERMITE_ALTERAR,
        C.CLASSE,
        A.ATIVO,
        B.ID AS ID_CLIENTE,
        B.NOME,
        B.DOCUMENTO
    FROM DAVS_DAV A 
        INNER JOIN BD_PESSOAS B ON B.ID = A.CLIENTE
        INNER JOIN DAVS_STATUS C ON C.ID = A.STATUS 
    WHERE A.ID = '$id' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";
    $resultado = $conexao->query($query);
    while ($dadosDavs = $resultado->fetch_assoc()) {
        $response['statusHttp'] = 'success';

        $response['davs'] = array(
            'id_dav' => $dadosDavs['ID_DAV'],
            'validade' => $dadosDavs['VALIDADE'],
            'observacoes' => $dadosDavs['OBSERVACOES'],
            'outras_despesas' => $dadosDavs['OUTRAS_DESPESAS'],
            'frete' => $dadosDavs['FRETE'],
            'status_id' => $dadosDavs['STATUS_ID'],
            'permite_alterar' => $dadosDavs['PERMITE_ALTERAR'],
            'status' => $dadosDavs['STATUS'],
            'classe' => $dadosDavs['CLASSE'],
            'ativo' => $dadosDavs['ATIVO'],
            'nome' => $dadosDavs['NOME'],
            'id_cliente' => $dadosDavs['ID_CLIENTE'],
            'documento' => $dadosDavs['DOCUMENTO'],
        );

        $query = "SELECT
            A.ID AS ID_PRODUTO_DAV,
            A.DAV AS ID_DAV,
            A.ORDEM,
            B.ID AS PRODUTO_ID,
            B.DESCRICAO AS PRODUTO,
            A.QUANTIDADE,
            A.VALOR_UNITARIO,
            A.DESCONTO,
            A.VALOR_TOTAL,
            A.QUANTIDADE
        FROM DAVS_PRODUTOS A 
            INNER JOIN EST_PRODUTOS B ON B.ID = A.PRODUTO
        WHERE A.DAV = '$id' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";
        $resultado = $conexao->query($query);
        while ($dadosDavsProdutos = $resultado->fetch_assoc()) {
            $response['davs']['produtos'][] = array(
                'id_dav' => $dadosDavsProdutos['ID_DAV'],
                'ordem' => $dadosDavsProdutos['ORDEM'],
                'quantidade' => $dadosDavsProdutos['QUANTIDADE'],
                'produto_id' => $dadosDavsProdutos['PRODUTO_ID'],
                'produto' => $dadosDavsProdutos['PRODUTO'],
                'valor_unitario' => $dadosDavsProdutos['VALOR_UNITARIO'],
                'desconto' => $dadosDavsProdutos['DESCONTO'],
                'valor_total' => $dadosDavsProdutos['VALOR_TOTAL'],
            );
        }

        $query = "SELECT
            A.ID AS ID_PAGAMENTO_DAV,
            A.DAV AS ID_DAV,
            A.ORDEM,
            B.ID AS FORMA_PAGAMENTO_ID,
            B.DESCRICAO AS FORMA_PAGAMENTO,
            A.VALOR,
            A.VENCIMENTO
        FROM DAVS_PAGAMENTOS A 
            INNER JOIN FIN_FORMAS_DE_PAGAMENTO B ON B.ID = A.FORMA_PAGAMENTO
        WHERE A.DAV = '$id' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";
        $resultado = $conexao->query($query);
        while ($dadosDavsPagamentos = $resultado->fetch_assoc()) {
            $response['davs']['pagamentos'][] = array(
                'id_dav' => $dadosDavsPagamentos['ID_DAV'],
                'ordem' => $dadosDavsPagamentos['ORDEM'],
                'forma_pagamento_id' => $dadosDavsPagamentos['FORMA_PAGAMENTO_ID'],
                'forma_pagamento' => $dadosDavsPagamentos['FORMA_PAGAMENTO'],
                'valor' => $dadosDavsPagamentos['VALOR'],
                'vencimento' => $dadosDavsPagamentos['VENCIMENTO'],
            );
        }
    }

    echo json_encode($response);
}