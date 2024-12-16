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

ALTER TABLE `periodos` ADD FOREIGN KEY (`id`) REFERENCES `disciplinas` (`periodo_id`);

ALTER TABLE `disciplinas` ADD FOREIGN KEY (`id`) REFERENCES `agendamentos` (`disciplina_id`);

ALTER TABLE `atividades` ADD FOREIGN KEY (`id`) REFERENCES `agendamentos` (`atividade_id`);
