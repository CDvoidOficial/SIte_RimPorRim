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
  <title>Área de Jogos</title>
  <link rel="stylesheet" href="css/index.css">
  <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet">
  <style>
    .area-jogos {
      background: radial-gradient(circle at center, #ffeaea 0%, #ffd6d6 40%, #ffb3b3 100%);
      min-height: 92.3vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 80px 20px;
    }

    .area-jogos .conteudo {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 50px;
      max-width: 1100px;
      width: 100%;
      flex-wrap: nowrap;
      background-color: #ff7b7b;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 5px 15px rgba(255, 100, 100, 0.3);
    }

    .area-jogos .texto h1 {
      font-size: 60px;
      font-weight: bold;
      text-shadow: 2px 2px #990000;
      margin-bottom: 20px;
      text-align: center; 
    }

    .area-jogos .texto p {
      font-size: 22px;
      line-height: 1.5;
    }

    .area-jogos .texto strong {
      color: #ff0000;
    }

    .area-jogos .btn-jogar {
      display: inline-block;
      background-color: #ff4d4d;
      color: #fff;
      font-size: 24px;
      font-weight: bold;
      padding: 16px 45px;
      border-radius: 40px;
      margin-top: 30px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(204, 0, 0, 0.5);
      text-align: center;
    }

    .area-jogos .btn-jogar:hover {
      background-color: #e63939;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(204, 0, 0, 0.7);
    }

    .area-jogos .menino {
      width: 400px;
      height: auto;
      filter: drop-shadow(0 5px 10px rgba(255, 100, 100, 0.5));
      flex-shrink: 0;
    }

    .area-jogos .texto {
      background-color: #ff8585;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 0 15px rgba(255, 150, 150, 0.5);
      max-width: 600px;
      font-family: 'Arial Rounded MT Bold', sans-serif;
      color: #fff;
      flex-shrink: 0;
      text-align: justify;
    }

    @media (max-width: 768px) {
      .area-jogos {
        min-height: 60vh; /* Reduz altura da área de jogos no celular */
        padding: 40px 20px; /* Reduz padding */
      }

      .area-jogos .conteudo {
        flex-direction: column-reverse;
        text-align: center;
        padding: 20px; /* Ajusta padding interno */
      }

      .area-jogos .menino {
        display: none; /* Esconde imagem no celular */
      }

      .area-jogos .texto {
        text-align: justify;
      }

      .area-jogos .texto h1 {
        font-size: 40px; /* Reduz título no celular */
      }

      .area-jogos .texto p {
        font-size: 18px; /* Reduz texto no celular */
      }

      .area-jogos .btn-jogar {
        font-size: 20px;
        padding: 12px 35px;
      }
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

  <section class="area-jogos">
    <div class="conteudo">
      <div class="texto">
        <h1>Área de Jogos</h1>
        <p>
          Bem-vindo à nossa área interativa! Aqui você poderá aprender sobre <strong>saúde renal</strong> enquanto se diverte com jogos educativos.
          <br><br>
          Explore, descubra e aprenda de um jeito leve e divertido!
        </p>
        <a href="KidGames.php" class="btn-jogar">Ir para os Jogos</a>
      </div>

      <img class="menino" src="img/Crianca_lascada.png" alt="Menino personagem">
    </div>
  </section>

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
