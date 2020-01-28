<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBseller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\Repository;

use \ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\Model\MarcacaoPontoJustificativa as MarcacaoPontoJustificativaModel;

/**
 * Class MarcacaoPontoJustificativa
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class MarcacaoPontoJustificativa {

  /**
   * @var \cl_pontoeletronicoregistrojustificativa
   */
  private $oDao;

  /**
   * MarcacaoPontoJustificativa constructor.
   */
  public function __construct() {
    $this->oDao = new \cl_pontoeletronicoregistrojustificativa();
  }

  /**
   * @param \Servidor $oServidor
   * @param \DBDate $oData
   * @return MarcacaoPontoJustificativaModel[]|null
   * @throws \DBException
   */
  public function getTodasJustificativasDiaTrabalho(\Servidor $oServidor, \DBDate $oData) {

    $aCampos = array('pontoeletronicoregistrojustificativa.*');
    $aWhere  = array("rh197_data = '{$oData->getDate()}'", "rh197_matricula = {$oServidor->getMatricula()}");

    $sSql = $this->oDao->sqlJustificativasData($aCampos, '', $aWhere);
    $rs   = db_query($sSql);

    if(!$rs) {
      throw new \DBException('Erro ao buscar as justificativas lançadas.');
    }

    if(pg_num_rows($rs) == 0) {
      return null;
    }

    return \db_utils::makeCollectionFromRecord($rs, function($oRetorno) {

      $oMarcacaoPontoJustificativa = new MarcacaoPontoJustificativaModel();

      $oMarcacaoPonto = new MarcacaoPonto();
      $oMarcacaoPonto->setCodigo($oRetorno->rh199_pontoeletronicoarquivodataregistro);

      $oJustificativa = new Justificativa();
      $oJustificativa->setCodigo($oRetorno->rh199_pontoeletronicojustificativa);

      $oMarcacaoPontoJustificativa->setCodigo($oRetorno->rh199_sequencial);
      $oMarcacaoPontoJustificativa->setMarcacaoPonto($oMarcacaoPonto);
      $oMarcacaoPontoJustificativa->setJustificativa($oJustificativa);
      $oMarcacaoPontoJustificativa->setTipo($oRetorno->rh199_tipo);

      return $oMarcacaoPontoJustificativa;
    });
  }

  /**
   * @param array $aCodigosJustificativas
   * @throws \DBException
   * @throws \ParameterException
   */
  public function removeColecao($aCodigosJustificativas) {

    if(empty($aCodigosJustificativas)) {
      throw new \ParameterException('Justificativas não informadas.');
    }

    $this->oDao->excluir(null, 'rh199_sequencial in(' . implode(', ', $aCodigosJustificativas) . ')');

    if($this->oDao->erro_status == '0') {
      throw new \DBException($this->oDao->erro_msg);
    }
  }
}