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
<title>Área dos Adultos</title>
<link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/index.css">
<style>
/* reset box-sizing útil */
* { box-sizing: border-box; }

/* Wrapper centralizador */
.grid-wrap {
  text-align: center;
  padding: 24px 16px;
}

/* Título */
.grid-wrap h2 {
  margin: 0 0 20px;
  font-size: 20px;
  font-weight: 700;
  color: #1f2d3d;
}

/* Grid centralizada */
.grid {
  display: inline-grid;
  grid-template-columns: repeat(2, auto);
  gap: 32px;
  width: auto;
  vertical-align: top;
}

/* Card da receita */
.receita {
  text-align: center;
}

/* Imagens menores (ajuste pedido) */
.receita img {
  width: 220px;
  height: 220px;
  object-fit: cover;
  border-radius: 12px;
  border: 6px solid #fff;
  box-shadow: 0 6px 18px rgba(0,0,0,0.25);
  margin: 0 auto 16px;
  display: block;
}

/* Legenda (centralizada) */
.receita p {
  font-size: 15px;
  font-weight: 700;
  text-align: justify;
  color: #2c3e50;
  margin: 0;
  letter-spacing: 0.5px;
}

/* Conteúdo da receita oculto por padrão */
.conteudo_receitas {
  display: none;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 12px;
  padding: 20px;
  box-sizing: border-box;
}

/* ------------------------------------------------------------------
   Quando uma receita está aberta (:target)
   - layout em linha (row) FORÇADO para todas as larguras
   - imagem à esquerda, descrição à direita
   ------------------------------------------------------------------ */
.receita:target {
  display: flex !important;
  flex-direction: row;
  align-items: flex-start;
  gap: 24px;
  width: 100vw;              /* agora ocupa toda a tela */
  max-width: 100vw;          /* impede limites do grid */
  position: fixed;           /* fica sobre tudo */
  top: 0;
  left: 0;
  height: 100vh;             /* ocupa a tela inteira */
  background: #ffffff;
  padding: 32px;
  overflow-y: auto;          /* permite rolagem interna */
  z-index: 9999;
}


/* Área esquerda (link contendo imagem + legenda) */
.receita:target > a {
  display: block;
  flex: 0 0 320px;   /* largura fixa/limitada para a coluna da imagem */
  max-width: 40%;
  box-sizing: border-box;
}

/* imagem responsiva quando aberto */
.receita:target img {
  width: 100%;
  height: auto;
  max-height: 320px;
  object-fit: cover;
  display: block;
  border-radius: 12px;
}

/* Conteúdo aberto (à direita) */
.receita:target .conteudo_receitas {
  display: block;    /* torna visível quando em :target */
  flex: 1 1 auto;    /* ocupa o restante do espaço */
  text-align: left;
  padding: 24px;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  box-sizing: border-box;
}

/* Título interno */
.conteudo_receitas h3 {
  margin-top: 0;
  margin-bottom: 12px;
  font-size: 22px;
  color: #1f2d3d;
}

/* Texto */
.conteudo_receitas p {
  font-size: 16px;
  line-height: 1.6;
  color: #444;
  margin-bottom: 16px;
}

/* Botão voltar */
.voltar {
  display: inline-block;
  padding: 10px 14px;
  background: #0077cc;
  color: #fff;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
}

.voltar:hover {
  background: #005fa3;
}

/* Ao abrir uma receita, esconde as demais (mantém comportamento atual) */
.grid:has(.receita:target) .receita:not(:target) {
  display: none;
}

