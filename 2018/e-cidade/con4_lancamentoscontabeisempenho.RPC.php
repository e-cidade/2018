<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("classes/empenho.php");
require_once("libs/JSON.php");
require_once("classes/lancamentoContabil.model.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->itens   = array();
$dtDia           = date("Y-m-d", db_getsession("DB_datausu"));
function diff_data($inicial,$final,$format="br"){
   $format = strtolower($format);
   switch ($format){
      case "br":
         $fmt = "%d/%m/%Y";
      break;
      case "en":
         $fmt = "%Y-%m-%d";
      break;
   }
   $dtini   = explode("/",$inicial);
   $dtfim   = explode("/",$final);
   $inicial = $dtini[2]."-".$dtini[1]."-".$dtini[0];
   $final   = $dtfim[2]."-".$dtfim[1]."-".$dtfim[0];
   $diastotal  =  (strtotime($final)-strtotime($inicial))/86400;
   $jd = 0;
   for ($i = 0;$i <= $diastotal;$i++){
       $date[$jd] = strftime($fmt,mktime(0,0,0,$dtini[1],$dtini[0]+$i,$dtini[2]));
       $jd++;
   }
   return $date;
}

switch ($oParam->exec) {

  case 'getLancamentosEmpenho' :


    /**
     * Verificamos se a data da sessao é maior que 10 dias do ultimo do bimestre.
     * Devemos informar que o o paf pode ter sido encerrado;
     */
    $iMesAnterior = date("m", db_getsession("DB_datausu"))-1;
    $iAno         = db_getsession("DB_anousu");
    if ($iMesAnterior == 0) {

      $iAno         -= 1;
      $iMesAnterior = 12;
    }
    $oRetorno->aviso = "";
    if ( $iMesAnterior % 2 == 0) {

    /**
     * mes é anterior é par (logo, é fim de bimestre)
     */
      $iUltimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $iMesAnterior, $iAno);
      $sDataAnterior = "{$iUltimoDiaMes}/{$iMesAnterior}/{$iAno}";
      $sDataAtual    = date("d/m/Y", db_getsession("DB_datausu"));
      $aDatas = diff_data($sDataAnterior, $sDataAtual);
      if (count($aDatas) <= 11) {

        $oRetorno->aviso  = "Antes de confirmar esta operação, é recomendável consulta ao Responsável pela ";
        $oRetorno->aviso .= "Contabilidade, pois pode haver reflexos no encerramento do bimestre para fins de ";
        $oRetorno->aviso .= "fechamento do SIAPC/PAD";
        $oRetorno->aviso  = urlencode($oRetorno->aviso);
      }

    }
    $oEmpenho = new empenho();
    $oEmpenho->setEmpenho($oParam->iNumEmp);
    $oRetorno->itens = $oEmpenho->getLancamentosContabeis();
    break;

  case 'alterarLancamento' :

    $oEmpenho = new empenho();
    $oEmpenho->setEmpenho($oParam->iNumEmp);
    $oRetorno->iNumEmp = $oParam->iNumEmp;
    try {

      db_inicio_transacao();
      $oEmpenho->alterarDataLancamento($oParam->iCodigo, $oParam->dtData);
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);

    }
     break;

    case 'alterarLancamentoComDesconto' :

    $oEmpenho = new empenho();
    $oEmpenho->setEmpenho($oParam->iNumEmp);
    $oRetorno->iNumEmp = $oParam->iNumEmp;
    try {

      db_inicio_transacao();
      $oEmpenho->alterarDataDesconto($oParam->iCodigoDesconto, $oParam->dtData);
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);

    }
    break;

    case 'excluirLancamento':

      $oEmpenho = new empenho();
      $oEmpenho->setEmpenho($oParam->iNumEmp);
      $oRetorno->iNumEmp = $oParam->iNumEmp;
      try {

        db_inicio_transacao();
        $oEmpenho->excluirLancamento($oParam->iCodigo);
        db_fim_transacao(false);

      } catch (Exception $eErro) {

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
        db_fim_transacao(true);

      }

    break;

  case 'excluirLancamentoComDesconto' :

    $oEmpenho = new empenho();
    $oEmpenho->setEmpenho($oParam->iNumEmp);
    $oRetorno->iNumEmp = $oParam->iNumEmp;
    try {

      db_inicio_transacao();
      $oEmpenho->excluirLancamentoDesconto($oParam->iCodigoDesconto);
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);

    }
  break;

  case "getInfoLancamento":

    try {
      $oRetorno->itens = lancamentoContabil::getInfoLancamento($oParam->iLancamento);
    } catch (Exception $oErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($oErro->getMessage());

    }
    break;

  case "verificaRelacaoComContaCorrenteDetalhe":

    $lTemRelacaoContaCorrenteDetalhe = false;
    $oEmpenhoFinanceiro              = new EmpenhoFinanceiro($oParam->iCodigoEmpenho);
    $oInformacaoLancamento           = lancamentoContabil::getInfoLancamento($oParam->iCodigoLancamento, false);

    if ($oInformacaoLancamento->tipoevento == 10) {
      $lTemRelacaoContaCorrenteDetalhe = $oEmpenhoFinanceiro->temContaCorrente();
    }

    $oRetorno->retorno = $lTemRelacaoContaCorrenteDetalhe;
    break;

}
echo $oJson->encode($oRetorno);