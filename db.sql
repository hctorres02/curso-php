CREATE TABLE periodos (
  id INTEGER PRIMARY KEY,
  ano TEXT,
  semestre TEXT,
  UNIQUE(ano, semestre)
);

CREATE TABLE disciplinas (
  id INTEGER PRIMARY KEY,
  nome TEXT,
  cor TEXT,
  periodo_id INTEGER,
  UNIQUE(nome, periodo_id),
  FOREIGN KEY(periodo_id) REFERENCES periodos(id)
);

CREATE TABLE atividades (
  id INTEGER PRIMARY KEY,
  nome TEXT UNIQUE,
  cor TEXT
);

CREATE TABLE agendamentos (
  id INTEGER PRIMARY KEY,
  data DATE,
  disciplina_id INTEGER,
  atividade_id INTEGER,
  conteudo TEXT,
  FOREIGN KEY(disciplina_id) REFERENCES disciplinas(id),
  FOREIGN KEY(atividade_id) REFERENCES atividades(id)
);