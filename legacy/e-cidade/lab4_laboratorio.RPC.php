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

if ($oParam->exec == 'duplicarControleFisFin') {
	
  $aIni               = explode("/",$oParam->la56_d_ini);
  $dIni               = $aIni[2].'-'.$aIni[1].'-'.$aIni[0]; 
  $aFim               = explode("/",$oParam->la56_d_fim);
  $dFim               = $aFim[2].'-'.$aFim[1].'-'.$aFim[0];
  $aIniAlvo           = explode("/",$oParam->la56_d_ini2);
  $dIniAlvo           = $aIniAlvo[2].'-'.$aIniAlvo[1].'-'.$aIniAlvo[0];
  $aFimAlvo           = explode("/",$oParam->la56_d_fim2);
  $dFimAlvo           = $aFimAlvo[2].'-'.$aFimAlvo[1].'-'.$aFimAlvo[0];
  
  $oDaolab_ctrlfisfin = db_utils::getdao('lab_controlefisicofinanceiro');

  db_inicio_transacao();

  $sWhere   = " la56_d_ini IS NOT NULL ";
  $sWhere  .= " and la56_d_fim IS NOT NULL ";
  $sWhere  .= " and la56_d_ini >= '$dIni' ";
  $sWhere  .= " and la56_d_fim <= '$dFim' ";
  $sSql     = $oDaolab_ctrlfisfin->sql_query_file("","lab_controlefisicofinanceiro.*","",$sWhere);
  $rsResult = $oDaolab_ctrlfisfin->sql_record($sSql);
  if ($oDaolab_ctrlfisfin->numrows > 0) {
        
    $iTam = $oDaolab_ctrlfisfin->numrows;
    for ($iInd=0; $iInd < $iTam; $iInd++) {
      
      $oControle                                           = db_utils::fieldsmemory($rsResult,$iInd);
      $oDaolab_ctrlfisfin->la56_i_codigo                   = $oControle->la56_i_codigo;
      $oDaolab_ctrlfisfin->la56_i_laboratorio              = $oControle->la56_i_laboratorio;
      $oDaolab_ctrlfisfin->la56_i_formaorganizacao         = $oControle->la56_i_formaorganizacao;
      $oDaolab_ctrlfisfin->la56_i_depto                    = $oControle->la56_i_depto;
      $oDaolab_ctrlfisfin->la56_i_exame                    = $oControle->la56_i_exame;
      $oDaolab_ctrlfisfin->la56_i_grupo                    = $oControle->la56_i_grupo;
      $oDaolab_ctrlfisfin->la56_i_teto                     = $oControle->la56_i_teto;
      $oDaolab_ctrlfisfin->la56_n_limite                   = $oControle->la56_n_limite;
      $oDaolab_ctrlfisfin->la56_i_periodo                  = $oControle->la56_i_periodo;
      $oDaolab_ctrlfisfin->la56_i_tipocontrole             = $oControle->la56_i_tipocontrole;
      $oDaolab_ctrlfisfin->la56_i_liberarequisicaosemsaldo = $oControle->la56_i_liberarequisicaosemsaldo;
      $oDaolab_ctrlfisfin->la56_d_ini                      = $dIniAlvo;
      $oDaolab_ctrlfisfin->la56_i_subgrupo                 = $oControle->la56_i_subgrupo;
      $oDaolab_ctrlfisfin->la56_d_fim                      = $dFimAlvo; 
      $oDaolab_ctrlfisfin->incluir(null);
      if ($oDaolab_ctrlfisfin->erro_status == "0") {
        break;
      }
    
    }
      
  } else {
    
    $oDaolab_ctrlfisfin->erro_status = "0";
    $oDaolab_ctrlfisfin->erro_msg    = "Nenhum registro de controle no periodo anterior!";
    
  }
  if ($oDaolab_ctrlfisfin->erro_status == "0") {
    
    db_fim_transacao(true);
    $oRetorno->iStatus  = 0;
    
  }else{
    db_fim_transacao(false);
  }
  $oRetorno->sMessage = urlencode($oDaolab_ctrlfisfin->erro_msg);

} elseif ($oParam->exec == 'incAltControleFisicoFinanceiro') {

  $oDaoLabControleFisicoFinanceiro = db_utils::getdao('lab_controlefisicofinanceiro');

  db_inicio_transacao();

  $oDaoLabControleFisicoFinanceiro->la56_i_grupo                    = $oParam->la56_i_grupo;
  $oDaoLabControleFisicoFinanceiro->la56_i_subgrupo                 = $oParam->la56_i_subgrupo;
  $oDaoLabControleFisicoFinanceiro->la56_i_formaorganizacao         = $oParam->la56_i_formaorganizacao;
  $oDaoLabControleFisicoFinanceiro->la56_i_exame                    = $oParam->la56_i_exame;
  $oDaoLabControleFisicoFinanceiro->la56_i_laboratorio              = $oParam->la56_i_laboratorio;
  $oDaoLabControleFisicoFinanceiro->la56_i_depto                    = $oParam->la56_i_depto;
  $oDaoLabControleFisicoFinanceiro->la56_i_teto                     = $oParam->la56_i_teto;
  $oDaoLabControleFisicoFinanceiro->la56_n_limite                   = $oParam->la56_n_limite;
  $oDaoLabControleFisicoFinanceiro->la56_i_periodo                  = $oParam->la56_i_periodo;
  $oDaoLabControleFisicoFinanceiro->la56_d_ini                      = formataData($oParam->la56_d_ini);
  $oDaoLabControleFisicoFinanceiro->la56_d_fim                      = formataData($oParam->la56_d_fim);
  $oDaoLabControleFisicoFinanceiro->la56_i_liberarequisicaosemsaldo = $oParam->la56_i_liberarequisicaosemsaldo;
  if ($oParam->sOperacao == 'incluir') {

    $oDaoLabControleFisicoFinanceiro->la56_i_tipocontrole = $oParam->iTipoControle;
    $oDaoLabControleFisicoFinanceiro->incluir(null);

  } else {

    $oDaoLabControleFisicoFinanceiro->la56_i_codigo = $oParam->la56_i_codigo;
    $oDaoLabControleFisicoFinanceiro->alterar($oParam->la56_i_codigo);

  }

  $oRetorno->sMessage = urlencode($oDaoLabControleFisicoFinanceiro->erro_msg);
  if ($oDaoLabControleFisicoFinanceiro->erro_status == '0') {
  
    $oRetorno->iStatus = 0;
    db_fim_transacao(true);
  
  } else {

    $oRetorno->iCodigo = $oDaoLabControleFisicoFinanceiro->la56_i_codigo;
    db_fim_transacao(false);
  
  }

} elseif ($oParam->exec == 'excluirControleFisicoFinanceiro') {

  $oDaoLabControleFisicoFinanceiro = db_utils::getdao('lab_controlefisicofinanceiro');

  db_inicio_transacao();
     
  $oDaoLabControleFisicoFinanceiro->excluir($oParam->la56_i_codigo);
  
  if ($oDaoLabControleFisicoFinanceiro->erro_status == '0') {
  
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode($oDaoLabControleFisicoFinanceiro->erro_msg);
    db_fim_transacao(true);
  
  } else { 

    $oRetorno->iCodigo  = $oDaoLabControleFisicoFinanceiro->la56_i_codigo;
    $oRetorno->sMessage = urlencode($oDaoLabControleFisicoFinanceiro->erro_msg);
    db_fim_transacao(false);
  
  }

} elseif ($oParam->exec == 'getInfoControleFisicoFinanceiro') {

  $oDaoLabControleFisicoFinanceiro = db_utils::getdao('lab_controlefisicofinanceiro');

  $sWhere = '';
  if ($oParam->iTipoControle == 1 || $oParam->iTipoControle == 2 || $oParam->iTipoControle == 3
      || $oParam->iTipoControle == 9) {
    $sWhere .=  ' la56_i_depto = '.$oParam->iLabDepto;
  } elseif ($oParam->iTipoControle == 4 || $oParam->iTipoControle == 5 || $oParam->iTipoControle == 6) {
    $sWhere .=  ' la56_i_laboratorio = '.$oParam->iLabDepto;
  }

  $sSql    = $oDaoLabControleFisicoFinanceiro->sql_query_controle(null, "*", 'la56_i_codigo desc', $sWhere);
  $rs      = $oDaoLabControleFisicoFinanceiro->sql_record($sSql);

  if ($oDaoLabControleFisicoFinanceiro->numrows > 0) {
    $oRetorno->aControles = db_utils::getColectionByRecord($rs, false, false, true);
  } else {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode('Nenhuma informação de controle físico / financeiro encontrada.');

  }
     
} elseif ($oParam->exec == 'getValorProcedimento') {

  $mAno = isset($oParam->iAno) ? $oParam->iAno : 'null';
  $mMes = isset($oParam->iMes) ? $oParam->iMes : 'null';
  $rs   = db_query("select fc_get_valor_procedimento('".$oParam->sProcedimento."', $mAno, $mMes) as valor;");
  $iLin = pg_num_rows($rs);
  if ($iLin > 0) {
    $oRetorno->nValor = db_utils::fieldsmemory($rs, 0)->valor;
  } else {

    $oRetorno->nValor   = '';
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = 'Erro ao executar a busca: '.pg_last_error();

  }
  $oRetorno->sProcedimento = $oParam->sProcedimento;
     
} elseif ($oParam->exec == 'verificarSaldoExame') {
     
  require_once('model/controleexameslaboratorio.model.php');

  $sMsg                       = '';
  $oRetorno->lLiberarSemSaldo = false;
  $oRetorno->lSaldoSuficiente = true;
  $aInfoReq                   = array();
  for ($i = 0; $i < count($oParam->iSetorExame); $i++) {

    $dData           = formataData($oParam->dData[$i]);
    $oControleExames = new controleExamesLaboratorio($oParam->iSetorExame[$i]);
    $nQtdReq         = 0;
    if ($oControleExames->getTipoControle() != 0) { // Possui um tipo de controle

      $oInfoControle = $oControleExames->getInfoControle($dData);
      if ($oInfoControle != null) { // Possui informação de controle

         if ($oInfoControle->la56_i_teto == 1) { // Teto físico
           $nQtdeReq = 1;
         } else { // Teto financeiro

           $nQtdeReq = $oControleExames->getValorProcedimento() 
                       + $oControleExames->getAcrescimoProcedimento();

        }

        $lNovo = true;
        foreach ($aInfoReq as $oInfoReq) { // Verifica se já existe algum exame que se enquadrou no mesmo controle
        
          if ($oInfoReq->oInfoControle->la56_i_codigo == $oInfoControle->la56_i_codigo) { // Mesmas info de controle
            
            /* Agora tenho que verificar se o saldo vai ser descontado descontado do mesmo saldo do que já existe */
            if ($oInfoControle->la56_i_periodo == 2) { // Mensal

              /* Em controle mensal, a data do exame no objeto oInfoReq será somente uma também, pois para agrupar,
                 basta que a data da nova requisição sendo avaliada esteja no mesmo mês de requisições (a contar 
                 da data de início do controle) */
              
              /* Obtenho as datas de início e fim do mês do controle para o qual a requição vai contar */
              $tNovo       = strtotime($dData);
              $sDiaIniNovo = substr($oInfoControle->la56_d_ini, 8, 2);
              $sDiaNovo    = date('d', $tNovo);
              $sMesNovo    = date('m', $tNovo);
              $sAnoNovo    = date('Y', $tNovo);
              if ($sDiaNovo < $sDiaIniNovo) {
                $dIniNovo = date('Y-m-d', strtotime("$sAnoNovo-$sMesNovo-$sDiaIniNovo -1 month"));
              } else {
                $dIniNovo = date('Y-m-d', strtotime("$sAnoNovo-$sMesNovo-$sDiaIniNovo"));
              }
              $dFimNovo = date('Y-m-d', strtotime("$dIniNovo +1 month -1 day"));

              /* Obtenho as datas de início e fim do mês do controle para o qual a requição já analisada vai contar */
              $tVelho       = strtotime($oInfoReq->dDataExame);
              $sDiaIniVelho = substr($oInfoReq->oInfoControle->la56_d_ini, 8, 2);
              $sDiaVelho    = date('d', $tNovo);
              $sMesVelho    = date('m', $tNovo);
              $sAnoVelho    = date('Y', $tNovo);
              if ($sDiaVelho < $sDiaIniVelho) {
                $dIniVelho = date('Y-m-d', strtotime("$sAnoVelho-$sMesVelho-$sDiaIniVelho -1 month"));
              } else {
                $dIniVelho = date('Y-m-d', strtotime("$sAnoVelho-$sMesVelho-$sDiaIniVelho"));
              }
              $dFimVelho = date('Y-m-d', strtotime("$dIniVelho +1 month -1 day"));

              /* Verifico se as duas requisições sendo analisadas vão contar para o mesmo mês de controle */
              if ($dIniNovo == $dIniVelho && $dFimNovo == $dFimVelho) { // Vão descontar do mesmo saldo

                $oInfoReq->nQtdeReq += $nQtdeReq;
                $lNovo               = false;
                break;

              }

            
            } else { // Diário


              if ($dData == $oInfoReq->dDataExame) { // Se for diário, só desconta do mesmo saldo se for no mesmo dia

                $oInfoReq->nQtdeReq += $nQtdeReq;
                $lNovo               = false;
                break;

              }

            }

          }

        }
        if ($lNovo) { // Não foi agrupado, ou seja a quantidade da requisicao não vai descontar do mesmo saldo de outra

          $iInd                           = count($aInfoReq);
          $aInfoReq[$iInd]                = new stdClass();
          $aInfoReq[$iInd]->oInfoControle = $oInfoControle;
          $aInfoReq[$iInd]->nQtdeReq      = $nQtdeReq; 
          $aInfoReq[$iInd]->dDataExame    = $dData; 
          $aInfoReq[$iInd]->nSaldoGasto   = $oControleExames->getSaldoGasto($oInfoControle, $dData); 

        }

      } else { // Existe controle físico financeiro, mas nenhum se encaixa para o exame em questão. Bloqueia a req.

        $sMsg = urlencode('Existe controle mas nenhum foi lançado para algum exame(s) da requisição. '.
                          'Impossível efetuar requisição.'
                         );
        /* Impeço requisição */
        $oRetorno->lSaldoSuficiente = false;
        break;

      }

    } else { // Não possui controle físico / financeiro, então o saldo é liberado

      $sMsg = urlencode('Não existe controle. Saldo liberado');
      break;

    }

  }

  /* Verifico se vai ter saldo suficiente para todas as requisições. Se o saldo for insuficiente para alguma requisição
     e não for pra liberar requisição sem saldo, bloqueia todas as requisições. Senão, libera. */
  foreach ($aInfoReq as $oInfoReq) {

    // Verifico se possui saldo suficiente
    if ($oInfoReq->oInfoControle->la56_n_limite >= ($oInfoReq->nQtdeReq + $oInfoReq->nSaldoGasto)) {
      continue; 
    } elseif($oInfoReq->oInfoControle->la56_i_liberarequisicaosemsaldo == 1) { // Saldo insuficiente, 
                                                                                // mas permite lib. req. sem saldo
      $oRetorno->lLiberarSemSaldo = true;
      $oRetorno->lSaldoSuficiente = false;

    } else { // Não possui saldo suficiente e nem permite liberar req. sem saldo, então, bloqueia req.

      $oRetorno->lLiberarSemSaldo = false;
      $oRetorno->lSaldoSuficiente = false;
      $sMsg                       = urlencode('Requisição possui exame(s) com saldo insuficiente. '.
                                              'Impossível efetuar requisição'
                                             );
      break;

    }

  }

  if ($oRetorno->lLiberarSemSaldo) {

    $sMsg = urlencode('Requisição possui exame(s) com saldo insuficiente porém o parâmetro '.
                      'para liberar a requisição sem saldo está "SIM".'
                     );

  }
  $oRetorno->sMessage = $sMsg;

/*
  $dData           = formataData($oParam->dData[0]);
  $oControleExames = new controleExamesLaboratorio($oParam->iSetorExame[0]);
  // Seto alguns valores de retorno padrões 
  $oRetorno->iTeto                   = 1;
  $oRetorno->lLiberarSemSaldo        = 'false';
  $oRetorno->sProcedimento           = '';
  $oRetorno->nValorTotalProcedimento = 0;

  if ($oControleExames->getTipoControle() == 0) { // Não tem nenhum tipo de controle, saldo liberado

    $oRetorno->sMessage         = urlencode('Não existe controle. Saldo liberado.');
    $oRetorno->nSaldo           = 1; // Seto para 1, mas na verdade é infinito
    $oRetorno->nSaldoGasto      = $oControleExames->getNumeroExamesAgendados($dData);
    $oRetorno->lSaldoSuficiente = 'true';

  } else {

    $oInfoControle = $oControleExames->getInfoControle($dData);
    
    if ($oInfoControle == null) {

      $oRetorno->sMessage = urlencode('Existe controle mas nenhum foi lançado. Saldo bloqueado.');
      $oRetorno->nSaldo   = 0; // Seto para 1, mas na verdade é infinito
      // Na verdade pode ter pac. agend., mas sem a info de controle não dá pra determinar saldo gasto
      $oRetorno->nSaldoGasto      = 0;
      $oRetorno->lSaldoSuficiente = 'false';

    } else {

      $oRetorno->nSaldoGasto      = $oControleExames->getSaldoGasto($oInfoControle, $dData);
      $oRetorno->nSaldo           = $oInfoControle->la56_n_limite - $oRetorno->nSaldoGasto;
      $oRetorno->iTeto            = $oInfoControle->la56_i_teto;
      $oRetorno->lLiberarSemSaldo = $oInfoControle->la56_i_liberarequisicaosemsaldo == 1 ? 'true' : 'false';

      if ($oInfoControle->la56_i_teto == 1) { // Teto físico

        $oRetorno->lSaldoSuficiente =  $oRetorno->nSaldo > 0 ? 'true' : false;

      } else { // Teto financeiro
         
        $oRetorno->sProcedimento           = $oControleExames->getProcedimento();
        $oRetorno->nValorTotalProcedimento = $oControleExames->getValorProcedimento() 
                                             + $oControleExames->getAcrescimoProcedimento();
        // Verifico se possui saldo suficiente para lançar mais um exame
        if ($oRetorno->nValorTotalProcedimento <= $oRetorno->nSaldo) { // Suficiente
          $oRetorno->lSaldoSuficiente = 'true';
        } else { // Insuficiente
          $oRetorno->lSaldoSuficiente = 'false';
        }

      }

    }

  }
*/
}

echo $oJson->encode($oRetorno);
?>