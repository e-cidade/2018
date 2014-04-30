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

final class PadArquivoSigapPrograma extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Programa";
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
    
    $sSqlProgramas  = "select distinct ";
    $sSqlProgramas .= "       o54_anousu as anousu,";
    $sSqlProgramas .= "       o54_programa as codigo,";
    $sSqlProgramas .= "       o54_descr as nome";
    $sSqlProgramas .= "  from orcprograma";
    $sSqlProgramas .= "       inner join orcdotacao on o58_programa = o54_programa";
    $sSqlProgramas .= "                            and o58_anousu   = o54_anousu";
    $sSqlProgramas .= "    and o54_anousu <= " . db_getsession("DB_anousu"); 
    $rsPrograma     = db_query($sSqlProgramas);
    $iTotalLinhas = pg_num_rows($rsPrograma);
     
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oPrograma = db_utils::fieldsMemory($rsPrograma, $i);
      
      $oProgramaRetorno                     = new stdClass();
      $oProgramaRetorno->proCodigoEntidade  = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oProgramaRetorno->proMesAnoMovimento = $sDiaMesAno;
      $oProgramaRetorno->proExercicio       = $oPrograma->anousu;
      $oProgramaRetorno->proCodigoPrograma  = str_pad($oPrograma->codigo, 3, "0", STR_PAD_LEFT);
      $oProgramaRetorno->proNomePrograma    = $oPrograma->nome;
      array_push($this->aDados, $oProgramaRetorno);
      
    }
  }
  
  public function getNomeElementos() {
    
    $aElementos = array(
                        "proCodigoEntidade",
                        "proMesAnoMovimento",
                        "proExercicio",
                        "proCodigoPrograma",
                        "proNomePrograma"
                       );
    return $aElementos;  
  }
  
}

?>