/* Responsividade – ajustes de tamanhos, mas mantendo descrição à direita */
@media (max-width: 820px) {

  .grid {
    grid-template-columns: repeat(1, auto);
    gap: 24px;
  }

  /* tamanho menor das imagens no grid (quando não aberto) */
  .receita img {
    width: 200px;
    height: 200px;
  }

  /* Ao abrir, mantemos layout em row (descrição à direita), mas reduzimos larguras */
  .receita:target {
    gap: 16px;
  }

  .receita:target > a {
    flex: 0 0 180px;   /* coluna da imagem menor em telas pequenas */
    max-width: 35%;
  }

  .receita:target img {
    max-height: 220px;
  }

  .receita:target .conteudo_receitas {
    max-width: 65%;
    padding: 16px;
  }

  /* Conteúdo padrão continua ocupando largura total quando não aberto */
  .conteudo_receitas {
    text-align: left;
    width: 100%;
  }

  
}

/* Esconde a legenda abaixo da imagem quando a receita está aberta */
.receita:target > a p {
  display: none;
}


.conteudo {
  max-width: 800px;       /* largura máxima */
  margin: 0 auto;         /* centraliza horizontalmente */
  padding: 20px;          /* espaço interno */
  background: #fff;       /* fundo branco */
  border-radius: 8px;     /* cantos arredondados */
  box-shadow: 0 2px 8px rgba(0,0,0,0.1); /* leve sombra */
}
 h2 {
  font-size: 28px;
  font-weight: bold;
  color: #2c3e50;          /* tom escuro elegante */
  text-align: center;      /* centraliza o título */
  margin-bottom: 20px;
  font-family: "Segoe UI", Arial, sans-serif;
  border-bottom: 2px solid #0077cc; /* linha decorativa */
  padding-bottom: 8px;
}

p {
  font-size: 18px;
  line-height: 1.6; 
  margin-bottom: 16px;
  font-family: "Segoe UI", Arial, sans-serif;
  text-align: justify; 
}


.videos {
  display: flex;
  flex-wrap: wrap;      /* permite quebrar no celular */
  justify-content: center;
  gap: 40px;
  padding: 20px;
}

.videos video {
  width: 100%;
  max-width: 300px;     /* controla tamanho no desktop */
  height: auto;
  border-radius: 8px;   /* opcional */
}
.videos h3 {
  font-size: 20px;          /* tamanho da fonte */
  font-weight: bold;        /* deixa em negrito */
  color: #444;              /* cor do texto */
  text-align: center;       /* centraliza */
  margin-bottom: 12px;      /* espaço entre título e vídeo */
  font-family: "Segoe UI", Arial, sans-serif; /* fonte mais moderna */
  background: #f0f0f0;      /* fundo suave */
  padding: 6px 10px;        /* espaço interno */
  border-radius: 6px;       /* cantos arredondados */
  box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* leve sombra */
  transition: transform 0.2s ease, color 0.2s ease;
}
/* ===== LAYOUT GERAL ===== */
.adultos-page .area-jogos {
  display: flex;
  min-height: calc(100vh - 70px);
  background: #e0f0ff;
  font-family: 'Arial Rounded MT Bold', sans-serif;
  color: #00264d;
}

/* SIDEBAR */
.adultos-page .sidebar-jogos {
  width: 220px;
  background: #004080;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.adultos-page .sidebar-jogos button {
  padding: 12px 10px;
  font-size: 16px;
  border: none;
  border-radius: 12px;
  background: #3399ff;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 3px 6px rgba(0,0,0,0.2);
}

.adultos-page .sidebar-jogos button:hover {
  background: #66b3ff;
  transform: translateY(-2px);
  box-shadow: 0 5px 10px rgba(0,0,0,0.3);
}

.adultos-page .sidebar-jogos button.active {
  background: #00264d;
  box-shadow: 0 5px 12px rgba(0,0,0,0.4);
}

/* CONTEÚDO GERAL */
.adultos-page .conteudo-jogos {
  flex: 1;
  background: #cce0ff;
  padding: 25px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.adultos-page .conteudo-jogos h2 {
  margin-top: 0;
  color: #00264d;
}

/* ===== ITENS PADRÃO (DRC / VÍDEOS) ===== */
.adultos-page .conteudo-jogos .item {
  display: flex;
  align-items: center;
  gap: 15px;
  background: #e6f0ff;
  padding: 12px;
  border-radius: 12px;
}

.adultos-page .conteudo-jogos .item img {
  width: 50px;
  height: 50px;
  object-fit: cover;
}

/* ===== ESTILO EXCLUSIVO PARA RECEITAS ===== */
.adultos-page .conteudo-jogos.receitas-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 25px;
}

