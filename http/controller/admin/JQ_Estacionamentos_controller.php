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
if ($_POST["JQueryFunction"] == 'novoEstacionamento') {
    $response = array();

    $horaEntrada = $_POST['horaEntrada'];
    $modelo = (!isset($_POST["modelo"]) || $_POST["modelo"] == '') ? 'NULL' : "'" . $_POST["modelo"] . "'";
    $placa = (!isset($_POST["placa"]) || $_POST["placa"] == '') ? 'NULL' : "'" . $_POST["placa"] . "'";
    $cor = (!isset($_POST["cor"]) || $_POST["cor"] == '') ? 'NULL' : "'" . $_POST["cor"] . "'";

    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $horaEntrada);
    if ($dateTime) {
        $horaEntrada = $dateTime->format('Y-m-d H:i:s');
    } else {
        // Se a conversão falhar, você pode lidar com o erro aqui
        $response = array(
            'status' => 'error',
            'msg' => 'Formato de data inválido.'
        );
        echo json_encode($response);
        exit;
    }

    $query = "INSERT INTO BD_ESTACIONAMENTOS (PLACA, MODELO, COR, HORA_ENTRADA, INCLUIDOPOR, INCLUIDOEM)
    VALUES ($placa, $modelo, $cor, '$horaEntrada', '$IDUSUARIOMODEL', NOW())";

    if (mysqli_query($conexao, $query)) {
        $last_id = mysqli_insert_id($conexao);

        $selectQuery = "SELECT A.PLACA, A.MODELO, A.COR, A.HORA_ENTRADA, A.ID, A.FINALIZADO, A.VALOR, A.FORMA_PAGAMENTO FROM BD_ESTACIONAMENTOS A WHERE A.FINALIZADO = 'N' AND A.ID = '$last_id'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastrado com Sucesso!',
                'id' => $dados['ID'],
                'placa' => $dados['PLACA'],
                'modelo' => $dados['MODELO'],
                'cor' => $dados['COR'],
                'hora_entrada' => $dados['HORA_ENTRADA'],
                'finalizado' => $dados['FINALIZADO'],
                'valor' => $dados['VALOR'],
                'forma_pagamento' => $dados['FORMA_PAGAMENTO'],
            );
        } else {
            $response = array(
                'status' => 'error',
                'msg' => 'Erro ao buscar dados atualizados.'
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

if ($_POST["JQueryFunction"] == 'excluirEstacionamento') {
    $response = array();

    $id = $_POST['id'];

    $query = "DELETE FROM BD_ESTACIONAMENTOS WHERE ID = '$id'";

    if (mysqli_query($conexao, $query)) {

        $response = array(
            'status' => 'success',
            'msg' => 'Cadastro foi excluido com Sucesso!',
        );
    } else {
        $response = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'pagarEstacionamento') {
    $response = array();

    $id = $_POST['id'];
    $formaPagamento = $_POST['formaPagamento'];
    $valorFinal = str_replace(",", ".", (str_replace(".", "",  $_POST['valorFinal'])));

    $query = "UPDATE BD_ESTACIONAMENTOS SET
        VALOR = '$valorFinal',
        FORMA_PAGAMENTO = '$formaPagamento'
    WHERE ID = '$id'";

    if (mysqli_query($conexao, $query)) {

        $selectQuery = "SELECT A.PLACA, A.MODELO, A.COR, A.HORA_ENTRADA, A.ID, A.FINALIZADO, A.VALOR, A.FORMA_PAGAMENTO FROM BD_ESTACIONAMENTOS A INNER JOIN BD_USUARIOS B ON B.ID = A.INCLUIDOPOR WHERE A.FINALIZADO = 'N' AND A.ID = '$id'";

        $resultado = mysqli_query($conexao, $selectQuery);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);

            $response = array(
                'status' => 'success',
                'msg' => 'Cadastro foi Alterado com Sucesso!',
                'id' => $dados['ID'],
                'placa' => $dados['PLACA'],
                'modelo' => $dados['MODELO'],
                'cor' => $dados['COR'],
                'hora_entrada' => $dados['HORA_ENTRADA'],
                'finalizado' => $dados['FINALIZADO'],
                'valor' => $dados['VALOR'],
                'forma_pagamento' => $dados['FORMA_PAGAMENTO'],
            );
        } else {
            $response = array(
                'status' => 'error',
                'msg' => 'Erro ao buscar dados atualizados.'
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

if ($_POST["JQueryFunction"] == 'finalizarEstacionamento') {
    $response = array();

    $id = $_POST['id'];
    $horaSaida = $_POST['horaSaida'];

    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $horaSaida);
    if ($dateTime) {
        $horaSaida = $dateTime->format('Y-m-d H:i:s');
    } else {
        // Se a conversão falhar, você pode lidar com o erro aqui
        $response = array(
            'status' => 'error',
            'msg' => 'Formato de data inválido.'
        );
        echo json_encode($response);
        exit;
    }

    $query = "UPDATE BD_ESTACIONAMENTOS SET
        FINALIZADO = 'S',
        FINALIZADOEM = '$horaSaida',
        FINALIZADOPOR = $IDUSUARIOMODEL
    WHERE ID = '$id'";

    if (mysqli_query($conexao, $query)) {

        $response = array(
            'status' => 'success',
            'msg' => 'Cadastro foi finalizado com Sucesso!',
        );
    } else {
        $response = array(
            'status' =>  'error',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}
