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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

db_app::import('exceptions.*');

$oPost    = db_utils::postMemory($_POST);

$oJson    = new services_json();
$lErro    = false;
$sMsgErro = '';

try {
  
  switch ($oPost->sMethod) {
    
    case 'consultaTiposDeDebitos':

      $aListaSituacao = array();

      $oDaoSituacaoLeitura = new cl_arrecad();
      
      $sCampos = 'distinct on (arrecad.k00_numpre, arrecad.k00_numpar) arretipo.k00_tipo, arretipo.k00_descr, count(1) as contador';
      
      $iInstituicao = db_getsession("DB_instit");
      
      $sSql  = "SELECT x.k00_tipo, x.k00_descr, count(1) as contador                                     \n";
      $sSql .= "  FROM ( SELECT DISTINCT ON (arrecad.k00_numpre, arrecad.k00_numpar)                     \n";
      $sSql .= "                arrecad.k00_numpre, arrecad.k00_numpar,                                  \n";
      $sSql .= "                arrecad.k00_tipo, arretipo.k00_descr                                     \n";
      $sSql .= "           FROM arrecad                                                                  \n";
      $sSql .= "                INNER JOIN arreinstit ON arreinstit.k00_numpre = arrecad.k00_numpre      \n";
      $sSql .= "                INNER JOIN arretipo   ON  arretipo.k00_tipo = arrecad.k00_tipo           \n";
      $sSql .= "          WHERE arrecad.k00_dtvenc BETWEEN '{$oPost->sDataIni}' AND '{$oPost->sDataFim}' \n";
      $sSql .= "            AND arreinstit.k00_instit = {$iInstituicao}                                  \n";
      $sSql .= "            AND arretipo.k00_instit   = {$iInstituicao} ) as x                           \n";
      $sSql .= " GROUP BY x.k00_tipo, x.k00_descr                                                        \n";
      $sSql .= " ORDER BY contador DESC                                                                  \n";
      
      $rsSituacoes = $oDaoSituacaoLeitura->sql_record($sSql);

      if ($oDaoSituacaoLeitura->numrows) {
        $aSituacoes = db_utils::getCollectionByRecord($rsSituacoes);
      } else {
        throw new Exception('Nenhum registro encontrado');
      }
      
      $aRetornoSituacao = array();
      
      foreach ($aSituacoes as $iIndice => $oSituacao) {
        
        $aRetornoSituacao[$iIndice] = new StdClass();
        
        $aRetornoSituacao[$iIndice]->codigo    = $oSituacao->k00_tipo;
        $aRetornoSituacao[$iIndice]->descricao = utf8_encode($oSituacao->k00_descr);
        $aRetornoSituacao[$iIndice]->contador  = $oSituacao->contador;
      }
      
      $aRetorno = array("lErro"      => false,
                        "aSituacoes" => $aRetornoSituacao); 
      break;
      
    case 'processaAlteracaoData':
      
      try {
        
        if ((empty($oPost->dataini_ano) or empty($oPost->dataini_mes) or empty($oPost->dataini_dia)) and 
            (    strlen($oPost->dataini_ano) != 4 
             and strlen($oPost->dataini_mes) != 2
             and strlen($oPost->dataini_dia) != 2)) {
          
          throw new Exception('Data Inicial é inválida.');
        }
        
        if ((empty($oPost->datafim_ano) or empty($oPost->datafim_mes) or empty($oPost->datafim_dia)) and 
            (    strlen($oPost->datafim_ano) != 4 
             and strlen($oPost->datafim_mes) != 2
             and strlen($oPost->datafim_dia) != 2)) {
          
          throw new Exception('Data Final é inválida.');
        }
        
        if ((   empty($oPost->novaDataVencimento_ano) or empty($oPost->novaDataVencimento_mes) or empty($oPost->novaDataVencimento_dia)) and
            (    strlen($oPost->novaDataVencimento_ano) != 4
             and strlen($oPost->novaDataVencimento_mes) != 2
             and strlen($oPost->novaDataVencimento_dia) != 2)) {
        
          throw new Exception('Nova Data informada é inválida.');
        }        
        
        $oDataInicial = new DBDate(      $oPost->dataini_ano .
                                   '-' . $oPost->dataini_mes .
                                   '-' . $oPost->dataini_dia);
        
        $oDataFinal   = new DBDate(      $oPost->datafim_ano .
                                   '-' . $oPost->datafim_mes .
                                   '-' . $oPost->datafim_dia);
        
        $oNovaDataVencimento   = new DBDate(      $oPost->novaDataVencimento_ano . 
                                            '-' . $oPost->novaDataVencimento_mes . 
                                            '-' . $oPost->novaDataVencimento_dia);
        
        if ($oDataInicial->getDate() > $oDataFinal->getDate()) {
          throw new Exception('Data inicial é maior que a final.');
        } 

        if (empty($oPost->sTiposDebito)) {
          throw new Exception('Nenhum Tipo de débito informado.');
        }
        
        
        $aTiposDebitos     = explode(',', $oPost->sTiposDebito);
        $sVirgula          = '';
        $sCodigoTipoDebito = '';
        
        foreach ($aTiposDebitos as $oDebito) {
        
          $iCodigoTipoDebito = explode('-', $oDebito);
          
          $sCodigoTipoDebito .= $sVirgula . $iCodigoTipoDebito[1];
          $sVirgula = ',';
        }
        
        $iInstituicao = db_getsession("DB_instit");

        $oDaoArrecad = new cl_arrecad();
        
        $sWhere  = "    arrecad.k00_dtvenc between '{$oDataInicial->getDate()}' and '{$oDataFinal->getDate()}' ";
        $sWhere .= "and arrecad.k00_tipo in ({$sCodigoTipoDebito}) ";
        $sWhere .= "and arreinstit.k00_instit = {$iInstituicao} ";
        $sWhere .= "and arretipo.k00_instit   = {$iInstituicao} ";
        $sWhere .= "and arretipo.k00_tipo in ({$sCodigoTipoDebito}) ";
        
        $sCampos = "arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_receit, arrecad.k00_tipo, k00_dtvenc";
        
        $sSql = $oDaoArrecad->sql_query(null, $sCampos, '', $sWhere);
        
        $sSqlUpdate  = "update arrecad set k00_dtvenc = '{$oNovaDataVencimento->getDate()}' ";
        $sSqlUpdate .= " where (k00_numpre, k00_numpar, k00_receit, k00_tipo, k00_dtvenc) in ({$sSql})";
        
        $rsDebitos = $oDaoArrecad->sql_record($sSql);

        db_inicio_transacao();
        
        if ($oDaoArrecad->numrows) {
          
          $aDebitos = db_utils::getCollectionByRecord($rsDebitos);
          db_query($sSqlUpdate);
        } else {
          throw new Exception('Nenhum registro encontrado');
        } 
        
        foreach ($aDebitos as $oDebito) {
          $aDebitosAlterados[$oDebito->k00_numpre][$oDebito->k00_numpar] = $oDebito->k00_dtvenc;
        } 
        
        foreach ($aDebitosAlterados as $sNumpre => $aDebitoNumpre) {
          
          foreach ($aDebitoNumpre as $sNumpar => $sDataVemcimento) {
            
            $oDaoArreHist = new cl_arrehist();
            
            $oDaoArreHist->k00_numpre     = $sNumpre;
            $oDaoArreHist->k00_numpar     = $sNumpar;
            $oDaoArreHist->k00_hist       = 502;
            $oDaoArreHist->k00_dtoper     = date('Y-m-d', time());
            $oDaoArreHist->k00_hora       = date('H:m', time());
            $oDaoArreHist->k00_id_usuario = db_getsession("DB_id_usuario");
            
            $sHistorico  = "Alterada a data vencimento do Numpre {$sNumpre} e";
            $sHistorico .= " Numpar {$sNumpar}: " . date('d/m/Y', strtotime($sDataVemcimento)) . " para ";
            $sHistorico .= $oNovaDataVencimento->getDate('d/m/Y');
            
            $oDaoArreHist->k00_histtxt = $sHistorico;
            
            $oDaoArreHist->incluir(null);
            
            if ($oDaoArreHist->erro_status == "0") {
              throw new Exception("Erro ao lançar histório: " . $oDaoArreHist->erro_msg);
            } 
          }
        }
        
        db_fim_transacao();
        
      } catch (Exception $oErro) {
        
        db_fim_transacao(TRUE);
        throw new Exception($oErro->getMessage());
      }
      
      $aRetorno = array("lErro" => false,
                        "sMsg"  => 'Alteração efetuada com sucesso!');
      
      break;
  } 
  
} catch (Exception $eErro) {
  
  db_fim_transacao(true);
  
  $aRetorno = array("lErro" => true,
                    "sMsg"  => urlencode($eErro->getMessage()));
} 

echo $oJson->encode($aRetorno);