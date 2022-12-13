<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio;

use \ECidade\Patrimonial\Protocolo\UF\Repository as UFRepository;

class DemonstrativoFiscal
{
  /**
   * Retorna o ente da federação de acordo com as regras de emissão dos demonstrativos fiscais
   *
   * @param \Instituicao $oInstituicao
   * @return string
   */
  public static function getEnteFederativo(\Instituicao $oInstituicao)
  {

    $oRepository = new UFRepository();
    $sEnteFederativo = null;

    if ($oInstituicao->getTipo() == \Instituicao::TIPO_TRIBUNAL_DE_CONTAS_ESTADO || $oInstituicao->getTipo() == \Instituicao::TIPO_TRIBUNAL_DE_JUSTICA || $oInstituicao->getTipo() == \Instituicao::TIPO_MINISTERIO_PUBLICO_ESTADUAL ) {
      $sEnteFederativo = $oRepository->getBySigla( $oInstituicao->getUf() )->getNomeExtenso() . ' - ' . $oInstituicao->getUf();
    } else {
      $sEnteFederativo = 'MUNICÍPIO DE '.$oInstituicao->getMunicipio(). ' - '. $oInstituicao->getUf();
    }

    return $sEnteFederativo;
  }
}
