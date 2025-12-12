-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/10/2025 às 01:44
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `chat_drc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `chat`
--

CREATE TABLE `chat` (
  `mensagem_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `hora_mandada` datetime DEFAULT current_timestamp(),
  `editada` tinyint(1) DEFAULT 0,
  `excluida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `postagens`
--

CREATE TABLE `postagens` (
  `postagem_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `hora_mandada` datetime DEFAULT current_timestamp(),
  `editada` tinyint(1) DEFAULT 0,
  `excluida` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `postagens`
--

INSERT INTO `postagens` (`postagem_id`, `usuario_id`, `mensagem`, `hora_mandada`, `editada`, `excluida`) VALUES
(24, 22, 'A Doença Renal Crônica tem cura?', '2025-08-22 22:58:22', 0, 0),
(25, 22, 'Como é diagnosticada a DRC?', '2025-08-22 22:59:09', 0, 0),
(26, 22, 'O que é Doença Renal Crônica (DRC)?', '2025-08-22 23:00:28', 0, 1),
(27, 23, 'QUal receita uma pessoal com drc e inolerancia a lactose voce recomenda', '2025-08-23 08:00:53', 0, 1),
(28, 23, 'oi qual sua agua favorita\r\n', '2025-10-27 18:45:45', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas_postagem`
--

CREATE TABLE `respostas_postagem` (
  `resposta_id` int(11) NOT NULL,
  `postagem_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `hora_mandada` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `respostas_postagem`
--

INSERT INTO `respostas_postagem` (`resposta_id`, `postagem_id`, `usuario_id`, `mensagem`, `hora_mandada`) VALUES
(38, 26, 21, 'Doença renal crônica é uma lesão no rim que persiste por mais de 3 meses, o que faz com que esse órgão não filtre os metabólitos do sangue adequadamente, resultando em sintomas, como urina com espuma, náuseas, vômitos, cãibras e inchaço nos pés e tornozelos, por exemplo.', '2025-08-22 23:01:28'),
(39, 25, 21, 'A Doença Renal Crônica (DRC) é diagnosticada através de exames laboratoriais e de imagem que avaliam a função renal e detectam danos estruturais.', '2025-08-22 23:01:40'),
(40, 24, 21, 'A doença renal crônica (DRC) não tem cura, mas possui tratamento para retardar a progressão da Doença e melhorar a qualidade da vida do paciente', '2025-08-22 23:01:51'),
(41, 27, 23, 'sla', '2025-08-23 08:01:24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `tipo` enum('Adulto','Profissional') NOT NULL DEFAULT 'Adulto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nome`, `email`, `senha`, `foto_perfil`, `data_cadastro`, `tipo`) VALUES
(21, 'Felipe Cardoso', 'felipecardopaulo@gmail.com', '$2y$10$SvGdwO00L5fJ5Izn.n52fuNVufp9mI1tRcgliAq1ke4fS4GFeglfG', 'uploads/21.png', '2025-08-22 22:00:54', 'Profissional'),
(22, 'Pedro de Souza', 'pedrokisdbr@gmail.com', '$2y$10$LcNkoZBY4YNi0qN92gizD.6H0QL1mckCOPxWHfvMXAUSSalK8vCLq', 'uploads/22.png', '2025-08-22 22:56:37', 'Adulto'),
(23, 'Caio Diego Silva de Aguiar', 'caiodiego1602silva@gmail.com', '$2y$10$D5wB0UZh8QeyuqGJMluODOEZmVUnUOs23NAq9gFS2fKN5RranB/ei', 'uploads/23.png', '2025-08-23 07:59:06', 'Profissional');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`mensagem_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `postagens`
--
ALTER TABLE `postagens`
  ADD PRIMARY KEY (`postagem_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `respostas_postagem`
--
ALTER TABLE `respostas_postagem`
  ADD PRIMARY KEY (`resposta_id`),
  ADD KEY `postagem_id` (`postagem_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chat`
--
ALTER TABLE `chat`
  MODIFY `mensagem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `postagens`
--
ALTER TABLE `postagens`
  MODIFY `postagem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `respostas_postagem`
--
ALTER TABLE `respostas_postagem`
  MODIFY `resposta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `postagens`
--
ALTER TABLE `postagens`
  ADD CONSTRAINT `postagens_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `respostas_postagem`
--
ALTER TABLE `respostas_postagem`
  ADD CONSTRAINT `respostas_postagem_ibfk_1` FOREIGN KEY (`postagem_id`) REFERENCES `postagens` (`postagem_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `respostas_postagem_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
