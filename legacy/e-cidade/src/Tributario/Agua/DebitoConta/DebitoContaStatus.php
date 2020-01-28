<?php

namespace ECidade\Tributario\Agua\DebitoConta;

abstract class DebitoContaStatus
{
  const PENDENTE = 1;
  const ATIVO = 2;
  const INATIVO = 3;

  public static $sDescricao = array(self::PENDENTE => 'Pendente',
                                    self::ATIVO => 'Ativo',
                                    self::INATIVO => 'Inativo');
}
