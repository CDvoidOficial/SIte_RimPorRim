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
    <title>Escolha a Página</title>
    <link rel="stylesheet" href="css/Index.css"> <!-- Você pode renomear para css/Escolha.css se quiser -->
    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet">
</head>
<body style="background-color: #e9dcea;">

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

<section class="Meio_escolha" style="height: auto; padding-top: 50px; text-align: center;">
    <h1 style="font-family: 'Berkshire Swash', cursive; font-size: 50px; color: black;">Escolha a página</h1>

    <div style="display: flex; justify-content: center; gap: 50px; margin-top: 50px; flex-wrap: wrap;">
        <a href="criança.php" class="btn_infantil button">
            <span style="font-size: 40px; font-family: 'Berkshire Swash', cursive;">Jogos educativos</span>
        </a>

        <a href="pagina_adulto.php" class="btn_adulto button">
            <span style="font-size: 40px; font-family: 'Berkshire Swash', cursive;">Responsável</span>
        </a>

        <a href="pagina_profissional.php" class="btn_profissional button">
            <span style="font-size: 40px; font-family: 'Berkshire Swash', cursive;">Profissional</span>
        </a>
    </div>
</section>
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
