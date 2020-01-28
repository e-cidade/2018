<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


require_once ('model/PadArquivoSigap.model.php');

final class PadArquivoSigapProjativ extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "ProjetoAtividadeOperacaoEspecial";
    $this->aDados       = array();
  }
  
  public function gerarDados() {
    
    if (empty($this->sDataInicial)) {
      throw new Exception("Data inicial nao informada!");
    }
    
    if (empty($this->sDataFinal)) {
      throw new Exception("Data final não informada!");
    }
    /**
     * Separamos a data do em ano, mes, dia
     */
    $iCodigoInstit  = db_getsession("DB_instit");
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    
    $sSqlProAtiv  = "select distinct ";
    $sSqlProAtiv .= "       o55_anousu as anousu,";
    $sSqlProAtiv .= "       o55_projativ as codigo,";
    $sSqlProAtiv .= "       o55_descr as nome,";
    $sSqlProAtiv .= "       2 as identificador";
    $sSqlProAtiv .= "  from orcprojativ";
    $sSqlProAtiv .= "       inner join orcdotacao on o58_projativ = o55_projativ";
    $sSqlProAtiv .= "                            and o58_anousu   = o55_anousu";
    $sSqlProAtiv .= "    and o55_anousu <= " . db_getsession("DB_anousu"); 
    $rsProjAtiv   = db_query($sSqlProAtiv);
    $iTotalLinhas = pg_num_rows($rsProjAtiv);
     
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oProjAtiv = db_utils::fieldsMemory($rsProjAtiv, $i);
      
      $oProjAtivRetorno                             = new stdClass();
      $oProjAtivRetorno->patCodigoEntidade          = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oProjAtivRetorno->patMesAnoMovimento         = $sDiaMesAno;
      $oProjAtivRetorno->patExercicio               = $oProjAtiv->anousu;
      $oProjAtivRetorno->patCodigoProjetoAtividade  = str_pad($oProjAtiv->codigo, 3, "0", STR_PAD_LEFT);
      $oProjAtivRetorno->patNomeProjetoAtividade    = str_pad($oProjAtiv->nome, 80,".", STR_PAD_RIGHT);
      $oProjAtivRetorno->patIdentificador           = str_pad($oProjAtiv->identificador, 2, '0', STR_PAD_LEFT);
      array_push($this->aDados, $oProjAtivRetorno);
      
    }
  }
  
  public function getNomeElementos() {
    
    $aElementos = array(
                        "patCodigoEntidade",
                        "patMesAnoMovimento",
                        "patExercicio",
                        "patCodigoProjetoAtividade",
                        "patNomeProjetoAtividade",
                        "patIdentificador",
                       );
    return $aElementos;  
  }
  
}

?>