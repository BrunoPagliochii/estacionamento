<?php
$dados = array(
    'NomePagina' => 'Relatórios',
    'MenuModulo' => 'Geral'
);

include('layouts/body.php');
?>

<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-filtrar" class="btn btn-info mb-3 mx-1">
    <i class="fas fa-search"></i>
</a>

<div class="row">

    <div class="col-6 mb-3">
        <div class="card card-dark h-100">
            <div class="card-header">
                <h4>Tempo médio que o carro fica estacionado</h4>
            </div>

            <div class="card-body">
                <canvas style="width: 100%; height: 400px;" id="chartTempoMedio"></canvas>

            </div>
        </div>
    </div>

    <div class="col-6 mb-3">
        <div class="card card-dark h-100">
            <div class="card-header">
                <h4>Quantidade por modelo</h4>
            </div>

            <div class="card-body">
                <canvas style="width: 100%; height: 400px;" id="chartPorModelo"></canvas>
            </div>
        </div>
    </div>

    <div class="col-6 mb-3">
        <div class="card card-dark h-100">
            <div class="card-header">
                <h4>Quantidade de veículos por dia (12h-11:59h)</h4>
            </div>

            <div class="card-body">
                <canvas style="width: 100%; height: 400px;" id="chartPorDia"></canvas>
            </div>
        </div>
    </div>

    <div class="col-6 mb-3">
        <div class="card card-dark h-100">
            <div class="card-header">
                <h4>Quantidade por forma de pagamento</h4>
            </div>

            <div class="card-body">
                <canvas style="width: 100%; height: 400px;" id="chartPorFormaPagamento"></canvas>
            </div>
        </div>
    </div>

    <div class="col-6 mb-3">
        <div class="card card-dark h-100">
            <div class="card-header">
                <h4>Valor arrecadado por dia (12h-11:59h)</h4>
            </div>

            <div class="card-body">
                <canvas style="width: 100%; height: 400px;" id="chartValorPorDia"></canvas>
            </div>
        </div>
    </div>

    <div class="col-6 mb-3">
        <div class="card card-dark h-100">
            <div class="card-header">
                <h4>Quantidade de Carros por Hora</h4>
            </div>
            <div class="card-body">
                <canvas style="width: 100%; height: 400px;" id="chartPorHora"></canvas>
            </div>
        </div>
    </div>
</div>

<?php
include('layouts/footer.php');

?>

<div class="modal fade" id="modal-filtrar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtrar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="get">

                    <div class="row">

                        <div class="col-12 col-lg-6 mb-3">
                            <label for="horaEntradaInicio">Hora de entrada (Inicio)</label>
                            <input type="datetime-local" id="horaEntradaInicio" name="horaEntradaInicio" class="form-control">
                        </div>

                        <div class="col-12 col-lg-6 mb-3">
                            <label for="horaEntradaFim">Hora de entrada (Fim)</label>
                            <input type="datetime-local" id="horaEntradaFim" name="horaEntradaFim" class="form-control">
                        </div>

                    </div>
                    <button type="submit" class="btn btn-success mt-4 waves-effect">Pesquisar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

$whereCondition = '';
if (isset($_GET['horaEntradaInicio']) && $_GET['horaEntradaInicio'] != '' && $_GET['horaEntradaInicio'] != 'null' && $_GET['horaEntradaInicio'] != null) {
    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $_GET['horaEntradaInicio']);
    $horaEntradaInicio = $dateTime->format('Y-m-d H:i:s');
    $whereCondition .= ' AND HORA_ENTRADA >= \'' . $horaEntradaInicio . '\'';
}

if (isset($_GET['horaEntradaFim']) && $_GET['horaEntradaFim'] != '' && $_GET['horaEntradaFim'] != 'null' && $_GET['horaEntradaFim'] != null) {
    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $_GET['horaEntradaFim']);
    $horaEntradaFim = $dateTime->format('Y-m-d H:i:s');
    $whereCondition .= ' AND HORA_ENTRADA <= \'' . $horaEntradaFim . '\'';
}

// Relatório de tempo médio em minutos
$queryTempoMedio = "SELECT ROUND(AVG(TIMESTAMPDIFF(MINUTE, HORA_ENTRADA, FINALIZADOEM))) AS tempo_medio FROM bd_estacionamentos WHERE ID IS NOT NULL $whereCondition";
$resultTempoMedio = mysqli_query($conexao, $queryTempoMedio);
$rowTempoMedio = mysqli_fetch_assoc($resultTempoMedio);
$tempoMedio = $rowTempoMedio['tempo_medio'];

// Relatório por modelo
$queryPorModelo = "SELECT MODELO, COUNT(*) AS quantidade FROM bd_estacionamentos WHERE ID IS NOT NULL $whereCondition GROUP BY MODELO";
$resultPorModelo = mysqli_query($conexao, $queryPorModelo);
$modelos = [];
$quantidadePorModelo = [];
while ($rowModelo = mysqli_fetch_assoc($resultPorModelo)) {
    $modelos[] = $rowModelo['MODELO'];
    $quantidadePorModelo[] = $rowModelo['quantidade'];
}

