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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson             = new services_json();

$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno          = new stdClass();

$oDaoArreCad  = db_utils::getDao('arrecad');

$oRetorno->status  = 1;
$oRetorno->message = '';
$sMsg              = '';
$lErro             = false;
$dtHoje            = date("Y-m-d", db_getsession("DB_datausu"));

switch ($oParam->sExec) {
  
  /*
   *  1 - lista débitos da matricula e tipo selecionado
   *  2 - lista os históricos para lançar o desconto (histcalc)
   */
  
  case 'getDebitosPorTipo':
    
    $oDaoClaArrematric = db_utils::getDao('arrematric');
    $oDaoClaHistCalc   = db_utils::getDao('histcalc');
    
    unset($sSql,$aRegistros, $oResulDebitos, $oResulDebitosA, $aDebitosResult);
    
    $sSql = "    arrematric.k00_matric = $oParam->iMatricula
             and arrecad.k00_tipo      = $oParam->iTipoDebito";
    
    if ($oParam->iReceitaDebito != 0) {
      
      $sSql .= " and arrecad.k00_receit    = $oParam->iReceitaDebito";
    }
    
    $sSqlDaoClaArrecad = $oDaoClaArrematric->sql_query_info("",
                                                            "",
                                                            "distinct
                                                             arrecad.k00_numpre, arrecad.k00_numpar,
                                                             arrecad.k00_receit, arretipo.k00_descr,
                                                             arrecad.k00_dtvenc, arrecad.k00_valor,
                                                             arrecad.k00_hist",
                                                            "",
                                                            $sSql
                                                           );
    
     $rsDaoClaArrecad   = $oDaoClaArrematric->sql_record($sSqlDaoClaArrecad);
    if ($oDaoClaArrematric->numrows > 0) {
      
      $aRegistros    = db_utils::getCollectionByRecord($rsDaoClaArrecad, false, false, true);
      
      /**
       * prepara o retorno caso numpre já exista algum desconto.
       */
      
      $oResulDebitos = array();
      
      foreach ($aRegistros as $iIndice => $oDebitos) {
        
        $sSqlDaoArrecad = 
          $oDaoArreCad->sql_query_info("",
                                       "arrecad.k00_hist",
                                       "",
                                       "    arrecad.k00_numpre = $oDebitos->k00_numpre
                                        and arrecad.k00_numpar = $oDebitos->k00_numpar
                                        and arrecad.k00_receit = $oDebitos->k00_receit
                                        and arrecad.k00_valor  > 0");
        
        $rsDaoArrecad   = $oDaoArreCad->sql_record($sSqlDaoArrecad);
        
        if ($oDaoArreCad->numrows > 0) {
          
          $oResulCodHist = db_utils::fieldsMemory($rsDaoArrecad,0);
          
        }
        
        
        if (array_key_exists($oDebitos->k00_numpre, $oResulDebitos)) {
          
          $oResulDebitos[$oDebitos->k00_numpre]['k00_valor'] =
            db_formatar((float)$oResulDebitos[$oDebitos->k00_numpre]['k00_valor'] + (float)$oDebitos->k00_valor, 'p');
        } else {
        
          $oResulDebitos[$oDebitos->k00_numpre]['k00_numpre'] = $oDebitos->k00_numpre;
          $oResulDebitos[$oDebitos->k00_numpre]['k00_numpar'] = $oDebitos->k00_numpar;
          $oResulDebitos[$oDebitos->k00_numpre]['k00_receit'] = $oDebitos->k00_receit;
          $oResulDebitos[$oDebitos->k00_numpre]['k00_descr']  = $oDebitos->k00_descr;
          $oResulDebitos[$oDebitos->k00_numpre]['k00_dtvenc'] = $oDebitos->k00_dtvenc;
          $oResulDebitos[$oDebitos->k00_numpre]['k00_valor']  = db_formatar($oDebitos->k00_valor, 'p');
          $oResulDebitos[$oDebitos->k00_numpre]['k00_hist']   = $oResulCodHist->k00_hist;
        }
      }
      
      foreach ($oResulDebitos as $key => $value)
      {
        
         $oResulDebitosA = new stdClass();	
         $oResulDebitosA->k00_numpre = $value['k00_numpre'];
         $oResulDebitosA->k00_numpar = $value['k00_numpar'];
         $oResulDebitosA->k00_receit = $value['k00_receit'];
         $oResulDebitosA->k00_descr  = $value['k00_descr'];
         $oResulDebitosA->k00_dtvenc = $value['k00_dtvenc'];
         $oResulDebitosA->k00_valor  = $value['k00_valor'];
         $oResulDebitosA->k00_hist   = $value['k00_hist'];
         $aDebitosResult[]           = $oResulDebitosA;
        
      }
     
      $oRetorno->aDebitos = $aDebitosResult;
      
      $sSqlDaoHistCalc       = $oDaoClaHistCalc->sql_query("", "k01_codigo, k01_descr", "k01_descr", "", "");
      $rsDaoClaHistCalc      = $oDaoClaHistCalc->sql_record($sSqlDaoHistCalc);
      $oRetorno->aHistoricos = db_utils::getCollectionByRecord($rsDaoClaHistCalc, false, false, true);
      
      $oRetorno->status  = 1;
      
    } else {
      
      $oRetorno->status  = 2;
      
      $oRetorno->message = 'Nenhum registro encontrado.';
      
    }
    
    break;  
    
   
  case 'processaDescontos':
    
  	if (!empty($oParam->aDebitos)) {
      
      $oDaoArreHist = db_utils::getDao('arrehist');
      
      /**
       * classes que guardam uma cópia do desconto lançado 
       * para depois ser usado quando rodar o calculo da agua
       */
      $oDaoAguaDescArrecad  = db_utils::getDao('aguadescarrecad');
      $oDaoAguaDescArreHist = db_utils::getDao('aguadescarrehist');
      
      $erro = false;
      
      db_inicio_transacao();
      
      unset($oDebito);
      
      foreach ($oParam->aDebitos as $oDebito) {
        
        unset($oResultado);
        
        $iNumpre         = $oDebito[1];
        $iNumpar         = $oDebito[2];
        $iReceita        = $oDebito[3];
        $fValorParcela   = $oDebito[4];
        $iHistorico      = $oDebito[6];
        
        unset($sSqlArrecad);
        
        $sSqlArrecad  = "    arrecad.k00_numpre = $iNumpre
                         AND arrecad.k00_numpar = $iNumpar";
        
        if ($iReceita != '' and $iReceita != 0) {
          
          $sSqlArrecad  .= " AND arrecad.k00_receit = $iReceita";
        }
        
        $sSqlArrecad .= " GROUP BY arrecad.k00_numcgm, arrecad.k00_numtot,";
        $sSqlArrecad .= "          arrecad.k00_numdig, arrecad.k00_tipo,  ";
        $sSqlArrecad .= "          arrecad.k00_tipojm, arrecad.k00_dtvenc,";
        $sSqlArrecad .= "          arrecad.k00_receit, arrecad.k00_dtoper ";
        
        $sSqlDaoArrecad =  $oDaoArreCad->sql_query("",
                                                   "arrecad.k00_numcgm, arrecad.k00_numtot,
                                                    arrecad.k00_numdig, arrecad.k00_tipo,
                                                    arrecad.k00_tipojm, arrecad.k00_dtvenc,
                                                    arrecad.k00_receit,
                                                    SUM(arrecad.k00_valor) as k00_valor,
                                                    arrecad.k00_dtoper",
                                                   "",
                                                   $sSqlArrecad);

        $rsDaoArrecad   = $oDaoArreCad->sql_record($sSqlDaoArrecad);
        
        if ($oDaoArreCad->numrows > 0) {
          
          $aResultado = db_utils::getCollectionByRecord($rsDaoArrecad, true);
          
          foreach ($aResultado as $oResultado) {
            
            if ($oDaoArreCad->numrows > 1) {
              
              $fValorDebito         = $oResultado->k00_valor;
              $fPercentDesconto     = $oParam->fPercentDesc;
              $fValorDescontoDebito = db_formatar((($fValorDebito / 100) * $fPercentDesconto),'p');
            } else {
             
              $fValorDescontoDebito = db_formatar($oParam->fValorDesc,'p');
            }
            
            $oDaoArreCad->k00_numcgm = $oResultado->k00_numcgm;
            $oDaoArreCad->k00_dtoper = $oResultado->k00_dtoper; 
            $oDaoArreCad->k00_receit = $oResultado->k00_receit;
            $oDaoArreCad->k00_hist   = 918;
            $oDaoArreCad->k00_valor  = ($fValorDescontoDebito * -1);
            $oDaoArreCad->k00_dtvenc = $oResultado->k00_dtvenc;
            $oDaoArreCad->k00_numpre = $iNumpre;
            $oDaoArreCad->k00_numpar = $iNumpar;
            $oDaoArreCad->k00_numtot = $oResultado->k00_numtot;
            $oDaoArreCad->k00_numdig = $oResultado->k00_numdig;
            $oDaoArreCad->k00_tipo   = $oResultado->k00_tipo;
            $oDaoArreCad->k00_tipojm = $oResultado->k00_tipojm;
            $oDaoArreCad->incluir();
            
            if ($oDaoArreCad->erro_status == 0) {
            
              $erro     = true;
              $erro_msg = $oDaoArreCad->erro_msg;
            } else {
              
              $oDaoAguaDescArrecad->x35_numcgm = $oResultado->k00_numcgm;
              $oDaoAguaDescArrecad->x35_dtoper = $oResultado->k00_dtoper;
              $oDaoAguaDescArrecad->x35_receit = $oResultado->k00_receit;
              $oDaoAguaDescArrecad->x35_hist   = 918;
              $oDaoAguaDescArrecad->x35_valor  = ($fValorDescontoDebito * -1);
              $oDaoAguaDescArrecad->x35_dtvenc = $oResultado->k00_dtvenc;
              $oDaoAguaDescArrecad->x35_numpre = $iNumpre;
              $oDaoAguaDescArrecad->x35_numpar = $iNumpar;
              $oDaoAguaDescArrecad->x35_numtot = $oResultado->k00_numtot;
              $oDaoAguaDescArrecad->x35_numdig = $oResultado->k00_numdig;
              $oDaoAguaDescArrecad->x35_tipo   = $oResultado->k00_tipo;
              $oDaoAguaDescArrecad->x35_tipojm = $oResultado->k00_tipojm;
              $oDaoAguaDescArrecad->incluir();
              
              if ($oDaoAguaDescArrecad->erro_status == 0) {
            
                $erro     = true;
                $erro_msg = $oDaoAguaDescArrecad->erro_msg;
              }
            }
          }
          
          $oDaoArreHist->k00_numpre     = $iNumpre;
          $oDaoArreHist->k00_numpar     = $iNumpar;
          $oDaoArreHist->k00_hist       = $iHistorico;
          $oDaoArreHist->k00_dtoper     = date("Y-m-d",db_getsession("DB_datausu"));
          $oDaoArreHist->k00_hora       = db_hora();
          $oDaoArreHist->k00_id_usuario = db_getsession("DB_id_usuario");
          $oDaoArreHist->k00_histtxt    = $oParam->sObs;
          $oDaoArreHist->k00_limithist  = null;
          $oDaoArreHist->incluir(null);
          
          if ($oDaoArreHist->erro_status == 0) {
           
            $erro     = true;
            $erro_msg = $oDaoArreHist->erro_msg;
          } else {
            
            $oDaoAguaDescArreHist->x36_numpre     = $iNumpre;
            $oDaoAguaDescArreHist->x36_numpar     = $iNumpar;
            $oDaoAguaDescArreHist->x36_hist       = $iHistorico;
            $oDaoAguaDescArreHist->x36_dtoper     = date("Y-m-d",db_getsession("DB_datausu"));
            $oDaoAguaDescArreHist->x36_hora       = db_hora();
            $oDaoAguaDescArreHist->x36_id_usuario = db_getsession("DB_id_usuario");
            $oDaoAguaDescArreHist->x36_histtxt    = $oParam->sObs;
            $oDaoAguaDescArreHist->x36_limithist  = null;
            $oDaoAguaDescArreHist->x36_idhist     = $oDaoArreHist->k00_idhist;
            $oDaoAguaDescArreHist->incluir(null);
          
            if ($oDaoAguaDescArreHist->erro_status == 0) {
            
              $erro     = true;
              $erro_msg = $oDaoAguaDescArreHist->erro_msg;
            }
          }
        }
      }
      
      db_fim_transacao($erro);

      if ($erro == false) {
        
        $oRetorno->status  = 1;
      } else {
      
        $oRetorno->status  = 2;
        $oRetorno->message = $erro_msg;
      }
      
    }

    break;
  
  

}

echo $oJson->encode($oRetorno);