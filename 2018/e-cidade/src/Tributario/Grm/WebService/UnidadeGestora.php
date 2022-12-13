<?php
namespace ECidade\Tributario\Grm\WebService;

use ECidade\Tributario\Grm\Repository\TipoRecolhimento as TipoRecolhimentoRepository;
use ECidade\Tributario\Grm\UnidadeGestora as UnidadeGestoraModel;

/**
 * Class UnidadeGestoraGrmWebService
 *
 */
class UnidadeGestora {

  public function getUnidadesGestoras() {

    $oUnidadeRepository  = new \ECidade\Tributario\Grm\Repository\UnidadeGestora();
    $aUnidades = array();
    foreach ($oUnidadeRepository->getAll() as $oUnidade) {

      $oUnidadeRetorno         = new \stdClass();
      $oUnidadeRetorno->codigo = $oUnidade->getCodigo();
      $oUnidadeRetorno->nome   = $oUnidade->getNome();
      $aUnidades[]             = utf8_encode_all($oUnidadeRetorno);
    }
    return $aUnidades;
  }

  /**
   * @param $codigoUnidade
   * @return array
   */
  public function getTiposRecolhimentosUnidadeGestora($codigoUnidade) {

    $oUnidade                    = new UnidadeGestoraModel($codigoUnidade);
    $oUnidade->setCodigo($codigoUnidade);

    $oTipoRecolhimentoRepository = new TipoRecolhimentoRepository();
    $aRecolhimentosUnidade       = $oTipoRecolhimentoRepository->getTiposRecolhimentoDaUnidadeGestora($oUnidade);
    $aRecolhimentos = array();
    foreach ($aRecolhimentosUnidade as $oRecolhimento) {

      $oRecolhimentoRetorno = new \stdClass();
      $oRecolhimentoRetorno->codigo                    = $oRecolhimento->getTipoRecolhimento()->getCodigo();
      $oRecolhimentoRetorno->codigo_recolhimento       = $oRecolhimento->getTipoRecolhimento()->getCodigoRecolhimento();
      $oRecolhimentoRetorno->titulo                    = $oRecolhimento->getTipoRecolhimento()->getNome();
      $oRecolhimentoRetorno->tipo_recolhedor           = $oRecolhimento->getTipoRecolhimento()->getTipoPessoa();
      $oRecolhimentoRetorno->titulo_reduzido           = $oRecolhimento->getTipoRecolhimento()->getTituloReduzido();
      $oRecolhimentoRetorno->obriga_referencia         = $oRecolhimento->getTipoRecolhimento()->obrigaNumeroReferencia();
      $oRecolhimentoRetorno->informa_desconto          = $oRecolhimento->getTipoRecolhimento()->informaDesconto();
      $oRecolhimentoRetorno->informa_juros             = $oRecolhimento->getTipoRecolhimento()->informaJuros();
      $oRecolhimentoRetorno->informa_multa             = $oRecolhimento->getTipoRecolhimento()->informaMulta();
      $oRecolhimentoRetorno->informa_outros_acrescimos = $oRecolhimento->getTipoRecolhimento()->informaOutrosAcrescimos();
      $oRecolhimentoRetorno->informa_outras_deducoes   = $oRecolhimento->getTipoRecolhimento()->informaOutrasDeducoes();
      $oRecolhimentoRetorno->atributos                 = array();
      $oGrupoAtributo  = TipoRecolhimentoRepository::getAtributosDoRecolhimento($oRecolhimento->getTipoRecolhimento());
      if (!empty($oGrupoAtributo)) {

        $atributos = $oGrupoAtributo->getAtributosAtivos();
        foreach ($atributos as $oAtributo) {

          $oAtributoRetorno = new \stdClass();
          $oAtributoRetorno->id = $oAtributo->getCodigo();
          $oAtributoRetorno->nome = $oAtributo->getDescricao();
          $oAtributoRetorno->tipo = $oAtributo->getTipo();
          $oAtributoRetorno->valor = $oAtributo->getValorDefault();
          $oAtributoRetorno->obrigatorio = $oAtributo->isObrigatorio();
          $oRecolhimentoRetorno->atributos[] = $oAtributoRetorno;
        }
      }
      $aRecolhimentos[] = $oRecolhimentoRetorno;
    }
    return utf8_encode_all($aRecolhimentos);
  }
}
