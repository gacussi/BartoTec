<?php

$servername = "localhost"; // Endereço do servidor MySQL
$username = "root";        // Usuário do banco de dados
$password = "";        // Senha do usuário
$dbname = "spfw_db";            // Nome do banco de dados

try {
    // Conexão com o banco de dados
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Limpeza e captura dos dados do formulário
    $nome = htmlspecialchars(trim($_POST['nome']));
    $email = htmlspecialchars(trim($_POST['email']));
    $mensagem = htmlspecialchars(trim($_POST['mensagem']));

    // Validação de campos
    if (!empty($nome) && !empty($email) && !empty($mensagem)) {

        // Validação de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Por favor, insira um email válido.'); window.history.back();</script>";
            exit;
        }

        try {
            // Inserir dados na tabela 'contato'
            $sql = "INSERT INTO contato (nome, email, mensagem) VALUES (:nome, :email, :mensagem)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mensagem', $mensagem);
            $stmt->execute();

            echo "<script>alert('Mensagem enviada com sucesso!'); window.location.href='contato.html';</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao enviar a mensagem.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.history.back();</script>";
    }
}

// Fecha a conexão
$conn = null;
?>
