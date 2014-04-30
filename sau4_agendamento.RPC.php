<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once('libs/db_stdlib.php');
require_once('libs/db_stdlibwebseller.php');
require_once('libs/db_utils.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/JSON.php');
require_once('dbforms/db_funcoes.php');
require_once("classes/db_agendamentos_ext_classe.php");

function formataData($dData, $iTipo = 1) {

  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];

 return $dData;

}

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

if ($oParam->exec == 'transferirAgendamentos') {

  $oDaoAgendamentos = db_utils::getdao('agendamentos');

  /*
  $aDados[$iCont]->sd23_i_codigo => código do agendamento sendo transferido
  $aDados[$iCont]->sd23_d_consulta => nova data de consulta
  $aDados[$iCont]->sd23_i_ficha => id da nova ficha do agendamento
  $aDados[$iCont]->sd23_c_hora => novo horário do agendamento
  $aDados[$iCont]->sd23_i_undmedhor => novo código da grade de horário do agendamento */
  $aDados = $oParam->aDadosTransferencia;

  db_inicio_transacao();
  $iTam   = count($aDados);
  for ($iCont = 0; $iCont < $iTam; $iCont++) {
     
    $oDaoAgendamentos->sd23_i_codigo    = $aDados[$iCont]->sd23_i_codigo;
    $oDaoAgendamentos->sd23_d_consulta  = $aDados[$iCont]->sd23_d_consulta;
    $oDaoAgendamentos->sd23_i_ficha     = $aDados[$iCont]->sd23_i_ficha;
    $oDaoAgendamentos->sd23_c_hora      = $aDados[$iCont]->sd23_c_hora;
    $oDaoAgendamentos->sd23_i_undmedhor = $aDados[$iCont]->sd23_i_undmedhor;
    $oDaoAgendamentos->alterar($aDados[$iCont]->sd23_i_codigo);
    if ($oDaoAgendamentos->erro_status == '0') {
      break;
    }

  }

  if ($oDaoAgendamentos->erro_status == '0') {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode($oDaoAgendamentos->erro_msg);
    db_fim_transacao(true);

  } else {
    db_fim_transacao(false);
  }

} elseif ($oParam->exec == 'agendarPaciente') {

  $oDaoAgendamentos     = db_utils::getdao('agendamentos');
  $oDaoProntuarios      = db_utils::getdao('prontuarios');
  $oDaoProntagendamento = db_utils::getdao('prontagendamento');
  $oDaoProntprofatend   = db_utils::getdao('prontprofatend');

  /*
   * =====================================================
   *   TESTA PARA VER SE O AGENDAMENTO É FEITO POR COTAS
   * =====================================================
   */
  $lAgendaLiberada = true;
  $lHorarioOcupado = false;
  $iUpssolicitante = db_getsession("DB_coddepto");
  $vet             = explode('/', $oParam->sd23_d_consulta);
  $clagendamentos  = new cl_agendamentos_ext;
  if ($iUpssolicitante != $oParam->sd02_i_codigo) {

    $oResult = getCotasAgendamento($iUpssolicitante, $oParam->sd02_i_codigo, $oParam->rh70_estrutural, $vet[2], $vet[1],
                                   $oParam->sd23_i_undmedhor); 
    $dIni    = "$vet[2]-$vet[1]-1";
    $dFim    = "$vet[2]-$vet[1]-";
    $dFim   .= date("t", strtotime("$vet[2]-$vet[1]-1"));
    if ($oResult->lStatus == 1) {
    
      $sSubSqlWhere        = " sd27_i_rhcbo = ".$oParam->rh70_sequencial;
      $sSubSqlWhere       .= " and sd23_i_upssolicitante = ".$iUpssolicitante;
      $sSubSqlWhere       .= " and sd04_i_unidade = ".$oParam->sd02_i_codigo;
      $sSubSqlWhere       .= " and sd23_d_consulta between '$dIni' and '$dFim' ";
      $sSubSqlWhere       .= " and not EXISTS ( select * from agendaconsultaanula where"; 
      $sSubSqlWhere       .= " s114_i_agendaconsulta = sd23_i_codigo ) ";
      $sSubSql             = $clagendamentos->sql_query_consulta_geral("", "count(sd23_i_codigo) as iAgendados", "",
                                                                       $sSubSqlWhere);
      $rs                  = $clagendamentos->sql_record($sSubSql);
      $oAgendamentosAnt    = db_utils::getColectionByRecord($rs, false, false, true);
      
      $sSubSqlWhere       .= " and sd23_i_undmedhor = ".$oParam->sd23_i_undmedhor;
      $sSubSql             = $clagendamentos->sql_query_consulta_geral("", "count(sd23_i_codigo) as iAgendados", "", 
                                                                     $sSubSqlWhere);
      $rs                  = $clagendamentos->sql_record($sSubSql);
      $oAgendamentosAntMed = db_utils::getColectionByRecord($rs, false, false, true);

      if ($clagendamentos->numrows > 0) {
        if ($oResult->aCotasAgendamento[0]->saldo_medico != null) {
          $iSaldo = (int)$oResult->aCotasAgendamento[0]->saldo_medico - (int)$oAgendamentosAnt[0]->iagendados;
        } else {
          $iSaldo = (int)$oResult->aCotasAgendamento[0]->s163_i_quantidade- (int)$oAgendamentosAnt[0]->iagendados;
        }
      }
      if ($iSaldo <= 0) {
        $lAgendaLiberada = false;
      } else {
        $lAgendaLiberada = true;
      }

    }
  
  } else {

    $sCampos  = "fc_saldoCotasPrestEspecComp";
    $sCampos .= "($oParam->sd02_i_codigo, '".$oParam->rh70_estrutural."', ".$vet[1].", ".$vet[2].") as saldo";
    $sSql     = "SELECT ";
    $sSql    .= $sCampos;
    $rs       = pg_query($sSql);

    if (pg_num_rows($rs) > 0) {
      
      $oSaldoAgendamento  = db_utils::fieldsMemory($rs, 0);
      $iSaldoCotas        = $oSaldoAgendamento->saldo;
      if ($iSaldoCotas <= 0) {
        $lAgendaLiberada = false;
      } else {
        $lAgendaLiberada = true;
      }
      
    }
    
  }
  
  if ($lAgendaLiberada == true) {

    $lHorarioOcupado = !validaSaldo($oParam->sd30_c_tipograde, $oParam->sd23_i_undmedhor, 
                                    formataData($oParam->sd23_d_consulta), $oParam->sd23_i_ficha
                                   );

  }
  
  if ($lAgendaLiberada && !$lHorarioOcupado) {

    db_inicio_transacao();

    $oDaoAgendamentos->sd23_i_usuario        = db_getsession('DB_id_usuario');
    $oDaoAgendamentos->sd23_d_agendamento    = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoAgendamentos->sd23_d_cadastro       = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoAgendamentos->sd23_d_consulta       = formataData($oParam->sd23_d_consulta);
    $oDaoAgendamentos->sd23_i_situacao       = 1;
    $oDaoAgendamentos->sd23_i_numcgs         = $oParam->iCgs;
    $oDaoAgendamentos->sd23_i_ficha          = $oParam->sd23_i_ficha;
    $oDaoAgendamentos->sd23_c_hora           = $oParam->sd23_c_hora;
    $oDaoAgendamentos->sd23_i_undmedhor      = $oParam->sd23_i_undmedhor;
    $oDaoAgendamentos->sd23_i_upssolicitante = $iUpssolicitante;
    $oDaoAgendamentos->incluir(null);

    if ($oDaoAgendamentos->erro_status != '0') {

      //gera numatend
      $sSql        = "select fc_numatend()";
      $rsResult    = pg_query($sSql) or die (pg_errormessage());
      $fc_numatend = explode(",", pg_result($rsResult, 0, 0));
      
      //incluir FAA
      $oDaoProntuarios->sd24_i_ano      = trim ( $fc_numatend [0] );
      $oDaoProntuarios->sd24_i_mes      = trim ( $fc_numatend [1] );
      $oDaoProntuarios->sd24_i_seq      = trim ( $fc_numatend [2] );
      $oDaoProntuarios->sd24_i_login    = DB_getsession ( "DB_id_usuario" );
      $oDaoProntuarios->sd24_i_unidade  = $oParam->sd02_i_codigo;
      $oDaoProntuarios->sd24_i_numcgs   = $oParam->iCgs;
      $oDaoProntuarios->sd24_d_cadastro = $oParam->sd23_d_consulta;
      $oDaoProntuarios->sd24_c_cadastro = $oParam->sd23_c_hora;
      $oDaoProntuarios->incluir (null);
      if ($oDaoProntuarios->erro_status == '0') {

        $oRetorno->sMessage = urlencode($oDaoProntuarios->erro_msg);
        $oRetorno->iStatus  = 0;
        db_fim_transacao(true);

      } else {

        //incluir prontagendamento
        $oDaoProntagendamento->s102_i_agendamento = $oDaoAgendamentos->sd23_i_codigo;
        $oDaoProntagendamento->s102_i_prontuario  = $oDaoProntuarios->sd24_i_codigo;
        $oDaoProntagendamento->incluir (null);
        if ($oDaoProntagendamento->erro_status == '0') {

          $oRetorno->sMessage = urlencode($oDaoProntagendamento->erro_msg);
          $oRetorno->iStatus  = 0;
          db_fim_transacao(true);

        } else {

          //Incluir profissional
          $oDaoProntprofatend->s104_i_prontuario   = $oDaoProntuarios->sd24_i_codigo;
          $oDaoProntprofatend->s104_i_profissional = $oParam->sd27_i_codigo;
          $oDaoProntprofatend->incluir (null);
          if ($oDaoProntprofatend->erro_status == '0') {

            $oRetorno->sMessage = urlencode($oDaoProntprofatend->erro_msg);
            $oRetorno->iStatus  = 0;
            db_fim_transacao(true);

          }else{

            $oRetorno->iCodigo  = $oDaoAgendamentos->sd23_i_codigo;
            $oRetorno->sMessage = urlencode($oDaoAgendamentos->erro_msg);
            db_fim_transacao(false);

          }

        }

      }

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode($oDaoAgendamentos->erro_msg);
      db_fim_transacao(true);

    }

  } else {
    
    $oRetorno->iStatus = 0;
    if ($lHorarioOcupado) {
      $oRetorno->sMessage = urlencode('Horário selecionado já foi agendado.');
    } elseif (!$lAgendaLiberada) {
      $oRetorno->sMessage = urlencode('Saldo insuficiente para agendamento.');
    }

  }

} elseif ($oParam->exec == 'marcarPresencaAgendamentos') {

  $oDaoAgendamentos = db_utils::getdao('agendamentos');
  $aAgendamentos    = explode(',', $oParam->sAgendamentos);

  db_inicio_transacao();
  $iTam             = count($aAgendamentos);
  for ($iCont = 0; $iCont < $iTam; $iCont++) {

    $oDaoAgendamentos->sd23_i_codigo   = $aAgendamentos[$iCont];
    $sSqlPresenca                      = '(select case when sd23_i_presenca = 1 then 2 else 1 end as p from ';
    $sSqlPresenca                     .= 'agendamentos where sd23_i_codigo ='.$aAgendamentos[$iCont].')';
    $oDaoAgendamentos->sd23_i_presenca = $sSqlPresenca;
    $oDaoAgendamentos->alterar($aAgendamentos[$iCont]);

    if ($oDaoAgendamentos->erro_status == '0') {

      $oRetorno->iStatus  = 0;
      break;

    }

  }
  
  $oRetorno->sMessage = urlencode($oDaoAgendamentos->erro_msg);

  db_fim_transacao($oDaoAgendamentos->erro_status == '0' ? true : false);

} elseif ($oParam->exec == 'lancarObservacaoAgendamentos') {

  $oDaoAgendamentos = db_utils::getdao('agendamentos');
  $iTam             = count($oParam->aAgendamentos);

  db_inicio_transacao();
  for ($iCont = 0; $iCont < $iTam; $iCont++) {

    $oDaoAgendamentos->sd23_i_codigo = $oParam->aAgendamentos[$iCont]->iCodigo;
    $oDaoAgendamentos->sd23_t_obs    = TiraAcento(converteCodificacao($oParam->aAgendamentos[$iCont]->sObs), false);
    $oDaoAgendamentos->alterar($oParam->aAgendamentos[$iCont]);

    if ($oDaoAgendamentos->erro_status == '0') {

      $oRetorno->iStatus  = 0;
      break;

    }

  }
  
  $oRetorno->sMessage = urlencode($oDaoAgendamentos->erro_msg);

  db_fim_transacao($oDaoAgendamentos->erro_status == '0' ? true : false);

} elseif ($oParam->exec == 'getProcedimentosPadraoProfissional') {

  $oDaoSauProcedMedAgendamento = db_utils::getdao('sau_procedmedagendamento');
  $sCampos                     = 'sd63_i_codigo, sd63_c_procedimento, sd63_c_nome ';
  $sSql                        = $oDaoSauProcedMedAgendamento->sql_query(null, $sCampos, '',
                                                                         's156_i_especmed = '.$oParam->iEspecMed
                                                                        );
  $rs                          = $oDaoSauProcedMedAgendamento->sql_record($sSql);

  if ($oDaoSauProcedMedAgendamento->numrows > 0) {
    $oRetorno->aProcedimentos = db_utils::getColectionByRecord($rs, false, false, true);
  } else {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = 'Nenhum procedimento padrao para este profissional.';

  }
 

} elseif ($oParam->exec == 'getCotasSolicitante') {

  $oDaoCotasAgendamento = db_utils::getdao('sau_cotasagendamento');
  $sCampos              = ' sd63_i_codigo, sd63_i_quantidade ';
  $sWhere               = " sd63_i_upssolicitante = $oParam->upssolicitante ";
  $sWhere              .= " AND sd63_i_rhcbo =  $oParam->rhcbo";
  $sWhere              .= " AND sd63_i_anocomp =  $oParam->->anocomp";
  $sWhere              .= " AND sd63_i_mescomp =  $oParam->mescomp";
  $sWhere              .= (isset($oParam->upsprestadora) && !empty($oParam->upsprestadora)) ? 
                          " AND sd63_i_upsprestadora = $oParam->upsprestadora" : "";
  $sSql                 = $oDaoCotasAgendamento->sql_query(null, $sCampos, null, $sWhere);
  $rs                   = $oDaoCotasAgendamento->sql_record($sSql);

  if ($oDaoCotasAgendamento->numrows > 0) {
    
    $oRetorno->aCotasAgendamento = db_utils::getColectionByRecord($rs, false, false, true);
  
  } else {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = 'Não encontrado o controle por cotas para a UPS solicitante.';

  }
 

}

echo $oJson->encode($oRetorno);
?>