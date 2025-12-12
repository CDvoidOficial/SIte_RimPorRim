<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['ultima_pagina'] = $_SERVER['REQUEST_URI'];
    header("Location: login.html");
    exit();
}

include('php/conexao.php');

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = $_POST['mensagem'] ?? '';
    if (!empty(trim($mensagem))) {
        $sql = "INSERT INTO postagens (usuario_id, mensagem) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $usuario_id, $mensagem);
        $stmt->execute();
        $stmt->close();
        header("Location: postagens.php");
        exit();
    } else {
        $erro = "Digite uma mensagem válida.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nova Pergunta</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f6f7f8;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }

    .container {
        background: #fff;
        margin-top: 50px;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        max-width: 600px;
        width: 100%;
    }

    h2 {
        margin-top: 0;
        color: #8e1f32;
        font-size: 22px;
        text-align: center;
    }

    p {
        text-align: center;
    }

    textarea {
        width: 100%;
        padding: 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 8px;
        resize: vertical;
        outline: none;
        transition: border 0.2s;
    }
    textarea:focus {
        border-color: #8e1f32;
    }

    button {
        background: #8e1f32;
        color: #fff;
        padding: 10px 20px;
        font-size: 15px;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 15px;
    }
    button:hover {
        background: #a22942;
    }

    a {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        color: #8e1f32;
        font-weight: bold;
        transition: 0.2s;
    }
    a:hover {
        color: #a22942;
    }

    .erro {
        color: red;
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Fazer uma pergunta</h2>
    <?php if(isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
    <form method="POST">
        <textarea name="mensagem" rows="5" cols="50" placeholder="Digite sua pergunta..." required></textarea><br>
        <button type="submit">Postar</button>
    </form>
    <p><a href="postagens.php">Voltar</a></p>
</div>
</body>
</html>
