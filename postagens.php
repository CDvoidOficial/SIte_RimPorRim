<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['ultima_pagina'] = $_SERVER['REQUEST_URI'];
    header("Location: login.html");
    exit();
}

include('php/conexao.php');

$usuario_id   = $_SESSION['usuario_id'];
$tipo_usuario = strtolower($_SESSION['tipo'] ?? '');
$usuarioLogado = isset($_SESSION['usuario_id']);

// Buscar posts
$sql = "SELECT p.postagem_id, p.usuario_id, p.mensagem, p.hora_mandada, u.nome, u.foto_perfil, u.tipo
        FROM postagens p
        JOIN usuarios u ON p.usuario_id = u.usuario_id
        WHERE p.excluida = 0
        ORDER BY p.hora_mandada DESC";
$resultPosts = $conn->query($sql);

$foto_perfil = "uploads/default.png";

if ($usuarioLogado) {
    $sql = "SELECT foto_perfil FROM usuarios WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultFoto = $stmt->get_result();
    if ($row = $resultFoto->fetch_assoc()) {
        $foto_perfil = $row["foto_perfil"] ?? "uploads/default.png";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fórum</title>
<link rel="stylesheet" href="css/index.css">
<link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet">
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #f0ecf1ff;
  margin: 0;
  padding: 0;
}

main {
  max-width: 800px;
  margin: 30px auto;
  padding: 0 15px;
}

button.btn, .btn {
  background: #653485;
  color: #fff;
  padding: 8px 16px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  transition: 0.2s;
}
button.btn:hover, .btn:hover {
  background: #653485;
}

.search-bar input {
  width: 50%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 6px;
  outline: none;
  font-size: 14px;
}

.post {
  background: #fff;
  border: 2px solid #e9dcea;
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  transition: 0.2s;
}
.post:hover {
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.post-header {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  gap: 10px;
}
.post-header img {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  border: 2px solid #653485;
  object-fit: cover;
}
.post-header .nome {
  font-weight: bold;
  font-size: 15px;
  color: #333;
}

.mensagem {
  font-size: 14px;
  line-height: 1.5;
  color: #444;
  margin-top: 8px;
}

.respostas {
  margin-top: 15px;
  padding-left: 15px;
  border-left: 3px solid #eee;
}
.resposta {
  background: #fafafa;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 10px;
}

.caixa-resposta {
  margin-top: 15px;
}
.caixa-resposta input[type="text"] {
  width: calc(100% - 90px);
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-right: 5px;
}
.caixa-resposta button {
  padding: 5px 5px;
  background: #653485;
  border: none;
  color: #fff;
  border-radius: 6px;
  cursor: pointer;
}
.caixa-resposta button:hover {
  background: #653485;
}

.botao-excluir {
  color: #c0392b;
  font-size: 13px;
  font-weight: bold;
  display: none;
  cursor: pointer;
}
.post.show-delete .botao-excluir {
  display: inline;
}
</style>
</head>
<body>
   <header>
    <nav class="navbar">
      <div class="hamburger" id="menu-button">
  <span></span>
  <span></span>
  <span></span>
</div>

<!-- Menu mobile -->
<ul class="mobile-menu" id="site-menu">
  <li><a href="index.php">Home</a></li>
  <li><a download href="Minha Lancheira Amiga - Ebook.pdf">EBook</a></li>
  <li><a href="#">Contato</a></li>
  <li><a href="postagens.php">Fórum</a></li>
</ul>
      <div class="logo"><a href="index.php"><img src="img/Imagem rim.png" class="img_navbar" alt="Logo"></a></div>
      
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
  <li><a download href="Minha Lancheira Amiga - Ebook.pdf">EBook</a></li>
        <li><a href="#">Contato</a></li>
        <li><a href="postagens.php">Fórum</a></li>
      </ul>
      <div class="botoes">
  <?php if ($usuarioLogado): ?>
<table>
  <tr>
    <th style="vertical-align: middle; padding: 5px;">
      <img src="<?= htmlspecialchars($foto_perfil) ?>" 
           style="width: 40px; height: 40px; border-radius: 50%;
                  border: 2px solid #653485; object-fit: cover;" alt="Foto de perfil">
    </th>
    <th style="vertical-align: middle; padding: 5px;">
      <a href="perfil.php" class="btn perfil">Perfil</a>
    </th>
  </tr>
</table>
  <?php else: ?>
      <a href="login.html" class="btn entrar">Entrar</a>
      <a href="cadastro.html" class="btn cadastrar">Cadastrar</a>
  <?php endif; ?>
</div>

    </nav>
  </header>
  <br>
  <center>
  <div class="search-bar">
    <input type="text" id="pesquisa" placeholder="Pesquisar...">
</div>
  </center>
<br>

<table>
  <tr>
    <th width="80.7%"></th>
    <th><button onclick="location.href='novo_post.php'" class="btn">Perguntar</button></th>
  </tr>
</table>

<main>
<div id="posts-container">
<?php while ($row = $resultPosts->fetch_assoc()): 
    $foto = $row['foto_perfil'] ?? 'uploads/default.png';
    $pode_excluir = ($row['usuario_id'] == $usuario_id || $tipo_usuario === 'profissional');
?>
<div class="post" data-id="<?= $row['postagem_id'] ?>">
    <div class="post-header">
        <img src="<?= htmlspecialchars($foto) ?>" alt="Foto perfil">
        <span class="nome"><?= htmlspecialchars($row['nome']) ?></span>
        <?php if($pode_excluir): ?>
            <span class="botao-excluir" onclick="excluirPost(<?= $row['postagem_id'] ?>)">Excluir</span>
        <?php endif; ?>
    </div>
    <div class="mensagem"><?= nl2br(htmlspecialchars($row['mensagem'])) ?></div>

    <div class="respostas">
    <?php
        $pid = $row['postagem_id'];
        $sql_resposta = "SELECT r.mensagem, u.nome, u.foto_perfil, u.tipo 
                         FROM respostas_postagem r
                         JOIN usuarios u ON r.usuario_id = u.usuario_id
                         WHERE r.postagem_id = ? ORDER BY r.hora_mandada ASC";
        $stmt_res = $conn->prepare($sql_resposta);
        $stmt_res->bind_param("i", $pid);
        $stmt_res->execute();
        $respostas = $stmt_res->get_result();
        while($res = $respostas->fetch_assoc()):
            $foto_r = $res['foto_perfil'] ?? 'uploads/default.png';
    ?>
        <div class="resposta">
            <div class="post-header">
                <img src="<?= htmlspecialchars($foto_r) ?>" alt="Foto perfil">
                <span class="nome"><?= htmlspecialchars($res['nome']) ?></span>
            </div>
            <div class="mensagem"><?= nl2br(htmlspecialchars($res['mensagem'])) ?></div>
        </div>
    <?php endwhile; $stmt_res->close(); ?>
    </div>

    <?php if($tipo_usuario === 'profissional'): ?>
    <div class="caixa-resposta">
        <form action="responder_post.php" method="POST">
            <input type="hidden" name="postagem_id" value="<?= $row['postagem_id'] ?>">
            <input type="text" name="mensagem" placeholder="Responder..." required>
            <button type="submit" class="btn-responder">Responder</button>
        </form>
    </div>
    <?php endif; ?>
</div>
<?php endwhile; ?>
</div>
</main>

<script>
function excluirPost(id){
    if(!confirm("Deseja realmente excluir este post?")) return;

    fetch('php/excluir_postagem.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'postagem_id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
        if(data.sucesso){
            const post = document.querySelector('.post[data-id="'+id+'"]');
            if(post) post.remove();
        } else {
            alert(data.erro || 'Erro ao excluir o post.');
        }
    })
    .catch(err => {
        alert('Erro na requisição.');
        console.error(err);
    });
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

document.getElementById('pesquisa').addEventListener('input', function(){
    const filtro = this.value.toLowerCase();
    document.querySelectorAll('#posts-container .post').forEach(post => {
        const texto = post.querySelector('.mensagem').innerText.toLowerCase();
        const nome = post.querySelector('.nome').innerText.toLowerCase();
        post.style.display = (texto.includes(filtro) || nome.includes(filtro)) ? '' : 'none';
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("menu-button");
    const menu = document.getElementById("site-menu");

    if (!btn || !menu) return;

    // Toggle menu ao clicar no botão
    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        menu.classList.toggle("open");
    });

    // Fecha menu ao clicar fora dele
    document.addEventListener("click", (e) => {
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.remove("open");
        }
    });

    // Fecha com ESC
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") menu.classList.remove("open");
    });

    // Fecha se aumentar a tela
    window.addEventListener("resize", () => {
        if (window.innerWidth > 768) {
            menu.classList.remove("open");
        }
    });
});
</script>
</body>
</html>
