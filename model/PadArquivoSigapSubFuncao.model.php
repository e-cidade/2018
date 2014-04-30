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

final class PadArquivoSigapSubFuncao extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "SubFuncao";
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
    
    $sSqlSubFuncao  = "select distinct ";
    $sSqlSubFuncao .= "       o58_anousu as anousu,";
    $sSqlSubFuncao .= "       o53_subfuncao as subfuncao,";
    $sSqlSubFuncao .= "       o53_descr as nome";
    $sSqlSubFuncao .= "  from orcsubfuncao ";
    $sSqlSubFuncao .= "       inner join orcdotacao on o58_subfuncao = o53_subfuncao";
    $sSqlSubFuncao .= " where o53_subfuncao > 0";
    $sSqlSubFuncao .= "   and o58_anousu <= {$iAno}";
    $sSqlSubFuncao .= "       union";
    $sSqlSubFuncao .= " select distinct";
    $sSqlSubFuncao .= "        o73_anousu  as anousu,";
    $sSqlSubFuncao .= "        o53_subfuncao as subfuncao,";
    $sSqlSubFuncao .= "        o53_descr as nome";
    $sSqlSubFuncao .= "   from orcsubfuncao ";
    $sSqlSubFuncao .= "        inner join orcdotacaorp on o73_subfuncao = o53_subfuncao";                
    $sSqlSubFuncao .= "  where o73_subfuncao > 0 ";
    $sSqlSubFuncao .= "    and o73_anousu <= {$iAno}";
    $rsSubFuncao    = db_query($sSqlSubFuncao);
    $iTotalLinhas = pg_num_rows($rsSubFuncao);
     
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oSubFuncao = db_utils::fieldsMemory($rsSubFuncao, $i);
      
      $oSubFuncaoRetorno                      = new stdClass();
      $oSubFuncaoRetorno->sfuCodigoEntidade   = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oSubFuncaoRetorno->sfuMesAnoMovimento  = $sDiaMesAno;
      $oSubFuncaoRetorno->sfuExercicio        = $oSubFuncao->anousu;
      $oSubFuncaoRetorno->sfuCodigoSubFuncao  = str_pad($oSubFuncao->subfuncao, 3, "0", STR_PAD_LEFT);
      $oSubFuncaoRetorno->sfuNomeSubFuncao    = $oSubFuncao->nome;
      array_push($this->aDados, $oSubFuncaoRetorno);
      
    }
  }
  
  public function getNomeElementos() {
    
    $aElementos = array(
                        "sfuCodigoEntidade",
                        "sfuMesAnoMovimento",
                        "sfuExercicio",
                        "sfuCodigoSubFuncao",
                        "sfuNomeSubFuncao"
                       );
    return $aElementos;  
  }
  
}

?>