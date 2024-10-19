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
$query = "SELECT A.NOME, A.EMAIL, A.ID FROM BD_USUARIOS A WHERE A.ATIVO = 'S' AND A.EMAIL = ?";

$stmt = $conexao->prepare($query);
$stmt->bind_param('s', $emailUsuario_token);
$stmt->execute();
$result = $stmt->get_result();

if ($dadossiderbar = $result->fetch_assoc()) {
    $IDUSUARIOMODEL = $dadossiderbar['ID'];
    $NOMEUSUARIOMODEL = $dadossiderbar['NOME'];
    $EMAILUSUARIOMODEL = $dadossiderbar['EMAIL'];
}
// --------------------------------------------------------------------

if ($_POST["JQueryFunction"] == 'preencherTabelaDeEstacionamentos') {
    $response = array();

    $query = "SELECT A.PLACA, A.MODELO, A.COR, A.HORA_ENTRADA, A.ID, A.FINALIZADO, A.VALOR, A.FORMA_PAGAMENTO FROM BD_ESTACIONAMENTOS A WHERE A.FINALIZADO = 'N'";

    $resultado = $conexao->query($query);
    if (!$resultado) {
        die("Erro na consulta: " . $conexao->error);
    }

    while ($dados = $resultado->fetch_assoc()) {
        $response[] = array(
            'id' => $dados['ID'],
            'placa' => $dados['PLACA'],
            'modelo' => $dados['MODELO'],
            'cor' => $dados['COR'],
            'hora_entrada' => $dados['HORA_ENTRADA'],
            'finalizado' => $dados['FINALIZADO'],
            'valor' => $dados['VALOR'],
            'forma_pagamento' => $dados['FORMA_PAGAMENTO'],
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'preencherTabelaDeEstacionamentosGeral') {
    $response = array();

    $whereCondition = '';
    if (isset($_POST['horaEntradaInicio']) && $_POST['horaEntradaInicio'] != '' && $_POST['horaEntradaInicio'] != null && $_POST['horaEntradaInicio'] != 'null') {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['horaEntradaInicio']);
        $horaEntradaInicio = $dateTime->format('Y-m-d H:i:s');

        $whereCondition .= ' AND A.HORA_ENTRADA >= \'' . $horaEntradaInicio . '\'';
    }

    if (isset($_POST['horaEntradaFim']) && $_POST['horaEntradaFim'] != '' && $_POST['horaEntradaFim'] != null && $_POST['horaEntradaFim'] != 'null') {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['horaEntradaFim']);
        $horaEntradaFim = $dateTime->format('Y-m-d H:i:s');

        $whereCondition .= ' AND A.HORA_ENTRADA <= \'' . $horaEntradaFim . '\'';
    }

    if (isset($_POST['horaSaidaInicio']) && $_POST['horaSaidaInicio'] != '' && $_POST['horaSaidaInicio'] != null && $_POST['horaSaidaInicio'] != 'null') {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['horaSaidaInicio']);
        $horaSaidaInicio = $dateTime->format('Y-m-d H:i:s');

        $whereCondition .= ' AND A.FINALIZADOEM >= \'' . $horaSaidaInicio . '\'';
    }

    if (isset($_POST['horaSaidaFim']) && $_POST['horaSaidaFim'] != '' && $_POST['horaSaidaFim'] != null && $_POST['horaSaidaFim'] != 'null') {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['horaSaidaFim']);
        $horaSaidaFim = $dateTime->format('Y-m-d H:i:s');

        $whereCondition .= ' AND A.FINALIZADOEM <= \'' . $horaSaidaFim . '\'';
    }

    if (isset($_POST['formaPagamento']) && $_POST['formaPagamento'] != '' && $_POST['formaPagamento'] != null && $_POST['formaPagamento'] != 'null') {
        $formaPagamento = $_POST['formaPagamento'];

        $whereCondition .= ' AND A.FORMA_PAGAMENTO = \'' . $formaPagamento . '\'';
    }



    $query = "SELECT A.PLACA, A.MODELO, A.VALOR, A.COR, A.HORA_ENTRADA, A.ID, A.FINALIZADO, B.NOME AS QUEM_ENTROU, C.NOME AS QUEM_SAIU, FORMA_PAGAMENTO, A.FINALIZADOEM
    FROM BD_ESTACIONAMENTOS A INNER JOIN BD_USUARIOS B ON B.ID = A.INCLUIDOPOR  
    LEFT JOIN BD_USUARIOS C ON C.ID = A.FINALIZADOPOR 
    WHERE A.VALOR IS NOT NULL $whereCondition ";

    $resultado = $conexao->query($query);
    if (!$resultado) {
        die("Erro na consulta: " . $conexao->error);
    }

    while ($dados = $resultado->fetch_assoc()) {
        $response[] = array(
            'id' => $dados['ID'],
            'placa' => $dados['PLACA'],
            'modelo' => $dados['MODELO'],
            'cor' => $dados['COR'],
            'hora_entrada' => $dados['HORA_ENTRADA'],
            'finalizado' => $dados['FINALIZADO'],
            'quem_entrou' => $dados['QUEM_ENTROU'],
            'quem_saiu' => $dados['QUEM_SAIU'],
            'forma_pagamento' => $dados['FORMA_PAGAMENTO'],
            'hora_saida' => $dados['FINALIZADOEM'],
            'valor' => $dados['VALOR'],

        );
    }

    echo json_encode($response);
}
