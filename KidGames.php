<?php
session_start();
$usuarioLogado = isset($_SESSION['usuario_id']);

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
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jogos Educativos</title>
  <link rel="stylesheet" href="css/index.css">
  <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet">

  <style>
    body.pagina-jogos {
      font-family: 'Arial Rounded MT Bold', sans-serif;
      margin: 0;
      padding: 0;
      background: #ffdddd; /* fundo da página */
      color: #3b0000;
    }

    .container-geral {
      display: flex;
      min-height: calc(100vh - 70px);
      overflow: hidden;
    }

    /* SIDEBAR - fundo mais escuro, cantos retos */
    .sidebar {
      width: 220px;
      background: linear-gradient(to bottom, #cc0000, #990000);
      padding: 25px 15px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
      border-radius: 0; /* sem arredondamento no fundo */
    }

    .sidebar button {
      padding: 14px 10px;
      font-size: 16px;
      border: none;
      border-radius: 12px; /* os botões continuam arredondados */
      background: linear-gradient(145deg, #ff6666, #cc0000);
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
    }

    .sidebar button:hover {
      background: linear-gradient(145deg, #ff4d4d, #990000);
      transform: translateY(-2px);
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    }

    .sidebar button.active {
      background: linear-gradient(145deg, #660000, #990000);
      color: #fff;
      box-shadow: 0 5px 12px rgba(0, 0, 0, 0.4);
    }

    /* CONTEÚDO PRINCIPAL - fundo reto */
    .conteudo {
      flex: 1;
      background: #ffcccc; /* tom suave */
      padding: 25px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-left: 1px solid #cc0000;
      border-radius: 0; /* sem arredondamento no fundo */
    }

    .conteudo iframe {
      width: 100%;
      max-width: 900px;
      height: 650px;
      border: none;
      border-radius: 12px; /* mantém o arredondamento do iframe */
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      background-color: #fff;
    }

    /* Responsividade */
    @media (max-width: 900px) {
      .container-geral {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        flex-direction: row;
        justify-content: space-around;
        padding: 15px;
      }

      .sidebar button {
        flex: 1;
        max-width: 140px;
        padding: 12px 5px;
      }

      .conteudo {
        width: 95%;
        margin: 15px auto 0 auto;
        border-left: none;
        border-radius: 0; /* fundo reto também em mobile */
      }
    }
  </style>

  <script>
    function mostrarJogo(tipo) {
      const conteudo = document.getElementById("conteudo");
      const botoes = document.querySelectorAll(".sidebar button");
      botoes.forEach(btn => btn.classList.remove("active"));
      document.getElementById("btn-" + tipo).classList.add("active");

      let html = "";
      if (tipo === "jogo1") {
        html = `<iframe src="jogos/TCC_Pou/index.html"></iframe>`;
      } else if (tipo === "jogo2") {
        html = `<iframe src="jogos/TCC_Receita/index.html"></iframe>`;
      } else if (tipo === "jogo3") {
        html = `<iframe src=""></iframe>`;
      }

      conteudo.innerHTML = html;
    }

    window.onload = () => {
      mostrarJogo("jogo1");
    };
  </script>
</head>

<body class="pagina-jogos">
  <!-- HEADER ORIGINAL -->
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

  <!-- CONTAINER PRINCIPAL -->
  <div class="container-geral">
    <div class="sidebar">
      <button id="btn-jogo1" onclick="mostrarJogo('jogo1')">Jogo 1</button>
      <button id="btn-jogo2" onclick="mostrarJogo('jogo2')">Jogo 2</button>
      <button id="btn-jogo3" onclick="mostrarJogo('jogo3')">Jogo 3</button>
    </div>
    <div class="conteudo" id="conteudo"></div>
  </div>
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
