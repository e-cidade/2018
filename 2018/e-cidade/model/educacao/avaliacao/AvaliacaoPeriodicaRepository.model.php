<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

/**
 * Repositoy para as AvaliacaoPeriodicas
 * @package   Educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.9 $
 */
class AvaliacaoPeriodicaRepository {

  /**
   * Array com instancias de AvaliacaoPeriodica
   * @var AvaliacaoPeriodica[]
   */
  private $aAvaliacaoPeriodica = array();
  private static $oInstance;

  private function __construct() {

  }

  private function __clone(){

  }

  /**
   * Retorna a instancia do Repositorio
   * @return AvaliacaoPeriodicaRepository
   */
  protected function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new AvaliacaoPeriodicaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se a AvaliacaoPeriodica possui instancia, se não instancia e retorna a instancia de AvaliacaoPeriodica
   * @param integer $iCodigoAvaliacaoPeriodica
   * @return AvaliacaoPeriodica
   */
  public static function getAvaliacaoPeriodicaByCodigo($iCodigoAvaliacaoPeriodica) {

    if (!array_key_exists($iCodigoAvaliacaoPeriodica, AvaliacaoPeriodicaRepository::getInstance()->aAvaliacaoPeriodica)) {
      AvaliacaoPeriodicaRepository::getInstance()->aAvaliacaoPeriodica[$iCodigoAvaliacaoPeriodica] = new AvaliacaoPeriodica($iCodigoAvaliacaoPeriodica);
    }
    return AvaliacaoPeriodicaRepository::getInstance()->aAvaliacaoPeriodica[$iCodigoAvaliacaoPeriodica];
  }

  /**
   * Adiciona uma AvaliacaoPeriodica ao repositorio
   * @param AvaliacaoPeriodica $oAvaliacaoPeriodica
   */
  public static function adicionarAvaliacaoPeriodica(AvaliacaoPeriodica $oAvaliacaoPeriodica) {

    AvaliacaoPeriodicaRepository::getInstance()->aAvaliacaoPeriodica[$oAvaliacaoPeriodica->getCodigo()] = $oAvaliacaoPeriodica;
    return true;
  }

  /**
   * Remove uma AvaliacaoPeriodica do repositorio
   * @param AvaliacaoPeriodica $oAvaliacaoPeriodica
   * @return boolean
   */
  public static function removerAvaliacaoPeriodica(AvaliacaoPeriodica $oAvaliacaoPeriodica) {

    if (array_key_exists($oAvaliacaoPeriodica->getCodigo(), AvaliacaoPeriodicaRepository::getInstance()->aAvaliacaoPeriodica)) {
      unset(AvaliacaoPeriodicaRepository::getInstance()->aAvaliacaoPeriodica[$oAvaliacaoPeriodica->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna a avaliacao periodica dependente do Elemento de avaliacao informado.
   * @param IElementoAvaliacao $oElementoAvaliacao
   * @return AvaliacaoPeriodica|null instancia da avaliacao Periodica
   */
  public static function getAvaliacaoDependente(IElementoAvaliacao $oElementoAvaliacao) {

    foreach (AvaliacaoPeriodicaRepository::getInstance()->aAvaliacaoPeriodica as $oAvaliacao) {

      if ($oAvaliacao->getElementoAvaliacaoVinculado()
          && $oAvaliacao->getElementoAvaliacaoVinculado()->getCodigo() == $oElementoAvaliacao->getCodigo()) {
        return $oAvaliacao;
      }
    }

    $iCodigoProcavaliacao = '';

    switch (get_class($oElementoAvaliacao)) {

      case 'ResultadoAvaliacao':
        $iCodigoProcavaliacao = ProcedimentoAvaliacaoRepository::getVinculoResultado($oElementoAvaliacao);
        break;

      case 'AvaliacaoPeriodica':
        $iCodigoProcavaliacao = ProcedimentoAvaliacaoRepository::getVinculoPeriodoAvaliacao($oElementoAvaliacao);
        break;
    }

    return !empty($iCodigoProcavaliacao) ? AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($iCodigoProcavaliacao)
            : null;
  }
}
?>