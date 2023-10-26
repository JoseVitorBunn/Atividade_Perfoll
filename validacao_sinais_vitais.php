<?php
$host = 'localhost';
$port = 5432;
$dbname = 'postgres';
$user = 'postgres';
$password = 'abc123';

$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");

function verificarSinaisVitaisForaDoPadrao($pdo, $nomePaciente) {
    $avisos = array();

    $sql = "SELECT * FROM leituras_sinais_vitais WHERE nome = :nomePaciente";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nomePaciente', $nomePaciente, PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $frequencia_cardiaca = $row['frequencia_cardiaca'];
        $pressao_arterial = $row['pressao_arterial'];
        $temperatura = $row['temperatura'];
        $saturacao_oxigenio = $row['saturacao_oxigenio'];

        if ($frequencia_cardiaca < 60 || $frequencia_cardiaca > 100) {
            $avisos[] = "Aviso: Frequência cardíaca fora do padrão para o paciente.";
        }

        if (!verificarPressaoArterial($pressao_arterial)) {
            $avisos[] = "Aviso: Pressão arterial fora do padrão para o paciente.";
        }
    }

    return $avisos;
}

function verificarPressaoArterial($pressao_arterial) {

    list($sistolica, $diastolica) = explode('/', $pressao_arterial);

    $sistolica = (int)$sistolica;
    $diastolica = (int)$diastolica;

    $sistolicaNormal = ($sistolica >= 90 && $sistolica <= 120);
    $diastolicaNormal = ($diastolica >= 60 && $diastolica <= 80);

    if ($sistolicaNormal && $diastolicaNormal) {
        return true;
    } else {
        return false;
    }
}

$paciente_id = 1;

$avisos = verificarSinaisVitaisForaDoPadrao($pdo, $nomePaciente);

echo json_encode($avisos);
?>