// Relatório por dia (considerando o range de tempo 12h-11:59h do dia seguinte)
$queryPorDia = "SELECT DATE_FORMAT(DATE_SUB(HORA_ENTRADA, INTERVAL 12 HOUR), '%Y-%m-%d') AS dia, COUNT(*) AS quantidade 
                FROM bd_estacionamentos WHERE ID IS NOT NULL $whereCondition
                GROUP BY dia";
$resultPorDia = mysqli_query($conexao, $queryPorDia);
$dias = [];
$quantidadePorDia = [];
while ($rowDia = mysqli_fetch_assoc($resultPorDia)) {
    $dias[] = $rowDia['dia'];
    $quantidadePorDia[] = $rowDia['quantidade'];
}

// Relatório por forma de pagamento
$queryPorFormaPagamento = "SELECT FORMA_PAGAMENTO, COUNT(*) AS quantidade FROM bd_estacionamentos WHERE ID IS NOT NULL $whereCondition GROUP BY FORMA_PAGAMENTO";
$resultPorFormaPagamento = mysqli_query($conexao, $queryPorFormaPagamento);
$formasPagamento = [];
$quantidadePorFormaPagamento = [];
while ($rowFormaPagamento = mysqli_fetch_assoc($resultPorFormaPagamento)) {
    $formasPagamento[] = $rowFormaPagamento['FORMA_PAGAMENTO'];
    $quantidadePorFormaPagamento[] = $rowFormaPagamento['quantidade'];
}

// Relatório de valor por dia (considerando o range de tempo 12h-11:59h do dia seguinte)
$queryValorPorDia = "SELECT DATE_FORMAT(DATE_SUB(HORA_ENTRADA, INTERVAL 12 HOUR), '%Y-%m-%d') AS dia, SUM(VALOR) AS total_valor 
                     FROM bd_estacionamentos WHERE ID IS NOT NULL $whereCondition
                     GROUP BY dia";
$resultValorPorDia = mysqli_query($conexao, $queryValorPorDia);
$valorPorDia = [];
while ($rowValorDia = mysqli_fetch_assoc($resultValorPorDia)) {
    $dias[] = $rowValorDia['dia'];
    $valorPorDia[] = $rowValorDia['total_valor'];
}

// Relatório de quantidade de carros por hora
$queryPorHora = "
    SELECT HOUR(HORA_ENTRADA) AS hora, COUNT(*) AS quantidade
    FROM bd_estacionamentos WHERE ID IS NOT NULL $whereCondition
    GROUP BY hora
    ORDER BY hora";
$resultPorHora = mysqli_query($conexao, $queryPorHora);
$horas = [];
$quantidadePorHora = [];
while ($rowHora = mysqli_fetch_assoc($resultPorHora)) {
    $horas[] = $rowHora['hora'] . ':00';  // Exibindo no formato 23:00, 22:00, etc.
    $quantidadePorHora[] = $rowHora['quantidade'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Gráfico de tempo médio
    const ctxTempoMedio = document.getElementById('chartTempoMedio').getContext('2d');
    new Chart(ctxTempoMedio, {
        type: 'bar',
        data: {
            labels: ['Tempo Médio (minutos)'],
            datasets: [{
                label: 'Tempo Médio',
                data: [<?php echo $tempoMedio; ?>],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });

    // Gráfico por modelo
    const ctxPorModelo = document.getElementById('chartPorModelo').getContext('2d');
    new Chart(ctxPorModelo, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($modelos); ?>,
            datasets: [{
                label: 'Quantidade por Modelo',
                data: <?php echo json_encode($quantidadePorModelo); ?>,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        }
    });

    // Gráfico por dia
    const ctxPorDia = document.getElementById('chartPorDia').getContext('2d');
    new Chart(ctxPorDia, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dias); ?>,
            datasets: [{
                label: 'Quantidade por Dia',
                data: <?php echo json_encode($quantidadePorDia); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        }
    });

    // Gráfico por forma de pagamento
    const ctxPorFormaPagamento = document.getElementById('chartPorFormaPagamento').getContext('2d');
    new Chart(ctxPorFormaPagamento, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($formasPagamento); ?>,
            datasets: [{
                label: 'Quantidade por Forma de Pagamento',
                data: <?php echo json_encode($quantidadePorFormaPagamento); ?>,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        }
    });

    // Gráfico de valor por dia
    const ctxValorPorDia = document.getElementById('chartValorPorDia').getContext('2d');
    new Chart(ctxValorPorDia, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($dias); ?>,
            datasets: [{
                label: 'Valor Arrecadado por Dia',
                data: <?php echo json_encode($valorPorDia); ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        }
    });

    // Gráfico de quantidade de carros por hora
    const ctxPorHora = document.getElementById('chartPorHora').getContext('2d');
    new Chart(ctxPorHora, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($horas); ?>,
            datasets: [{
                label: 'Quantidade de Carros por Hora',
                data: <?php echo json_encode($quantidadePorHora); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });
</script>