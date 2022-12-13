<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao;

use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosGerais;

class ParametrosRepository {

  /**
   * @var array
   */
  private $aConfiguracoesGerais;

  /**
   * @var array
   */
  private $aConfiguracoesLotacao;

  /**
   * @var ParametrosRepository
   */
  private static $oInstance;

  /**
   * Cria uma instância de parâmetros
   * @return ParametrosRepository
   */
  public static function create() {
    return ParametrosRepository::getInstance();
  }

  /**
   * Retorna uma instância de parâmetros
   * @return ParametrosRepository
   */
  public static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new ParametrosRepository();
    }

    return self::$oInstance;
  }

  /**
   * @param $sConfiguracoes
   * @param array|null $aCampos
   * @param array|null $aOrder
   * @param array|null $aWhere
   * @throws \DBException
   */
  private function getDados($sConfiguracoes, array $aCampos = null, array $aOrder = null, array $aWhere = null) {

    if(strtolower($sConfiguracoes) == 'lotacao') {
      $oDao = new \cl_pontoeletronicoconfiguracoeslotacao;
    } else { //Gerais
      $oDao = new \cl_pontoeletronicoconfiguracoesgerais;
    }

    $sCampos = '*';
    if(!empty($aCampos)) {
      $sCampos = implode(' , ', $aCampos);
    }

    $sOrder = null;
    if(!empty($aOrder)) {
      $sOrder = implode(' , ', $aOrder);
    }

    $sWhere = null;
    if(!empty($aWhere)) {
      $sWhere = implode(' and ', $aWhere);
    }


    $sSql = $oDao->sql_query_file(
      null,
      $sCampos,
      $sOrder,
      $sWhere
    );
    $rsDados = db_query($sSql);

    if(!$rsDados) {
      throw new \DBException("Ocorreu um erro ao buscar as configurações.");
    }

    if(pg_num_rows($rsDados) > 0) {

      $oParametros = $this;
      $aConfiguracoes = \db_utils::makeCollectionFromRecord($rsDados, function ($oRetorno) use ($sConfiguracoes, $oParametros) {
        return $oParametros->montarDados($oRetorno, $sConfiguracoes);
      });

      foreach ($aConfiguracoes as $oConfiguracoes) {
        switch ($sConfiguracoes) {
          case 'lotacao':
            $this->aConfiguracoesLotacao[$oConfiguracoes->getCodigoLotacao()] = $oConfiguracoes;
            break;

          default:
            $this->aConfiguracoesGerais[$oConfiguracoes->getInstituicao()->getCodigo()] = $oConfiguracoes;
            break;
        }
      }
    }
  }

  /**
   * @param $oDados
   * @param $sConfiguracoes
   * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosGerais|\ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao
   */
  public function montarDados($oDados, $sConfiguracoes) {

    switch ($sConfiguracoes) {

      case 'lotacao':

        $oParametros = new ParametrosLotacao();
        $oParametros->setCodigo($oDados->rh195_sequencial);
        $oParametros->setTolerancia($oDados->rh195_tolerancia);
        $oParametros->setHoraExtra50($oDados->rh195_hora_extra_50);
        $oParametros->setHoraExtra75($oDados->rh195_hora_extra_75);
        $oParametros->setHoraExtra100($oDados->rh195_hora_extra_100);
        $oParametros->setCodigoLotacao($oDados->rh195_lotacao);
        $oParametros->setSupervisor(\ServidorRepository::getInstanciaByCodigo($oDados->rh195_supervisor));

        break;

      default: //Gerais

        $oParametros = new ParametrosGerais();
        $oParametros->setCodigo($oDados->rh200_sequencial);
        $oParametros->setInstituicao(\InstituicaoRepository::getInstituicaoByCodigo($oDados->rh200_instituicao));
        $oParametros->setTipoAssentamentoExtra50Diurna(\TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh200_tipoasse_extra50diurna));
        $oParametros->setTipoAssentamentoExtra75Diurna(\TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh200_tipoasse_extra75diurna));
        $oParametros->setTipoAssentamentoExtra100Diurna(\TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh200_tipoasse_extra100diurna));
        $oParametros->setTipoAssentamentoExtra50Noturna(\TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh200_tipoasse_extra50noturna));
        $oParametros->setTipoAssentamentoExtra75Noturna(\TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh200_tipoasse_extra75noturna));
        $oParametros->setTipoAssentamentoExtra100Noturna(\TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh200_tipoasse_extra100noturna));
        $oParametros->setTipoAssentamentoAdicionalNoturno(\TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh200_tipoasse_adicionalnoturno));
        $oParametros->setTipoAssentamentoFalta(\TipoAssentamentoRepository::getInstanciaPorCodigo($oDados->rh200_tipoasse_falta));
        $oParametros->setHoraExtraSomenteComAutorizacao($oDados->rh200_autorizahoraextra == 't');

        break;
    }

    return $oParametros;
  }

  /**
   * @param $iCodigoLotacao
   * @return int|null
   */
  public function getConfiguracoesLotacao($iCodigoLotacao) {

    if(empty($this->aConfiguracoesLotacao[$iCodigoLotacao])) {
      $this->getDados('lotacao', null, null, array("rh195_lotacao = {$iCodigoLotacao}"));
    }

    return !empty($this->aConfiguracoesLotacao[$iCodigoLotacao]) ? $this->aConfiguracoesLotacao[$iCodigoLotacao] : null;
  }

  /**
   * @param $iCodigoInstituicao
   * @return int|null
   */
  public function getConfiguracoesGerais($iCodigoInstituicao) {

    if(empty($this->aConfiguracoesGerais[$iCodigoInstituicao])) {
      $this->getDados('gerais', null, null, array("rh200_instituicao = {$iCodigoInstituicao}"));
    }

    return !empty($this->aConfiguracoesGerais[$iCodigoInstituicao]) ? $this->aConfiguracoesGerais[$iCodigoInstituicao] : null;
  }
}
