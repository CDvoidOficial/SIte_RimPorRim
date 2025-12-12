<?php
session_start();
$usuarioLogado = isset($_SESSION['usuario_id']);
include("Php/conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['ultima_pagina'] = $_SERVER['REQUEST_URI'];  
    header("Location: login.html");
    exit;
}

// Dados do usuário
$usuario_id = $_SESSION['usuario_id'];
$nome       = $_SESSION['nome'];
$email      = $_SESSION['email'];
$tipo       = $_SESSION['tipo'];
$fotoPerfil = $_SESSION['foto_perfil'] ?? "uploads/default.png";

// Cria pasta uploads se não existir
$diretorio = "uploads/";
if (!is_dir($diretorio)) mkdir($diretorio, 0777, true);

// Upload de foto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $arquivo = $_FILES['foto'];
    if ($arquivo['error'] == 0) {
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $novoNome = $usuario_id . "." . $extensao;
        $caminho = $diretorio . $novoNome;

        if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
            $sql = "UPDATE usuarios SET foto_perfil = ? WHERE usuario_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $caminho, $usuario_id);
            $stmt->execute();
            $stmt->close();

            $_SESSION['foto_perfil'] = $caminho;
            $fotoPerfil = $caminho;
            $mensagem = "Foto de perfil atualizada!";
        } else {
            $mensagem = "Erro ao mover o arquivo.";
        }
    } else {
        $mensagem = "Erro no upload.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f3f4f6 0%, #e2e2e2 100%);
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    max-width: 700px;
    margin: 50px auto;
    background: #fff;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

h1 {
    color: #653485;
    font-size: 2.2em;
    margin-bottom: 15px;
}

.info {
    margin: 8px 0;
    font-size: 16px;
    color: #555;
}

.profile-pic {
    margin: 25px 0;
}

.profile-pic img {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    border: 5px solid #653485;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.profile-pic img:hover {
    transform: scale(1.05);
}

form {
    margin-top: 20px;
}

input[type="file"] {
    display: none;
}

.custom-file-upload {
    display: inline-block;
    padding: 12px 25px;
    cursor: pointer;
    border-radius: 10px;
    background-color: #653485;
    color: #fff;
    font-weight: bold;
    transition: all 0.3s ease;
}

.custom-file-upload:hover {
    background-color: #45235cff;
    transform: scale(1.05);
}

button {
    background: #653485;
    color: #fff;
    border: none;
    padding: 12px 25px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: background 0.3s ease, transform 0.3s ease;
    margin-top: 10px;
}

button:hover {
    background: #45235cff;
    transform: scale(1.05);
}

.links {
    margin-top: 30px;
}

.links a {
    text-decoration: none;
    color: #653485;
    margin: 0 15px;
    font-weight: 600;
    transition: color 0.3s ease;
}

.links a:hover {
    color: #45235cff;
}

.mensagem {
    margin-top: 15px;
    color: green;
    font-weight: bold;
    font-size: 15px;
}

.post.show-delete::after {
    content: "🗑️ Clique para excluir";
    display: block;
    background: #fee2e2;
    color: #b91c1c;
    padding: 6px;
    margin-top: 8px;
    border-radius: 6px;
    font-size: 13px;
}

#posts-container .post {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    transition: box-shadow 0.3s ease;
}

#posts-container .post:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

    </style>
  

</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?= htmlspecialchars($nome) ?>!</h1>
        <div class="info">Email: <?= htmlspecialchars($email) ?></div>
        <div class="info">Conta de <?= htmlspecialchars($tipo) ?></div>

       

       <form method="POST" enctype="multipart/form-data">
         <div class="profile-pic">
            <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil">
        </div>
    <label class="custom-file-upload">
        Escolher Foto
        <input type="file" name="foto" accept="image/*" required>
    </label>
    <br>
    <button type="submit">Atualizar Foto</button>
</form>


        <?php if (isset($mensagem)) echo "<p class='mensagem'>$mensagem</p>"; ?>

        <div class="links">
            <a href="index.php">Voltar</a> | 
            <a href="logout.php">Sair</a>
        </div>
    </div>

<script>
function excluirPost(id){
    if(confirm("Deseja realmente excluir este post?")){
        fetch('excluir_postagem.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'postagem_id=' + id
        })
        .then(r => r.json())
        .then(data => {
            if(data.status === 'ok'){
                const post = document.querySelector('.post[data-id="'+id+'"]');
                if(post) post.remove();
            } else {
                alert('Erro: ' + data.mensagem);
            }
        })
        .catch(err => alert('Erro na requisição: ' + err));
    }
}

document.querySelectorAll('.post').forEach(post => {
    post.addEventListener('contextmenu', e => {
        e.preventDefault();
        document.querySelectorAll('.post').forEach(p => {
            if(p !== post) p.classList.remove('show-delete');
        });
        post.classList.toggle('show-delete');
    });
});

document.getElementById('pesquisa')?.addEventListener('input', function(){
    const filtro = this.value.toLowerCase();
    document.querySelectorAll('#posts-container .post').forEach(post => {
        const texto = post.querySelector('.mensagem').innerText.toLowerCase();
        const nome = post.querySelector('.nome').innerText.toLowerCase();
        post.style.display = (texto.includes(filtro) || nome.includes(filtro)) ? '' : 'none';
    });
});
</script>
</body>
</html>