/* >>>> NOVO ESTILO PARA O TÍTULO DE RECEITAS <<<< */
.adultos-page .conteudo-jogos.receitas-grid h2 {
  grid-column: 1 / -1;              /* ocupa toda a largura da grade */
  text-align: center;               /* centraliza o texto */
  font-size: 28px;                  /* aumenta o tamanho */
  font-weight: bold;                /* deixa mais forte */
  color: #001a33;                   /* tom mais escuro para contraste */
  text-shadow: 1px 1px 2px rgba(0,0,0,0.3); /* leve sombra */
  margin-bottom: 10px;
}

.adultos-page .conteudo-jogos.receitas-grid .item {
  display: flex;
  flex-direction: column; /* texto vai pra baixo */
  align-items: center;
  background: #f8fbff;
  border-radius: 16px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  text-align: center;
  padding: 10px;
}

.adultos-page .conteudo-jogos.receitas-grid .item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 18px rgba(0,0,0,0.25);
}

/* >>>> IMAGENS MAIORES <<<< */
.adultos-page .conteudo-jogos.receitas-grid .item img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  border-radius: 10px;
}

.adultos-page .conteudo-jogos.receitas-grid .item p {
  margin-top: 10px;
  font-size: 18px;
  color: #003366;
  font-weight: 600;
}

/* RESPONSIVO */
@media (max-width: 900px) {
  .adultos-page .area-jogos {
    flex-direction: column;
  }

  .adultos-page .sidebar-jogos {
    width: 100%;
    flex-direction: row;
    justify-content: space-around;
    padding: 15px;
  }

  .adultos-page .sidebar-jogos button {
    flex: 1;
    max-width: 140px;
    padding: 10px;
  }

  .adultos-page .conteudo-jogos.receitas-grid h2 {
    font-size: 24px;
  }
}
</style>

