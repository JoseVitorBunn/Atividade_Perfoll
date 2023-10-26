<?php
$host = 'localhost';
$port = 5432;
$dbname = 'postgres';
$user = 'postgres';
$password = 'abc123';

$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");

$nomePaciente = $_POST['nome_paciente'];
$dataInicio = $_POST['data_inicio'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Pesquisa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 800px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Resultado da Pesquisa</h1>
        
        <?php
        $sql = "SELECT * FROM pacientes WHERE nome = :nome ORDER BY id DESC LIMIT 1" ;
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nomePaciente, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo '<table>';
            echo '<tr><th>Nome</th><th>Sexo</th><th>Idade</th><th>Cidade</th></tr>';
        
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pacienteId = $row['id']; // Armazene o ID do paciente
                $pacienteNome = $row['nome'];
                echo '<tr>';
                echo '<td>' . $row['nome'] . '</td>';
                echo '<td>' . $row['sexo'] . '</td>';
                echo '<td>' . $row['idade'] . '</td>';
                echo '<td>' . $row['cidade'] . '</td>';
                echo '</tr>';
            }
        
            echo '</table>';
        
            // Consulta a última leitura de sinais vitais para o paciente encontrado
            $sqlLeituras = "SELECT * FROM leituras_sinais_vitais WHERE nome = :pacienteNome";

            if ($dataInicio) {
                $sqlLeituras .= " AND data_hora_leitura >= :data_inicio";
            }

            $sqlLeituras .= " ORDER BY data_hora_leitura DESC LIMIT 1";
            $stmtLeituras = $pdo->prepare($sqlLeituras);
            $stmtLeituras->bindParam(':pacienteNome', $pacienteNome, PDO::PARAM_STR);
            $stmtLeituras->execute();
        
            if ($stmtLeituras->rowCount() > 0) {
                echo '<h2>Última Leitura de Sinais Vitais</h2>';
                echo '<table>';
                echo '<tr><th>Data/Hora</th><th>Frequência Cardíaca</th><th>Pressão Arterial</th><th>Temperatura</th><th>Saturação de Oxigênio</th></tr>';
        
                $rowLeitura = $stmtLeituras->fetch(PDO::FETCH_ASSOC);
                echo '<tr>';
                echo '<td>' . $rowLeitura['data_hora_leitura'] . '</td>';
                echo '<td>' . $rowLeitura['frequencia_cardiaca'] . '</td>';
                echo '<td>' . $rowLeitura['pressao_arterial'] . '</td>';
                echo '<td>' . $rowLeitura['temperatura'] . '</td>';
                echo '<td>' . $rowLeitura['saturacao_oxigenio'] . '</td>';
                echo '</tr>';
        
                echo '</table>';
            } else {
                if ($dataInicio) {
                    echo 'Nenhuma leitura de sinais vitais encontrada para o paciente na data: ' . $dataInicio;
                } else {
                 echo 'Nenhuma leitura de sinais vitais encontrada para o paciente.';
                }  
            }    
        } else {
            echo 'Nenhum paciente encontrado com o nome: ' . $nomePaciente;
        }
        ?>
    </div>
</body>
</html>
