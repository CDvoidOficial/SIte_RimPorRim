<?php
session_start();
include('php/conexao.php');

if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit();
}

$hora = $_GET['hora'] ?? '';

if (!$hora) {
    exit("Hora inválida.");
}

// Marca a mensagem como excluída
$sql = "UPDATE chat SET excluida = 1 WHERE hora_mandada = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hora);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: chat.php");
exit();
