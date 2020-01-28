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


class linhaColunaRelatorio {

  private $codigo    = null;
  private $relatorio = null;
  private $colunas   = array();
  /**
   * 
   */
  function __construct($iColunaRelatorio) {

    $this->codigo    = $iColunaRelatorio;
    
  }
  
  function save($iLinha, $nValor, $iPeriodo, $iSeqValor = null, $iAnoUsu = null) {
   
    $oDaoColunaValor  = db_utils::getDao("orcparamseqorcparamseqcolunavalor");
    
    if (empty($iAnoUsu)) {
      $iAnoUsu = db_getsession("DB_anousu");
    }
    
    if ($iSeqValor != null) {

      $oDaoColunaValor->o117_periodo    = $iPeriodo;
      $oDaoColunaValor->o117_valor      = "$nValor";
      $oDaoColunaValor->o117_sequencial = $iSeqValor;
      $oDaoColunaValor->alterar($iSeqValor);
      if ($oDaoColunaValor->erro_status == 0) {
        
        throw new Exception($oDaoColunaValor->erro_msg);
      }
         
    } else {
      
      $oDaoColunaValor->o117_periodo = $iPeriodo;
      $oDaoColunaValor->o117_valor   = "{$nValor}";
      $oDaoColunaValor->o117_linha   = $iLinha;
      $oDaoColunaValor->o117_instit  = db_getsession("DB_instit");
      $oDaoColunaValor->o117_anousu  = $iAnoUsu;
      $oDaoColunaValor->o117_orcparamseqorcparamseqcoluna = $this->codigo;
      $oDaoColunaValor->incluir(null);
      if ($oDaoColunaValor->erro_status == 0) {
        
        throw new Exception(str_replace("\\n","\n",$oDaoColunaValor->erro_msg));
      }
      $iSeqValor     = $oDaoColunaValor->o117_sequencial;
      
    }
    return $iSeqValor;
  }
}

?>