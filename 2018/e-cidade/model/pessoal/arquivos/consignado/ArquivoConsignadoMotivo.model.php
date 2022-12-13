<?php


class ArquivoConsignadoMotivo {


  const MOTIVO_FALECIMENTO        = 1;
  const MOTIVO_SERVIDOR_INVALIDO  = 2;
  const MOTIVO_TIPO_CONTRATO      = 3;
  const MOTIVO_MARGEM_EXCEDIDO    = 4;
  const MOTIVO_OUTROS_MOTIVOS     = 5;
  const MOTIVO_SERVIDOR_DESLIGADO = 6;
  const MOTIVO_SERVIDOR_AFASTADO  = 7;
  const MOTIVO_EXCLUIDO           = 8;
  const MOTIVO_SALDO_INSUFICIENTE = 9;

  public static function getDescricaoMotivo($iMotivo) {

    $aMotivos = array(
      '' => 'ACEITO',
      1 => 'FALECIMENTO',
      2 => 'SERVIDOR NÃO IDENTIFICADO',
      3 => "TIPO DE CONTRATO NÃO PERMITE EMPRÉSTIMO",
      4 => "MARGEM CONSIGNÁVEL EXCEDIDA",
      5 => "NÃO DESCONTADO - OUTROS MOTIVOS",
      6 => "SERVIDOR DESLIGADO",
      7 => "SERVIDOR AFASTADO EM LICENÇA SAÚDE",
      8 => "EXCLUÍDO",
      9 => "SALDO INSUFICIENTE"
    );
    return $aMotivos[$iMotivo];
  }
}