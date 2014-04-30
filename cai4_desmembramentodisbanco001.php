<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

try {
  
  if ($iTipoProcessamento == 1) {
    $sCampoAgrupa = "dtpago";
  } else if ($iTipoProcessamento == 2) {
    $sCampoAgrupa = "dtcredito";
  }  
  
  if (empty($sCampoAgrupa)) {
    throw new ParameterException("Campo para realizar o agrupamento dos registros do arquivo de retorno não declarado!");
  }
  
  if (empty($iCodRet)) {
    throw new ParameterException("Código do retorno gerado pelo processamento do arquivo não declarado!");
  }  
  
  if ( !db_utils::inTransaction() ) {
  	throw new Exception("[ 5 ] - Nenhuma transação com o banco de dados encontrada!");
  }
  
  $dDataAgrupamento = "";

  $sSqlVerifica  = "select count(distinct {$sCampoAgrupa})    "; 
  $sSqlVerifica .= "  from disbanco                           ";
  $sSqlVerifica .= " where codret = {$iCodRet}                ";
  $sSqlVerifica .= "having count(distinct {$sCampoAgrupa}) > 1";
  $rsVerifica = $clDisBanco->sql_record($sSqlVerifica);
  if ($clDisBanco->numrows > 0) { 
    
    $sSqlDisBanco  = "select distinct *                                                                               "; 
    $sSqlDisBanco .= "  from disarq                                                                                   ";
    $sSqlDisBanco .= "       inner join disbanco       on disbanco.codret            = disarq.codret                  ";
    $sSqlDisBanco .= "       left  join disbancotxtreg on disbancotxtreg.k35_idret   = disbanco.idret                 ";
    $sSqlDisBanco .= "       left  join disbancotxt    on disbancotxt.k34_sequencial = disbancotxtreg.k35_disbancotxt ";
    $sSqlDisBanco .= " where disarq.codret = {$iCodRet}                                                               ";
    $sSqlDisBanco .= " order by {$sCampoAgrupa}                                                                       ";
    $rsDisBanco         = $clDisBanco->sql_record($sSqlDisBanco);
    $iQtdLinhasDisbanco = $clDisBanco->numrows;
    
    $clDisBancoTXTReg->excluir(null, "k35_idret in (select idret from disbanco where codret = {$iCodRet})");
    if ($clDisBancoTXTReg->erro_status == "0") {
      $sMsg  = "Operação Abortada!\\n";
      $sMsg .= "Erro ao excluir registros da Tabela disbancotxtreg!\\n";
      $sMsg .= "Erro: {$clDisBancoTXTReg->erro_msg}";
      throw new DBException($sMsg);      
    }
    
    $clDisBancoTXT->excluir(null, "k34_codret = {$iCodRet}");
    if ($clDisBancoTXT->erro_status == "0") {
      $sMsg  = "Operação Abortada!\\n";
      $sMsg .= "Erro ao excluir registros da Tabela disbancotxt!\\n";
      $sMsg .= "Erro: {$clDisBancoTXT->erro_msg}";
      throw new DBException($sMsg);      
    }    
    
    $clDisBanco->excluir(null, "codret = {$iCodRet}");
    if ($clDisBanco->erro_status == "0") {
      $sMsg  = "Operação Abortada!\\n";
      $sMsg .= "Erro ao excluir arquivo {$iCodRet}!\\n";
      $sMsg .= "Erro: {$clDisBanco->erro_msg}";
      throw new DBException($sMsg);      
    }
    
    $clDisArq->excluir($iCodRet);
    if ($clDisArq->erro_status == "0") {
      $sMsg  = "Operação Abortada!\\n";
      $sMsg .= "Erro ao excluir arquivo {$iCodRet}!\\n";
      $sMsg .= "Erro: {$clDisArq->erro_msg}";
      throw new DBException($sMsg);      
    }
    
    for ($iInd = 0; $iInd < $iQtdLinhasDisbanco; $iInd++) {
      $oDadosDisbanco = db_utils::fieldsMemory($rsDisBanco, $iInd);
      
      if ($dDataAgrupamento != $oDadosDisbanco->$sCampoAgrupa) {
        
        if ($iInd == 0) {
          $clDisArq->codret = $iCodRet;
        } else {
          $clDisArq->codret = null;
        }
        $clDisArq->id_usuario = $oDadosDisbanco->id_usuario;
        $clDisArq->k15_codbco = $oDadosDisbanco->k15_codbco;
        $clDisArq->k15_codage = $oDadosDisbanco->k15_codage;
        $clDisArq->arqret     = $oDadosDisbanco->arqret."_".str_replace("-","",$oDadosDisbanco->$sCampoAgrupa);
        $clDisArq->textoret   = $oDadosDisbanco->textoret  ;
        $clDisArq->dtretorno  = $oDadosDisbanco->dtretorno ;
        $clDisArq->dtarquivo  = $oDadosDisbanco->dtarquivo ;
        $clDisArq->k00_conta  = $oDadosDisbanco->k00_conta ;
        $clDisArq->autent     = ($oDadosDisbanco->autent == "f"?"false":"true");
        $clDisArq->instit     = $oDadosDisbanco->instit    ;
				$clDisArq->md5        = $oDadosDisbanco->md5;
        $clDisArq->incluir($clDisArq->codret);
        if ($clDisArq->erro_status == "0") {
          $sMsg  = "Operação Abortada!\\n";
          $sMsg .= "Erro ao incluir registros na DisArq!\\n";
          $sMsg .= "Erro: {$clDisArq->erro_msg}";
          throw new DBException($sMsg);
        }
        
        $dDataAgrupamento = $oDadosDisbanco->$sCampoAgrupa;
      }
    
      if (empty($oDadosDisbanco->dtcredito)) {
        $oDadosDisbanco->dtcredito = $oDadosDisbanco->dtpago;
      }
      
      $clDisBanco->codret     = $clDisArq->codret          ;
      $clDisBanco->k00_numbco = $oDadosDisbanco->k00_numbco;
      $clDisBanco->k15_codbco = $oDadosDisbanco->k15_codbco;
      $clDisBanco->k15_codage = $oDadosDisbanco->k15_codage;
      $clDisBanco->dtarq      = $oDadosDisbanco->dtarq     ;
      $clDisBanco->dtpago     = $oDadosDisbanco->dtpago    ;
      $clDisBanco->vlrpago    = $oDadosDisbanco->vlrpago   ;
      $clDisBanco->vlrjuros   = $oDadosDisbanco->vlrjuros  ;
      $clDisBanco->vlrmulta   = $oDadosDisbanco->vlrmulta  ;
      $clDisBanco->vlracres   = $oDadosDisbanco->vlracres  ;
      $clDisBanco->vlrdesco   = $oDadosDisbanco->vlrdesco  ;
      $clDisBanco->vlrtot     = $oDadosDisbanco->vlrtot    ;
      $clDisBanco->cedente    = $oDadosDisbanco->cedente   ;
      $clDisBanco->vlrcalc    = $oDadosDisbanco->vlrcalc   ;
      $clDisBanco->idret      = $oDadosDisbanco->idret     ;
      $clDisBanco->classi     = ($oDadosDisbanco->classi == "f"?"false":"true");
      $clDisBanco->k00_numpre = $oDadosDisbanco->k00_numpre;
      $clDisBanco->k00_numpar = $oDadosDisbanco->k00_numpar;
      $clDisBanco->convenio   = $oDadosDisbanco->convenio  ;
      $clDisBanco->instit     = $oDadosDisbanco->instit    ;
      $clDisBanco->dtcredito  = $oDadosDisbanco->dtcredito ;
      $clDisBanco->incluir(null);
      if ($clDisBanco->erro_status == "0") {
        $sMsg  = "Operação Abortada!\\n";
        $sMsg .= "Erro ao incluir registros na DisBanco!\\n";
        $sMsg .= "Erro: {$clDisBanco->erro_msg}";    
        throw new DBException($sMsg);
      }
      
      if (!empty($oDadosDisbanco->k34_sequencial)) {
        $clDisBancoTXT->k34_numpremigra = $oDadosDisbanco->k34_numpremigra;
        $clDisBancoTXT->k34_valor       = $oDadosDisbanco->k34_valor+0;
        $clDisBancoTXT->k34_dtvenc      = $oDadosDisbanco->k34_dtvenc;
        $clDisBancoTXT->k34_dtpago      = $oDadosDisbanco->k34_dtpago;
        $clDisBancoTXT->k34_codret      = $clDisArq->codret;
        $clDisBancoTXT->k34_diferenca   = $oDadosDisbanco->k34_diferenca;      
        $clDisBancoTXT->incluir(null);
        if ($clDisBancoTXT->erro_status == "0") {
          $sMsg  = "Operação Abortada!\\n"; 
          $sMsg .= "Erro as incluir registros na disbancotxt\\n";
          $sMsg .= "Erro: {$clDisBancoTXT->erro_msg}";        
          throw new DBException($sMsg);
        }
      }
      
      if (!empty($oDadosDisbanco->k35_sequencial)) {
        $clDisBancoTXTReg->k35_disbancotxt = $clDisBancoTXT->k34_sequencial;
        $clDisBancoTXTReg->k35_idret       = $clDisBanco->idret;
        $clDisBancoTXTReg->incluir(null);
        if ($clDisBancoTXTReg->erro_status == "0") {
          $sMsg  = "Operação Abortada!\\n"; 
          $sMsg .= "[ 4 ] - Erro incluindo registros na disbancotxtreg\\n";
          $sMsg .= "Erro: {$clDisBancoTXTReg->erro_msg}";        
          throw new DBException($sMsg);            
        }
      }      
      
    }
    
  }
  
} catch (DBException $eErro){          // DB Exception
   
  db_fim_transacao(true);
  throw new DBException($eErro->getMessage());
  
} catch (BusinessException $eErro){     // Business Exception
  
  db_fim_transacao(true);
  throw new BusinessException($eErro->getMessage());
  
} catch (ParameterException $eErro){     // Parameter Exception
  
  db_fim_transacao(true);
  throw new ParameterException($eErro->getMessage());
  
} catch (Exception $eErro){

  db_fim_transacao(true);
  throw new Exception($eErro->getMessage());
}

?>