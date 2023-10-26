<?php
$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=abc123");

function generateRandomData() {
    return array(
        'frequencia_cardiaca' => rand(60, 100),
        'pressao_arterial' => rand(9000, 14000) / 100, // Convertido para formato decimal
        'temperatura' => rand(3500, 4000) / 100, // Convertido para formato decimal
        'saturacao_oxigenio' => rand(9500, 10000) / 100, // Convertido para formato decimal
    );
}

$data_hora_leitura = date('Y-m-d H:i:s');
$dados = generateRandomData();

$sql = "INSERT INTO leituras_sinais_vitais (nome,frequencia_cardiaca, pressao_arterial, temperatura, saturacao_oxigenio, data_hora_leitura) VALUES (:nome,:frequencia_cardiaca, :pressao_arterial, :temperatura, :saturacao_oxigenio, :data_hora_leitura)";
$stmt = $pdo->prepare($sql);
$nomePaciente = $_SESSION['nome'];
$stmt->bindParam(':nome', $nomePaciente, PDO::PARAM_STR);
$stmt->bindParam(':frequencia_cardiaca', $dados['frequencia_cardiaca'], PDO::PARAM_INT);
$stmt->bindParam(':pressao_arterial', $dados['pressao_arterial'], PDO::PARAM_STR);
$stmt->bindParam(':temperatura', $dados['temperatura'], PDO::PARAM_STR);
$stmt->bindParam(':saturacao_oxigenio', $dados['saturacao_oxigenio'], PDO::PARAM_STR);
$stmt->bindParam(':data_hora_leitura', $data_hora_leitura, PDO::PARAM_STR);

if ($stmt->execute()) {

    $sql = "SELECT lastval()";
    $stmt = $pdo->query($sql);
    $nomePaciente = $stmt->fetchColumn();
    
    include('validacao_sinais_vitais.php');

    $avisos = verificarSinaisVitaisForaDoPadrao($pdo,$nomePaciente);

    if (!empty($avisos)) {
        echo "<h3>Avisos:</h3>";
        foreach ($avisos as $aviso) {
            echo "<p>$aviso</p>";
        }
    }

    echo "Paciente cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar paciente: " . $stmt->errorInfo()[2];
}
?>
