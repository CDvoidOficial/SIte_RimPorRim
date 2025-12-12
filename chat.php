<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  $_SESSION['ultima_pagina'] = $_SERVER['REQUEST_URI'];
  header("Location: login.html");
  exit();

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
}

include('php/conexao.php');

// Buscar mensagens
$sql = "SELECT c.mensagem, c.hora_mandada, u.nome, u.tipo
        FROM chat c
        JOIN usuarios u ON c.usuario_id = u.usuario_id
        WHERE c.excluida = 0
        ORDER BY c.hora_mandada ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat</title>
  <link rel="stylesheet" href="css/index.css">
  <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet">
</head>
<body>
   <header>
    <nav class="navbar">
      <div class="logo"><a href="index.php"><img src="img/Imagem rim.png" class="img_navbar"></a></div> <!-- logo profisorio -->
      
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a download href="teste.txt">EBook</a></li>
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
                  border: 2px solid #8e1f32; object-fit: cover;">
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
  <main class="chat-container">
    <h2 class="chat-titulo">CHAT</h2>

    <div class="caixa-chat" id="caixa-chat">
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php
          $sou_eu = ($row['nome'] === $_SESSION['usuario']['nome']);
          $classe_lado = $sou_eu ? 'direita' : 'esquerda';
          $classe_profissional = (strtolower($row['tipo']) === 'profissional') ? 'profissional' : '';
          $usuario_logado = $_SESSION['usuario'];
          $pode_excluir = (
            strtolower($usuario_logado['tipo']) === 'profissional' ||
            $row['nome'] === $usuario_logado['nome']
          );
        ?>
        <div class="mensagem <?php echo $classe_lado; ?>" data-hora="<?php echo $row['hora_mandada']; ?>">
          <div class="conteudo-mensagem">
            <p class="nome <?php echo $classe_profissional; ?>">
              <?php echo htmlspecialchars($row['nome']); ?>
            </p>
            <div class="bolha">
              <?php echo htmlspecialchars($row['mensagem']); ?>
            </div>
            <?php if ($pode_excluir): ?>
              <button class="btn-excluir" onclick="excluirMensagem('<?php echo $row['hora_mandada']; ?>')">Excluir</button>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <form action="enviar_mensagem.php" method="POST" class="form-chat">
      <input type="text" name="mensagem" placeholder="Digite sua mensagem..." required>
      <button alt="enviar" type="submit">
        <img src="img/send_icon.png" alt="Enviar" height="25">
      </button>
    </form>
  </main>

  <script>
  
  // Rolar para o final ao carregar
  window.addEventListener('load', function () {
    var chatBox = document.getElementById('caixa-chat');
    chatBox.scrollTop = chatBox.scrollHeight;
  });

  // Mostrar botão de excluir com clique direito (botão direito do mouse)
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.mensagem').forEach(msg => {
      msg.addEventListener('contextmenu', (e) => {
        e.preventDefault(); // impede o menu do navegador
        document.querySelectorAll('.mensagem').forEach(m => {
          if (m !== msg) m.classList.remove('show-delete');
        });
        msg.classList.toggle('show-delete');
      });
    });
  });

  // Função de exclusão
  function excluirMensagem(horaMandada) {
    if (confirm("Deseja realmente excluir esta mensagem?")) {
      window.location.href = 'excluir_mensagem.php?hora=' + encodeURIComponent(horaMandada);
    }
  }




  </script>
</body>
</html>