<script>
function mostrarConteudo(tipo) {
  const conteudo = document.getElementById("conteudo-jogos");
  const botoes = document.querySelectorAll(".adultos-page .sidebar-jogos button");
  botoes.forEach(btn => btn.classList.remove("active"));
  document.getElementById("btn-" + tipo)?.classList.add("active");

  conteudo.className = "conteudo-jogos";

  if(tipo === "drc") {
    conteudo.innerHTML = `
    <div class="conteudo">
      <h2>O que é DRC</h2>
      <p>A Doença Renal Crônica (DRC) acontece quando os rins vão perdendo, lentamente, a capacidade de filtrar o sangue. O que isso significa? Significa que substâncias que deveriam ser eliminadas acabam ficando no organismo, o que pode trazer vários problemas de saúde. O grande desafio é que no começo a doença costuma ser silenciosa, sem sintomas claros, o que dificulta a percepção de que algo está errado.
    <p>Com o avanço da DRC, começam a surgir sinais como inchaço nas pernas, pressão alta, cansaço frequente e mudanças na urina. Esses sintomas aparecem porque os rins já não conseguem trabalhar como deveriam. É por isso que fazer exames de sangue e urina feitos regularmente são fundamentais, principalmente para quem tem fatores de risco como diabetes, hipertensão ou histórico familiar.
    <p>A melhor forma de conter o avanço da doença é cuidar bem da saúde no dia a dia. Isso inclui controlar a pressão arterial e o diabetes, beber água na medida certa, manter uma alimentação equilibrada e evitar excesso de sal. Além disso, o acompanhamento médico é indispensável, já que quanto mais cedo a DRC é identificada, maiores são as chances de prevenir complicações e garantir qualidade de vida.
      </div>
    `;
 
  } else if(tipo === "receitas") {
    conteudo.classList.add("receitas-grid");
    conteudo.innerHTML = `
<div class="grid-wrap">
  <h2>Receitas saudáveis</h2><p>  Para ver mais verifique o Ebook</p>

  <div class="grid">

    <div id="receita1" class="receita">
      <a href="#receita1">
        <img src="img/Img_Miojo.jpg" alt="Miojo saudável">
        <p>Miojo saudável</p>
      </a>
      <div class="conteudo_receitas">
        <h3>Caldo de frango para macarrão - Adaptação “miojo” saudável</h3>
<p>- 78g de casca de cenoura;</p>
<p>- 85g de casca de cebola;</p>
<p>- 18g de salsa;</p>
<p>- 80g de macarrão do tipo Cabelinho de Anjo;</p>
<p>- 790g de frango com osso;</p>
<p>- 15ml de azeita extra virgem;</p>
<p>- 2L de água.</p>
<p>Modo de preparo: 
Separe o peito do frango da 
carcaça e reserve;
Retire cascas e aparas da cenoura e 
cebola para reservar e corte o restante 
dos vegetais em pedaços grandes;
</p>
<p>
Separe a salsa e lave bem, depois 
pique e a reserve;
Aqueça a panela com azeite e 
adicione a carcaça de frango 
para selar;
</p>
<p>
Depois de selar, acrescente a 
cenoura, a cebola e a salsa e 
mexa um pouco;
</p>
<p>
 Adicione os 2 litros de água na 
panela e mantenha em fervura por 
1 a 2 horas;
</p>
<p>
Após esse tempo, separe o 
líquido das aparas utilizando 
uma peneira;
</p>
<p>
Após esse tempo, separe o 
líquido das aparas utilizando 
uma peneira;
</p>
<p>
Cozinhe por 3 minutos e sirva-se.
</p>
        <a href="#" class="voltar">Voltar</a>
      </div>
    </div>

    <div id="receita2" class="receita">
      <a href="#receita2">
        <img src="img/Img_bolacha.jpg" alt="bolacha">
        <p>Massa de bolacha</p>
      </a>
      <div class="conteudo_receitas">
        <h3>Massa de bolacha</h3>
        <p>- 240g de farinha de trigo;</p>
<p>- 45g de cacau em pó 30%;</p>
<p>- 25g de margarina sem sal;</p>
<p>- 50ml de água.</p>
<p>Modo de preparo: 
Em uma tigela, adicione a aveia, o 
cacau, o leite em pó, a margarina e 
a água e misture com o auxílio de 
uma colher;
</p>
<p>
A massa ficará mais pesada e homogênea. 
Pegue-a com a mão e continue encorpando 
os ingredientes;
</p>
<p>
O ponto certo é até ela desgrudar 
da mão. A partir dessa etapa, 
modele a massa da bolacha da 
maneira que preferir;
</p>
<p>
Com a forma untada de 
margarina, distribua as bolachas 
e leve ao forno por 10 a 15 
minutos.
</p>
<p>
Caso queira que a bolacha 
fique crocante, faça pequenos 
furos com a ajuda de um garfo 
na massa antes de ir ao forno.
</p>
<p>
Como opções dos 
recheios da bolacha, 
tem o de leite em pó, o 
de chocolate e o de 
morango.
</p>
        <a href="#" class="voltar">Voltar</a>
      </div>
    </div>

    <div id="receita3" class="receita">
      <a href="#receita3">
        <img src="img/Img_DanoneMorango.jpg" alt="Danone Morango">
        <p>Danone de Morango</p>
      </a>
      <div class="conteudo_receitas">
        <h3>Danone de Morango</h3>
<p>- 300g de couve-flor;</p>
<p>- 200g de morango limpo e picado;</p>
<p>- 120g de leite em pó integral;</p>
<p>- Caso necessário, adicione 60 gramas de 
açúcar refinado.</p>

<p>
Modo de preparo:
Higienize e retire as aparas do 
couve-flor;
Higienize os morangos e corte 
em pedaços menores;
Coloque o couve-flor para 
cozinhar em fogo médio, por 
25 minutos;
Após o cozimento do couve
flor despreze toda a água do 
cozimento;
Descartar a água do cozimento auxilia na perda do 
potássio no alimento.
</p>
<p>
Reserve o couve-flor e espere 
até que a temperatura 
diminua;
Adicione os morangos  e a couve 
no liquidificador e bata em 
velocidade média até que virem 
uma mistura homogênea;
Desligue o liquidificador e 
adicione o leite em pó e bata 
novamente a mistura;
Adicione a mistura a um 
recipiente e leve a geladeira, 
deixe por no mínimo 4 horas e 
sirva.
</p>
        <a href="#" class="voltar">Voltar</a>
      </div>
    </div>

    <div id="receita4" class="receita">
      <a href="#receita4">
        <img src="img/Img_BolinhoChocolate.jpg" alt="Bolinho de Chocolate">
        <p>Bolinho de Chocolate</p>
      </a>
      <div class="conteudo_receitas">
        <h3>Bolinho de Chocolate</h3>
<p>- 4 ovos;</p>
<p>- 2 potes de iogurte natural de 170g;</p>
<p>- 100ml de óleo;</p>
<p>- 200g de açúcar;</p>
<p>- 400g de farinha de trigo;</p>
<p>- 1 colher de sopa de fermento em pó.</p>


<p>
Modo de preparo:
 Misture todos os ingredientes, 
exceto o fermento, numa tigela 
e mexa com uma colher;
 Adicione o fermento na 
massa;
</p>
<p>
Divida a massa nas formas de 
cupcake, leve ao forno pré 
aquecido a 180°graus e deixe 
por 30min ou até a superfície 
estar dourada;
 Espere esfriar para saborear o seu 
bolinho.
</p>
        <a href="#" class="voltar">Voltar</a>
      </div>
    </div>

  </div>
    
</div>


`;
  
   } else if(tipo === "videos") {
    conteudo.innerHTML = `
    <h2>Vídeos das receitas</h2>
<div class="videos">
    <div>
        <h3>Bolinho de Iogurte</h3>
        <video controls>
            <source src="videos/Bolinho.mp4" type="video/mp4">
        </video>
    </div>

    <div>
        <h3>Iogurte de Couve Flor</h3>
        <video controls>
            <source src="videos/Danone.mp4" type="video/mp4">
        </video>
    </div>

    <div>
        <center><h3>Macarrão instantâneo sabor frango</h3><center>
        <video controls>
            <source src="videos/Miojo.mp4" type="video/mp4">
        </video>
    </div>
</div>

    `;
   }
}

