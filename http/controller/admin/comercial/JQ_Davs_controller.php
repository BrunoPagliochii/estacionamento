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

if ($_POST["JQueryFunction"] == 'cadastrarDav') {
    $response = array();

    // Recebe os dados do POST
    $json_data = $_POST['dav'];

    // Converte o JSON em array associativo PHP
    $dav_data = json_decode($json_data, true);

    // Agora você pode acessar cada parte dos dados
    $cliente = $dav_data['cliente'];
    $validade = $dav_data['validade'];
    $produtos = $dav_data['produtos'];
    $pagamentos = $dav_data['pagamentos'];
    $status = $dav_data['status'];
    
    $observacoes = (!isset($dav_data["observacoes"]) || $dav_data["observacoes"] == '') ? 'NULL' : "'" . $dav_data["observacoes"] . "'";
    
    $total = str_replace(",", ".", (str_replace(".", "",  $dav_data['total'])));
    $frete = str_replace(",", ".", (str_replace(".", "",  $dav_data['frete'])));
    $outrasDespesas = str_replace(",", ".", (str_replace(".", "",  $dav_data['outrasDespesas'])));

    $query = "INSERT INTO DAVS_DAV (CLIENTE, VALIDADE, OBSERVACOES, OUTRAS_DESPESAS, FRETE, STATUS, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
    VALUES ('$cliente', '$validade', $observacoes, $outrasDespesas, $frete, '$status', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";

    if (!mysqli_query($conexao, $query)) {

        $response = array(
            'status' => 'error',
            'msg'    => mysqli_error($conexao),
            'query' => $query,
        );

        echo json_encode($response);
        exit;
    }

    // Coletar o ID do último registro inserido
    $id = mysqli_insert_id($conexao);

    foreach ($produtos as $produto) {

        $ordem = $produto['ordem'];
        $id = $produto['id'];
        $quantidade = $produto['quantidade'];
        $valor = $produto['valor'];
        $desconto = $produto['desconto'];
        $valorTotal = $produto['valorTotal'];

        $query = "INSERT INTO DAVS_PRODUTOS (DAV, ORDEM, PRODUTO, QUANTIDADE, VALOR_UNITARIO, DESCONTO, VALOR_TOTAL, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
        VALUES ('$id', '$ordem', '$id', '$quantidade', '$valor', '$desconto', '$valorTotal', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";
        if (!mysqli_query($conexao, $query)) {
            $response = array(
                'status' =>  'error',
                'msg' =>  mysqli_error($conexao),
            );
            echo json_encode($response);
            exit;
        }
    }

    foreach ($pagamentos as $pagamento) {

        $ordem = $pagamento['ordem'];
        $id = $pagamento['id'];
        $vencimento = $pagamento['vencimento'];
        $valor = $pagamento['valor'];

        $query = "INSERT INTO DAVS_PAGAMENTOS (DAV, ORDEM, FORMA_PAGAMENTO, VENCIMENTO, VALOR, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
        VALUES ('$id', '$ordem', '$id', '$vencimento', '$valor', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";
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
        'status' => 'success',
        'msg' => 'Dav cadastrado com sucesso!'
    );

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'editarDav') {
    $response = array();

    // Recebe os dados do POST
    $id = $_POST['id'];
    $json_data = $_POST['dav'];

    // Converte o JSON em array associativo PHP
    $dav_data = json_decode($json_data, true);

    // Agora você pode acessar cada parte dos dados
    $cliente = $dav_data['cliente'];
    $validade = $dav_data['validade'];
    $produtos = $dav_data['produtos'];
    $pagamentos = $dav_data['pagamentos'];
    $status = $dav_data['status'];
    
    $observacoes = (!isset($dav_data["observacoes"]) || $dav_data["observacoes"] == '') ? 'NULL' : "'" . $dav_data["observacoes"] . "'";

    $total = str_replace(",", ".", (str_replace(".", "",  $dav_data['total'])));
    $frete = str_replace(",", ".", (str_replace(".", "",  $dav_data['frete'])));
    $outrasDespesas = str_replace(",", ".", (str_replace(".", "",  $dav_data['outrasDespesas'])));

    // Atualiza a tabela DAVS_DAV
    $query = "UPDATE DAVS_DAV 
    SET CLIENTE = '$cliente', 
        VALIDADE = '$validade', 
        OBSERVACOES = $observacoes, 
        OUTRAS_DESPESAS = $outrasDespesas, 
        STATUS = '$status', 
        FRETE = $frete, 
        ALTERADOPOR = '$IDUSUARIOMODEL', 
        ALTERADOEM = NOW() 
    WHERE id = $id AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (!mysqli_query($conexao, $query)) {
        $response = array(
            'status' => 'error',
            'msg'    => mysqli_error($conexao),
            'query' => $query,
        );
        echo json_encode($response);
        exit;
    }

    // Excluir produtos existentes
    $deleteProdutosQuery = "DELETE FROM DAVS_PRODUTOS WHERE DAV = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";
    if (!mysqli_query($conexao, $deleteProdutosQuery)) {
        $response = array(
            'status' => 'error',
            'msg'    => mysqli_error($conexao),
            'query' => $deleteProdutosQuery,
        );
        echo json_encode($response);
        exit;
    }

    // Excluir pagamentos existentes
    $deletePagamentosQuery = "DELETE FROM DAVS_PAGAMENTOS WHERE DAV = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";
    if (!mysqli_query($conexao, $deletePagamentosQuery)) {
        $response = array(
            'status' => 'error',
            'msg'    => mysqli_error($conexao),
            'query' => $deletePagamentosQuery,
        );
        echo json_encode($response);
        exit;
    }

    // Inserir novos produtos
    foreach ($produtos as $produto) {
        $ordem = $produto['ordem'];
        $produtoId = $produto['id'];
        $quantidade = $produto['quantidade'];
        $valor = $produto['valor'];
        $desconto = $produto['desconto'];
        $valorTotal = $produto['valorTotal'];

        $query = "INSERT INTO DAVS_PRODUTOS (DAV, ORDEM, PRODUTO, QUANTIDADE, VALOR_UNITARIO, DESCONTO, VALOR_TOTAL, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
        VALUES ('$id', '$ordem', '$produtoId', '$quantidade', '$valor', '$desconto', '$valorTotal', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";
        if (!mysqli_query($conexao, $query)) {
            $response = array(
                'status' =>  'error',
                'msg' =>  mysqli_error($conexao),
            );
            echo json_encode($response);
            exit;
        }
    }

    // Inserir novos pagamentos
    foreach ($pagamentos as $pagamento) {
        $ordem = $pagamento['ordem'];
        $pagamentoId = $pagamento['id'];
        $vencimento = $pagamento['vencimento'];
        $valor = $pagamento['valor'];

        $query = "INSERT INTO DAVS_PAGAMENTOS (DAV, ORDEM, FORMA_PAGAMENTO, VENCIMENTO, VALOR, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
        VALUES ('$id', '$ordem', '$pagamentoId', '$vencimento', '$valor', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";
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
        'status' => 'success',
        'msg' => 'Dav cadastrado com sucesso!'
    );

    echo json_encode($response);
}



if ($_POST["JQueryFunction"] == 'inativarDav') {
    $response = array();

    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($status == 'S') {
        $status = 'N';
    } else {
        $status = 'S';
    }

    $query = "UPDATE DAVS_DAV SET
        ATIVO = '$status',
        ALTERADOPOR = '$IDUSUARIOMODEL',
        ALTERADOEM = NOW()
    WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {

        $query = "SELECT
            A.ID AS ID_DAV ,
            A.VALIDADE,
            A.OBSERVACOES,
            A.OUTRAS_DESPESAS,
            A.FRETE,
            C.ID AS STATUS_ID,
            C.DESCRICAO AS STATUS,
            C.CLASSE,
            A.ATIVO,
            B.NOME,
            B.DOCUMENTO
        FROM DAVS_DAV A 
            INNER JOIN BD_PESSOAS B ON B.ID = A.CLIENTE
            INNER JOIN DAVS_STATUS C ON C.ID = A.STATUS 
        WHERE A.ID = '$id' AND A.EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";
        $resultado = $conexao->query($query);
        while ($dadosDavs = $resultado->fetch_assoc()) {
            $response = array(
                'statusHttp' => 'success',
                'msg' => 'Dav alterado com sucesso!',
                'id_dav' => $dadosDavs['ID_DAV'],
                'validade' => $dadosDavs['VALIDADE'],
                'observacoes' => $dadosDavs['OBSERVACOES'],
                'outras_despesas' => $dadosDavs['OUTRAS_DESPESAS'],
                'frete' => $dadosDavs['FRETE'],
                'status_id' => $dadosDavs['STATUS_ID'],
                'status' => $dadosDavs['STATUS'],
                'classe' => $dadosDavs['CLASSE'],
                'ativo' => $dadosDavs['ATIVO'],
                'nome' => $dadosDavs['NOME'],
                'documento' => $dadosDavs['DOCUMENTO'],
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
