<?php
session_start();
$usuarioLogado = isset($_SESSION['usuario_id']);

$foto_perfil = "uploads/default.png"; // foto padrão

if ($usuarioLogado) {
    include("php/conexao.php");

    $usuario_id = $_SESSION['usuario_id'];
    $sql = "SELECT foto_perfil FROM usuarios WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $foto_perfil = $row["foto_perfil"] ?? "uploads/default.png";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
    <style>
@media (max-width: 768px) {
    .imagem {
        display: none;
    }

    .content {
        display: flex;
        flex-direction: column;      /* empilha elementos verticalmente */
        align-items: center;         /* centraliza horizontalmente */
        justify-content: center;     /* centraliza verticalmente se houver altura definida */
        text-align: center;          /* centraliza o texto */
        margin-top: 50px;            /* distância do topo */
        padding: 0 10px;
    }

    .content h1 {
        font-size: 40px !important; /* diminui título */
        line-height: 1.3;
        margin-bottom: 10px;
    }

    .content p {
        font-size: 1rem !important;
        margin-bottom: 20px;
    }

    .content > div {                 /* o container dos botões */
        display: flex;
        flex-direction: column;       /* empilha os botões */
        gap: 10px;                    /* espaço entre eles */
        width: 100%;                  /* ocupa largura total do conteúdo */
        max-width: 250px;             /* limita largura dos botões */
    }

    .content .btnpro {
        width: 100%;                  /* faz os botões ocuparem toda a largura do container */
        padding: 10px 0;
        font-size: 0.95rem;
    }

    
}


    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área dos Profissionais</title>
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body style="background-image: url('img/outroroxo.jpg'); background-size: cover;">
    
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

<div class="content">
    <h1>Compartilhe seu<br>Conhecimento!</h1>
    <p>Venha fazer parte dessa equipe.<br>Se prepare para conscientizar!</p>
    <div style="display: flex; gap: 10px;"> <!-- container para os botões lado a lado -->
        <a href="postagens.php" class="btnpro">Acesse o fórum</a>
        <a href="verificar_conta.php" class="btnpro">Verificar sua conta profissional</a>
    </div>
</div>

<img src="img/medicodesenho.png" class="imagem">

<script>
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("menu-button");
    const menu = document.getElementById("site-menu");

    if (!btn || !menu) return;

    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        menu.classList.toggle("open");
    });

    document.addEventListener("click", (e) => {
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.remove("open");
        }
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") menu.classList.remove("open");
    });

    window.addEventListener("resize", () => {
        if (window.innerWidth > 768) {
            menu.classList.remove("open");
        }
    });
});
</script>
</body>
</html>
