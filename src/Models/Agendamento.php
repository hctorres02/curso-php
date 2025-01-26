<?php

namespace Models;

class Agendamento
{
    /** @var int */
    public $id;

    /** @var int */
    public $disciplina_id;

    /** @var int */
    public $atividade_id;

    /** @var string */
    public $data;

    /** @var string */
    public $conteudo;
}
