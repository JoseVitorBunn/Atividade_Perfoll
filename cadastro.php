<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Pacientes</title>
</head>
<body>
    <h2>Cadastro de Pacientes</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $host = 'localhost';
        $port = 5432;
        $dbname = 'postgres';
        $user = 'postgres';
        $password = 'abc123';

        try {
            $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
        } catch (PDOException $e) {
            die("Erro Teste na conexão com o banco de dados: " . $e->getMessage());
        }

        $nome = $_POST['nome'];
        $sexo = $_POST['sexo'];
        $idade = $_POST['idade'];
        $cidade = $_POST['cidade'];

        $sql = "INSERT INTO pacientes (nome, sexo, idade, cidade) VALUES (:nome, :sexo, :idade, :cidade)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':sexo', $sexo, PDO::PARAM_STR);
        $stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
        $stmt->bindParam(':cidade', $cidade, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $_SESSION['nome'] = $nome;
            include('cadastro_info.php');
            header("refresh:2;url=teste.html");
        } else {
            echo "Erro ao registrar leitura de sinais vitais: " . $stmt->errorInfo()[2];
        }
    }
    ?>
</body>
</html>
