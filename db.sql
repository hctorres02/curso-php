CREATE TABLE `periodos` (
  `id` integer PRIMARY KEY,
  `ano` varchar(255),
  `semestre` varchar(255)
);

CREATE TABLE `disciplinas` (
  `id` integer PRIMARY KEY,
  `nome` varchar(255),
  `cor` varchar(255),
  `periodo_id` integer
);

CREATE TABLE `atividades` (
  `id` integer PRIMARY KEY,
  `nome` varchar(255) UNIQUE,
  `cor` varchar(255)
);

CREATE TABLE `agendamentos` (
  `id` integer PRIMARY KEY,
  `data` date,
  `disciplina_id` integer,
  `atividade_id` integer,
  `conteudo` text
);

ALTER TABLE `disciplinas` ADD FOREIGN KEY (`periodo_id`) REFERENCES `periodos` (`id`);

ALTER TABLE `agendamentos` ADD FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`);

ALTER TABLE `agendamentos` ADD FOREIGN KEY (`atividade_id`) REFERENCES `atividades` (`id`); 