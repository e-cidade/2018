<?php
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

$oDaoAguaConfVenc = db_utils::getDao("aguaconfvenc");
$oDaoAguaBaseVenc = db_utils::getDao("aguabasevenc");

/**
 * Virada VENCIMENTOS AGUA
 */
if ($sqlerro == false) {
	     
  $sSqlDuplicaExercicio  = "select fc_duplica_exercicio('aguaconf', 'x18_anousu', {$anoorigem}, {$anodestino}, null)";
  $rsSqlDuplicaExercicio = db_query($sSqlDuplicaExercicio);
  if ($rsSqlDuplicaExercicio == false) {
          
    $sqlerro   = true;
    $erro_msg .= "ERRO: Tabela aguaconf já possui registros para o exercicío de {$anodestino}!";
  }
     
  if ($sqlerro == false) {
        
    $sSqlDuplicaExercicio  = "select fc_duplica_exercicio('aguaconfvenc', 'x33_exerc', {$anoorigem}, {$anodestino}, null)";
    $rsSqlDuplicaExercicio = db_query($sSqlDuplicaExercicio);
    if ($rsSqlDuplicaExercicio == false) {
          
      $sqlerro   = true;
      $erro_msg .= "ERRO: Tabela aguaconfvenc já possui registros para o exercicío de {$anodestino}!";
    }
        
    if ($sqlerro == false) {
          
      $sCampos              = "x33_exerc,                                                         ";
      $sCampos             .= "x33_parcela,                                                       ";
      $sCampos             .= "to_date((x33_dtvenc + interval '1 year')::text,'yyyy-mm-dd') as x33_dtvenc ";
      $sWhere               = "x33_exerc = {$anodestino}";
      $sSqlAguaConfVenc     = $oDaoAguaConfVenc->sql_query_file(null, null, $sCampos, 'x33_parcela', $sWhere);
      $rsSqlAguaConfVenc    = $oDaoAguaConfVenc->sql_record($sSqlAguaConfVenc);
      $iNumRowsAguaConfVenc = $oDaoAguaConfVenc->numrows; 
      if ($iNumRowsAguaConfVenc > 0) {

        for ($iIndAguaConfVenc = 0; $iIndAguaConfVenc < $iNumRowsAguaConfVenc; $iIndAguaConfVenc++) {
              
          db_atutermometro($iIndAguaConfVenc, $iNumRowsAguaConfVenc, 'termometroitem', 1, $sMensagemTermometroItem . " (Passo 1/2)");
           
          $oAguaConfVenc = db_utils::fieldsMemory($rsSqlAguaConfVenc, $iIndAguaConfVenc);
              
          $oDaoAguaConfVenc->x33_exerc   = $anodestino;
          $oDaoAguaConfVenc->x33_parcela = $oAguaConfVenc->x33_parcela;
          $oDaoAguaConfVenc->x33_dtvenc  = "{$oAguaConfVenc->x33_dtvenc}";
          $oDaoAguaConfVenc->alterar($oDaoAguaConfVenc->x33_exerc, $oDaoAguaConfVenc->x33_parcela);
          if ($oDaoAguaConfVenc->erro_status == 0) {
            $sqlerro   = true;
            $erro_msg .= $oDaoAguaConfVenc->erro_msg;
            break;
          }
        }
      }
    }
  }
    
  if ($sqlerro == false) {
      
    $sCampos  = "x27_matric,                                                        ";
    $sCampos .= "x27_parcela,                                                       ";
    $sCampos .= "to_date((x27_dtvenc + interval '1 year')::text,'yyyy-mm-dd') as x27_dtvenc ";
    $sSqlAguaBaseVenc     = $oDaoAguaBaseVenc->sql_query_file(null, null, $sCampos, 'x27_matric, x27_parcela');
    $rsAguaBaseVenc       = $oDaoAguaBaseVenc->sql_record($sSqlAguaBaseVenc);
    $iNumRowsAguaBaseVenc = $oDaoAguaBaseVenc->numrows;
    if ($iNumRowsAguaBaseVenc > 0) {
        
      for ($iIndAguaBaseVenc = 0; $iIndAguaBaseVenc < $iNumRowsAguaBaseVenc; $iIndAguaBaseVenc++) {
          
        db_atutermometro($iIndAguaBaseVenc, $iNumRowsAguaBaseVenc, 'termometroitem', 1, $sMensagemTermometroItem . " (Passo 2/2)");
          
        $oAguaBaseVenc = db_utils::fieldsMemory($rsAguaBaseVenc, $iIndAguaBaseVenc);
          
        $oDaoAguaBaseVenc->x27_matric  = $oAguaBaseVenc->x27_matric;
        $oDaoAguaBaseVenc->x27_parcela = $oAguaBaseVenc->x27_parcela;
        $oDaoAguaBaseVenc->x27_dtvenc  = "{$oAguaBaseVenc->x27_dtvenc}";
        $oDaoAguaBaseVenc->alterar($oDaoAguaBaseVenc->x27_matric, $oDaoAguaBaseVenc->x27_parcela);
        if ($oDaoAguaBaseVenc->erro_status == 0) {
            
          $sqlerro   = true;
          $erro_msg .= $oDaoAguaBaseVenc->erro_msg;
          break;
        }
      }
    }
  }
}
?>