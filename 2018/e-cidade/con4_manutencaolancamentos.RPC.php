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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SubSistemaConta.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));
require_once modification("model/contabilidade/planoconta/ContaPlano.model.php");

db_app::import("exceptions.*");
db_app::import("recursosHumanos.RefactorProvisaoFerias");
db_app::import("patrimonio.*");
db_app::import("patrimonio.depreciacao.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("financeiro.*");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
$aDadosRetorno          = array();

try {

  switch ($oParam->exec) {

    /**
     * case para processamento do inventário
     */
    case "getDadosDocumento":

      $iDocumento       = $oParam->iDocumento;
      $iAno             = DB_getsession("DB_anousu");

      $aDadosDocumento  = array();
      $oEventoContabil  = new EventoContabil($iDocumento, $iAno);
      $oDadosLancamento = $oEventoContabil->getEventoContabilLancamento();
      $aRegrasEvento    = array();
      if (count($oDadosLancamento) > 0) {

        $aRegrasEvento    = $oDadosLancamento[0]->getRegrasLancamento();
        $iHistorico       = $oDadosLancamento[0]->getHistorico();
        $sHistorico       = $oDadosLancamento[0]->getDescricao();
      }

      foreach ($aRegrasEvento as $oRegrasEvento) {

        $oPlanoContaDebito = new ContaPlanoPCASP(null, $iAno, $oRegrasEvento->getContaDebito());
        $iContaDebito      = $oRegrasEvento->getContaDebito();
        $sEstruturalDebito = $oPlanoContaDebito->getEstrutural();
        $sDescricaoDebito  = urlencode($oPlanoContaDebito->getDescricao());
        if (empty($iContaDebito) || $iContaDebito == "0") {
          $iContaDebito      = "";
          $sEstruturalDebito = "";
          $sDescricaoDebito  = "";
        }
        unset($oPlanoContaDebito);

        $oPlanoContaCredito = new ContaPlanoPCASP(null, $iAno, $oRegrasEvento->getContaCredito());
        $iContaCredito      = $oRegrasEvento->getContaCredito();
        $sEstruturalCredito = $oPlanoContaCredito->getEstrutural();
        $sDescricaoCredito  = urlencode($oPlanoContaCredito->getDescricao());
        if (empty($iContaCredito) || $iContaCredito == "0") {
          $iContaCredito      = "";
          $sEstruturalCredito = "";
          $sDescricaoCredito  = "";
        }
        unset($oPlanoContaCredito);

        // descricao do historico
        $oDaoConhist     = db_utils::getDao("conhist");
        $sSqlHistorico   = $oDaoConhist->sql_query_file($iHistorico, "c50_descr", null, null);
        $rsHistorico     = $oDaoConhist->sql_record($sSqlHistorico);
        $oDadosHistorico = db_utils::fieldsMemory($rsHistorico, 0);


        $oDadosDocumento = new stdClass();
        $oDadosDocumento->iContaDebito        = $iContaDebito;
        $oDadosDocumento->sEstruturalDebito   = $sEstruturalDebito;
        $oDadosDocumento->sDescricaoDebito    = $sDescricaoDebito;

        $oDadosDocumento->iContaCredito       = $iContaCredito;
        $oDadosDocumento->sEstruturalCredito  = $sEstruturalCredito;
        $oDadosDocumento->sDescricaoCredito   = $sDescricaoCredito;

        $oDadosDocumento->iHistoricoTransacao = $iHistorico;
        $oDadosDocumento->sHistorico          = urlencode($oDadosHistorico->c50_descr);
        $aDadosDocumento[] = $oDadosDocumento;
      }

      $oRetorno->aDados = $aDadosDocumento;
    break;


    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;


  }


  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

} catch (DBException $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

} catch (ParameterException $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

} catch (BusinessException $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}
