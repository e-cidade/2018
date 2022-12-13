<?php

/**
 * Class cl_importacaoplanoconta
 */
class cl_importacaoplanoconta extends DAOBasica {

  /**
   * Nome do Esquema
   */
  const NOME_SCHEMA = 'contabilidade';

  /**
   * Nome da tabela
   */
  const NOME_TABELA = 'importacaoplanoconta';

  public function __construct() {
    parent::__construct(self::NOME_SCHEMA.".".self::NOME_TABELA);
  }
}
