<?php
$servidor = "localhost";
$usuario = "root";
$senha = ""; // Se você não tem senha no XAMPP deixe vazio
$dbname = "chat_drc"; // Nome do banco de dados

// Criar conexão
$conn = new mysqli($servidor, $usuario, $senha, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
