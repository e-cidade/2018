<?php

/**
 * Class cl_planocontadetalhe
 */
class cl_planocontadetalhe extends DAOBasica {

  /**
   * Nome do Esquema
   */
  const NOME_SCHEMA = 'contabilidade';

  /**
   * Nome da tabela
   */
  const NOME_TABELA = 'planocontadetalhe';

  public function __construct() {
    parent::__construct(self::NOME_SCHEMA.".".self::NOME_TABELA);
  }
}
