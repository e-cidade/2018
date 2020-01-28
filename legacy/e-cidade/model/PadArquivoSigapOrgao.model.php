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

final class PadArquivoSigapOrgao extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Orgao";
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
    $sWhereInstit = " and o58_instit = ".db_getsession("DB_instit");
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    
    $sSqlOrgaos  = "select distinct    "; 
    $sSqlOrgaos .= "       o40_anousu, ";
    $sSqlOrgaos .= "       o40_orgao,  ";
    $sSqlOrgaos .= "       o40_descr";
    $sSqlOrgaos .= "  from orcorgao  ";
    $sSqlOrgaos .= "      inner join orcdotacao on o58_orgao = o40_orgao  ";
    $sSqlOrgaos .= "                           and o58_anousu = o40_anousu ";
    $sSqlOrgaos .= " where o58_anousu <= {$iAno} {$sWhereInstit}";
    $rsOrgaos     = db_query($sSqlOrgaos);
    $iTotalLinhas = pg_num_rows($rsOrgaos); 
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno    =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oOrgao        = db_utils::fieldsMemory($rsOrgaos, $i);
      
      $oOrgaoRetorno                      = new stdClass();
      $oOrgaoRetorno->orgCodigoEntidade   = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oOrgaoRetorno->orgMesAnoMovimento  = $sDiaMesAno;
      $oOrgaoRetorno->orgExercicio        = $oOrgao->o40_anousu;
      $oOrgaoRetorno->orgCodigoOrgao      = str_pad($oOrgao->o40_orgao, 2, "0", STR_PAD_LEFT);
      $oOrgaoRetorno->orgNomeOrgao        = $oOrgao->o40_descr;
      
      array_push($this->aDados, $oOrgaoRetorno);
      
    }
  }
  
  public function getNomeElementos() {
    
    $aElementos = array(
                        "orgCodigoEntidade",
                        "orgMesAnoMovimento",
                        "orgExercicio",
                        "orgCodigoOrgao",
                        "orgNomeOrgao"
                       );
    return $aElementos;  
  }
  
}

?>