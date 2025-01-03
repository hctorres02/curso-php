CREATE TABLE periodos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ano TEXT,
    semestre TEXT,
    UNIQUE (ano, semestre)
);

CREATE TABLE disciplinas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    periodo_id INTEGER,
    nome TEXT,
    cor TEXT,
    FOREIGN KEY (periodo_id) REFERENCES periodos(id)
);

CREATE TABLE atividades (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT UNIQUE,
    cor TEXT
);

CREATE TABLE agendamentos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    disciplina_id INTEGER,
    atividade_id INTEGER,
    data DATE,
    conteudo TEXT,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    FOREIGN KEY (atividade_id) REFERENCES atividades(id)
);
