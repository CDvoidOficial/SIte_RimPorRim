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
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DRC - Doença Renal Crônica</title>
  <link rel="stylesheet" href="CSS/index.css">
  <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet">
  <script src="js/Index.js" defer></script>
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
  <section class="Meio">
  <center>
    <table class="tabelaMeio">
      <tr>
        <th class="escritaDRC">
          <h1>DRC </h1><h2>Doença Renal Crônica</h2>
        </th>
        <th>
          <img src="img/log.png" class="logoIndex" width="80%" height="400px" alt="Imagem principal">
        </th>
      </tr>
    </table>
    <br><br>
    <a href="escolha.php" class="saiba-mais">Saiba mais</a>
    <br><br><br><br><br><br>
  </center>
</section>


  <!-- footer original — será ocultado/remoção no mobile -->
  <footer id="footer">
    <div class="footer-container">
      <div class="footer-box">
        <h3>Assuntos</h3>
        <p>O que é DRC?</p>
        <p>Vídeos</p>
        <p>Receitas</p>
      </div>

      <div class="footer-box">
        <h3>Dúvidas</h3>
        <p href="Post.html">A DRC tem cura?</p>
        <p href="Post.html">Como a DRC é diagnosticada?</p>
        <p href="Post.html">O que é Doença Renal Crônica (DRC)?</p>
      </div>

      <div class="footer-box">
        <h3>Conta</h3>
        <p><a href="login.html">Entrar</a></p>
        <p><a href="cadastro.html">Cadastrar</a></p>
        <p><a href="postagens.php">Fórum</a></p>
      </div>

      <div class="footer-box direita">
        <h3>Contato</h3>
        <p>Telefone: (11) 97684-4202</p>
        <p>Telefone: (11) 96145-5885</p>
        <p>Email: TccDcsNutri@gmail.com</p>
      </div>
    </div>
  </footer>

  <script>
    (function() {
      function removeFooterIfMobile() {
        var footer = document.getElementById('footer');
        if (!footer) return;
        if (window.innerWidth <= 768) {
          // remove do DOM para evitar qualquer interferência de estilos ou espaço em branco
          footer.parentNode && footer.parentNode.removeChild(footer);
        } else {
          // se o footer foi removido e a tela aumentou, recarregar a página para restaurá-lo
          // (alternativa mais simples do que reconstruir o nó)
          // note: isso evita re-criar o footer via JS; pode ser removido se preferir manter somente CSS
          if (!document.getElementById('footer')) {
            window.location.reload();
          }
        }
      }

      window.addEventListener('load', removeFooterIfMobile);
      window.addEventListener('resize', function() {
        // debounce simples
        clearTimeout(window._rfmTimer);
        window._rfmTimer = setTimeout(removeFooterIfMobile, 150);
      });
    })();
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
