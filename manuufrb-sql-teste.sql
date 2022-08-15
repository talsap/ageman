-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Ago-2022 às 00:03
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `manuufrb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `equipamentos`
--

CREATE TABLE `equipamentos` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `patrimonio` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `local` varchar(255) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `horas` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `hist_manu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `equipamentos`
--

INSERT INTO `equipamentos` (`id`, `id_user`, `patrimonio`, `nome`, `descricao`, `local`, `imagem`, `horas`, `status`, `hist_manu`) VALUES
(1, 1, 'Patrimonio', 'Nome_equipamento', 'Descricao', 'Local', 'DIR_Imagem', '11', 'status_equipamento', 0),
(13, 1, 'P01', 'Triaxial', 'DEZS', 'UFRB', 'resources/img/uplouds/User1/equipamentos/01.png', '', '', 0),
(14, 1, 'P01', 'b', 'd', 'a', 'resources/img/uplouds/User1/equipamentos/01-2.png', '', '', 0),
(16, 1, '123456', 'Nome12345', 'descricao123', 'local123456', 'resources/img/uplouds/User1/equipamentos/01.png', '', '', 0),
(18, 1, 'teste sem img', 'sem', 'teste teste', 'imagem ', '', '', '', 0),
(19, 1, 'testet', 'testet', 'testet', 'testet', '', '', '', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Teste Manuufrb', 'teste@teste.com', '$2y$10$6KwhKt5FGvI1EXYzDSNtkOGDPb0/ua4xIKPvFQP1oSlujWWROEe2W'),
(2, 'Tarcisio', 'tarcisiosapucaia@hotmail.com', '$2y$10$6KwhKt5FGvI1EXYzDSNtkOGDPb0/ua4xIKPvFQP1oSlujWWROEe2W');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `equipamentos`
--
ALTER TABLE `equipamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `equipamentos`
--
ALTER TABLE `equipamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
