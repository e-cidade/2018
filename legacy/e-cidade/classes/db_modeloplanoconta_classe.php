<?php

/**
 * Class cl_modeloplanoconta
 */
class cl_modeloplanoconta extends DAOBasica {

  /**
   * Nome do Esquema
   */
  const NOME_SCHEMA = 'contabilidade';

  /**
   * Nome da tabela
   */
  const NOME_TABELA = 'modeloplanoconta';

  public function __construct() {
    parent::__construct(self::NOME_SCHEMA.".".self::NOME_TABELA);
  }
}
