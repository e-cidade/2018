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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
if ($oParam->exec == "getExames") {

  if (isset($oParam->iCGS)) {

    $sWhere = "s113_i_numcgs = {$oParam->iCGS}";
    /**
     * retorno de todos os exames do paciente
     */
    $oRetorno->tipo = 1;

  }
  if (isset($oParam->iCodigoExame)) {
    $sWhere = "s113_i_codigo = {$oParam->iCodigoExame}";
    $oRetorno->tipo = 2;
  }

  $sSql   = "select s113_i_codigo,";
  $sSql  .= "        s133_c_protocolo,";
  $sSql  .= "        z01_v_nome, ";
  $sSql  .= "        s110_i_codigo,";
  $sSql  .= "        z01_nome, ";
  $sSql  .= "        z01_i_cgsund, ";
  $sSql  .= "        sd63_i_codigo,";
  $sSql  .= "        sd63_c_procedimento,";
  $sSql  .= "        sd63_c_nome,";
  $sSql  .= "        s113_c_encaminhamento,";
  $sSql  .= "        s113_d_exame, ";
  $sSql  .= "        s113_c_hora,   ";
  $sSql  .= "        z01_d_nasc,  ";
  $sSql  .= "        z01_v_cgccpf,s133_i_codigo, ";
  $sSql  .= "        s133_c_observacoes";
  $sSql  .= "   from sau_agendaexames";
  $sSql  .= "        inner join sau_prestadorhorarios on s112_i_codigo = s113_i_prestadorhorarios";
  $sSql  .= "        inner join cgs_und on z01_i_cgsund = s113_i_numcgs  ";
  $sSql  .= "        left join sau_agendaexameconfirma on s113_i_codigo = s133_i_agendaexames ";
  $sSql  .= "        inner join sau_prestadorvinculos on s111_i_codigo = s112_i_prestadorvinc ";
  $sSql  .= "        inner join sau_prestadores on s110_i_codigo = s111_i_prestador ";
  $sSql  .= "        inner join cgm on s110_i_numcgm = z01_numcgm ";
  $sSql  .= "        inner join sau_procedimento ON sau_procedimento.sd63_i_codigo = sau_prestadorvinculos.s111_procedimento ";
  $sSql  .= "  where {$sWhere}";
  $sSql  .= "  order by sd63_c_nome";
  $rs     = db_query($sSql);
  if ($rs) {
    if ($oRetorno->tipo == 1) {
      $oRetorno->itens = db_utils::getCollectionByRecord($rs, false, false, true);
    } else {

      $oRetorno->itens = db_utils::fieldsMemory($rs, 0, true, false, true);
      $oRetorno->itens->valoresatributos = "";
      if ($oRetorno->itens->s133_i_codigo != "") {

        $oDaoExames    = db_utils::getDao("sau_examesatributos");
        $sWhere        = "s134_i_agendaexameconfirma = {$oRetorno->itens->s133_i_codigo}";
        $sSqlAtributos = $oDaoExames->sql_query_atributovalores(null,"*","s132_i_codigo", $sWhere,$oRetorno->itens->s133_i_codigo );
        $rsAtributos   = $oDaoExames->sql_record($sSqlAtributos);
        $aValoresAtributos = db_utils::getCollectionByRecord($rsAtributos);
        $sV                = "";
        foreach ($aValoresAtributos as $oAtributo) {

          $oRetorno->itens->valoresatributos .= $sV."{$oAtributo->s132_i_codigo}-{$oAtributo->s134_c_valor}";
          $sV = ",";
        }
      }
    }
  } else {

    $oRetorno->status = 2;
    $oRetorno->message = urlencode(pg_last_error());

  }

} else if ($oParam->exec == "saveExame") {

  db_inicio_transacao ();

  $oDaoAgendaExame = db_utils::getDao("sau_agendaexameconfirma");
  $sSqlAgenda      = $oDaoAgendaExame->sql_query_file(null,"*",null,"s133_i_agendaexames = {$oParam->iExame}");

  $rsAgenda        = $oDaoAgendaExame->sql_record($sSqlAgenda);
  $iNumRowsAgenda  = $oDaoAgendaExame->numrows;
  $oDaoAgendaExame->s133_i_agendaexames = $oParam->iExame;
  $oDaoAgendaExame->s133_c_protocolo    = $oParam->iProtocolo;
  $oDaoAgendaExame->s133_c_observacoes  = "$oParam->sObservacao ";
  $oDaoAgendaExame->s133_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
  $oDaoAgendaExame->s133_c_hora         = db_hora();
  $oDaoAgendaExame->s133_i_login        = db_getsession("DB_id_usuario");
  if ($oDaoAgendaExame->numrows  == 0) {
    $oDaoAgendaExame->incluir(null);
  } else {

    $oAgenda = db_utils::fieldsMemory($rsAgenda, 0);
    $oDaoAgendaExame->s133_i_codigo = $oAgenda->s133_i_codigo;
    $oDaoAgendaExame->alterar($oAgenda->s133_i_codigo);

  }
  if ($oDaoAgendaExame->erro_status == 0) {

    $oRetorno->status  = 2;
    $oRetorno->message = urlencode($oDaoAgendaExame->erro_msg);

  }
  if ($oRetorno->status == 1 ) {
    /*
     * excluimos todos os resultados do exame e incluimos novamente
     */
    $oDaoAgendaExameResultado = db_utils::getDao("sau_agendaexameconfirmaresultado");
    $oDaoAgendaExameResultado->excluir(null,"s134_i_agendaexameconfirma = {$oDaoAgendaExame->s133_i_codigo}");
    if ($oParam->sResultadoExame != "") {

      $aAtributos = explode(",", $oParam->sResultadoExame);
      foreach ($aAtributos as $iAtributo => $sValor) {

        $aValor = explode("-",$sValor);
        $oDaoAgendaExameResultado->s134_i_agendaexameconfirma = $oDaoAgendaExame->s133_i_codigo;
        $oDaoAgendaExameResultado->s134_i_examesatributos     = $aValor[0];
        $oDaoAgendaExameResultado->s134_c_valor               = "$aValor[1]";
        $oDaoAgendaExameResultado->incluir(null);
        if ($oDaoAgendaExameResultado->erro_status == 0) {

          $oRetorno->status  = 2;
          $oRetorno->message = urlencode($oDaoAgendaExameResultado->erro_msg);
          break;

        }
      }
    }
  }
  if ($oRetorno->status == 1) {
    db_fim_transacao(false);
  }else {
    db_fim_transacao(true);
  }
} else if ($oParam->exec == "excluirExame") {

  db_inicio_transacao ();
  $oRetorno->iCGS = $oParam->iCGS;
  $oDaoAgendaExame          = db_utils::getDao("sau_agendaexameconfirma");
  $oDaoAgendaExameResultado = db_utils::getDao("sau_agendaexameconfirmaresultado");
  $oDaoAgendaExameResultado->excluir(null,"s134_i_agendaexameconfirma = {$oParam->iConfirmaExame}");
  if ($oDaoAgendaExameResultado->erro_status == 0) {

    $oRetorno->status  = 2;
    $oRetorno->message = urlencode($oDaoAgendaExameResultado->erro_msg);

  }
  $oDaoAgendaExame->excluir($oParam->iConfirmaExame);
  if ($oDaoAgendaExame->erro_status == 0) {

    $oRetorno->status  = 2;
    $oRetorno->message = urlencode($oDaoAgendaExame->erro_msg);

  }
  if ($oRetorno->status == 1) {
    db_fim_transacao(false);
  }else {
    db_fim_transacao(true);
  }
}

echo $oJson->encode($oRetorno);
?>