window.onload = () => {
  mostrarConteudo("drc");
};
</script>
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

<div class="adultos-page">
  <div class="area-jogos">
    <div class="sidebar-jogos">
      <button id="btn-drc" onclick="mostrarConteudo('drc')">O que é DRC</button>
      <button id="btn-receitas" onclick="mostrarConteudo('receitas')">Receitas</button>
      <button id="btn-videos" onclick="mostrarConteudo('videos')">Vídeos</button>
      <button onclick="window.location.href='postagens.php'">Fórum</button>
    </div>
    <div class="conteudo-jogos" id="conteudo-jogos"></div>
  </div>
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
<script>
  function mostrarReceita(id) {
  // Esconde todas as imagens
  const itens = document.querySelectorAll("#receitas .item");
  itens.forEach(item => {
    item.querySelector("img").style.display = "none";
    item.querySelector("p").style.display = "none";
    item.querySelector(".conteudo").style.display = "none";
  });

  // Mostra apenas a receita clicada
  document.getElementById("receita" + id).style.display = "block";
}

function voltar() {
  // Mostra todas as imagens novamente
  const itens = document.querySelectorAll("#receitas .item");
  itens.forEach(item => {
    item.querySelector("img").style.display = "inline";
    item.querySelector("p").style.display = "block";
    item.querySelector(".conteudo").style.display = "none";
  });
}

</script>
</body>
</html>
