<?php
/**
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
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson                = new services_json(0,true);
$oParametros          = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->status     = true;
$oRetorno->erro       = false;
$oRetorno->message    = '';

const MENSAGEM        = 'recursoshumanos.rh.rec4_assentamentosefetificadadeRPC.';

try {

  switch ($oParametros->exec) {

    case 'getAssentamentosEfetividade':

      $oDataInicio = null;

      if(isset($oParametros->sDataInicio) && !empty($oParametros->sDataInicio)) {
        $oDataInicio = new DBDate($oParametros->sDataInicio);
      }

      if(!isset($oParametros->iTipoAssentamento) || empty($oParametros->iTipoAssentamento)) {
        throw new BusinessException(_M(MENSAGEM ."tipo_assentamento_nao_informado"));
      }

      $oTipoAssentamento         = TipoAssentamentoRepository::getInstanciaPorCodigo($oParametros->iTipoAssentamento);
      $aAssentamentosEfetividade = AssentamentoFuncionalRepository::getAssentamentosEfetividadePorTipo($oTipoAssentamento, $oDataInicio);

      $sTipoAssentamento                   = $oTipoAssentamento->getCodigo();
      $sDescricaoAssentamento              = $oTipoAssentamento->getDescricao();
      $oRetorno->aAssentamentosEfetividade = array();

      foreach ($aAssentamentosEfetividade as $oAssentamento) {

        $oServidor          = ServidorRepository::getInstanciaByCodigo($oAssentamento->getMatricula(), DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());

        $oStdAssentamento = new StdClass();
        $oStdAssentamento->iCodigo        = $oAssentamento->getCodigo();
        $oStdAssentamento->sNome          = $oServidor->getCgm()->getNome();
        $oStdAssentamento->iMatricula     = $oServidor->getMatricula();
        $oStdAssentamento->sTipo          = $sTipoAssentamento;
        $oStdAssentamento->sDataInicio    = ($oAssentamento->getDataConcessao()) ? $oAssentamento->getDataConcessao()->getDate(DBDate::DATA_PTBR) : '';
        $oStdAssentamento->sDataFim       = ($oAssentamento->getDataTermino()) ? $oAssentamento->getDataTermino()->getDate(DBDate::DATA_PTBR) : '';
        $oStdAssentamento->sHistorico     = $oAssentamento->getHistorico();
        $oStdAssentamento->sDescricaoTipo = $sDescricaoAssentamento;
        $oRetorno->aAssentamentosEfetividade[] = $oStdAssentamento;
      }
      break;

    case 'getAssentamentosFuncionais':

      $aAssentamentosEfetividade = AssentamentoFuncionalRepository::getAssentamentosFuncional($oParametros->iCodigoEfetividade);


      foreach ($aAssentamentosEfetividade as $oAssentamento) {

        $oServidor         = ServidorRepository::getInstanciaByCodigo($oAssentamento->getMatricula(), DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
        $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($oAssentamento->getTipoAssentamento());

        $oStdAssentamento = new StdClass();
        $oStdAssentamento->iCodigo      = $oAssentamento->getCodigo();
        $oStdAssentamento->sNome        = $oServidor->getCgm()->getNome();
        $oStdAssentamento->iMatricula   = $oServidor->getMatricula();
        $oStdAssentamento->sTipo        = $oTipoAssentamento->getCodigo();
        $oStdAssentamento->sDataInicio  = $oAssentamento->getDataConcessao()->getDate(DBDate::DATA_PTBR);
        $oStdAssentamento->sDataFim     = ($oAssentamento->getDataTermino()) ? $oAssentamento->getDataTermino()->getDate(DBDate::DATA_PTBR) : '';
        $oRetorno->aAssentamentosEfetividade[] = $oStdAssentamento;
      }
      break;

    case 'clonarAssentamentoParaVidaFuncional':

      if(empty($oParametros->aAssentamentos) || count($oParametros->aAssentamentos) <= 0) {
        throw new BusinessException(_M(MENSAGEM ."nenhum_assentamento_encontrado"));
      }

      foreach ($oParametros->aAssentamentos as $iCodigoAssentamento) {

        $oAssentamento = AssentamentoRepository::getInstanceByCodigo($iCodigoAssentamento);

        $oAssentamentoFuncional = new AssentamentoFuncional();
        $oAssentamentoFuncional->setMatricula       ($oAssentamento->getMatricula());
        $oAssentamentoFuncional->setTipoAssentamento($oAssentamento->getTipoAssentamento());
        $oAssentamentoFuncional->setHistorico       ($oAssentamento->getHistorico());
        $oAssentamentoFuncional->setCodigoPortaria  ($oAssentamento->getCodigoPortaria());
        $oAssentamentoFuncional->setDescricaoAto    ($oAssentamento->getDescricaoAto());
        $oAssentamentoFuncional->setDias            ($oAssentamento->getDias());
        $oAssentamentoFuncional->setPercentual      ($oAssentamento->getPercentual());
        $oAssentamentoFuncional->setSegundoHistorico($oAssentamento->getSegundoHistorico());
        $oAssentamentoFuncional->setLoginUsuario    ($oAssentamento->getLoginUsuario());
        $oAssentamentoFuncional->setDataLancamento  ($oAssentamento->getDataLancamento());
        $oAssentamentoFuncional->setConvertido      ($oAssentamento->isConvertido());
        $oAssentamentoFuncional->setAnoPortaria     ($oAssentamento->getAnoPortaria());
        $oAssentamentoFuncional->setDataConcessao   ($oAssentamento->getDataConcessao());
        $oAssentamentoFuncional->setDataTermino     ($oAssentamento->getDataTermino());

        $oAssentamentoFuncional->setAssentamentoEfetividade($oAssentamento);

        AssentamentoFuncionalRepository::persist($oAssentamentoFuncional);
      }

      $oRetorno->message = urlEncode(_M(MENSAGEM ."sucesso_processamento"));
      break;
  }
} catch (Exception $eException) {

  $oRetorno->status  = false;
  $oRetorno->erro    = true;
  $oRetorno->message = urlEncode($eException->getMessage());
}

echo JSON::create()->stringify($oRetorno);