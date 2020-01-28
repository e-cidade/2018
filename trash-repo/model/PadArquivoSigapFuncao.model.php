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

final class PadArquivoSigapFuncao extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Funcao";
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
    
    $sSqlFuncao  = "select distinct ";
    $sSqlFuncao .= "       o58_anousu as anousu,";
    $sSqlFuncao .= "       o52_funcao as funcao,";
    $sSqlFuncao .= "       o52_descr as nome";
    $sSqlFuncao .= "  from orcfuncao ";
    $sSqlFuncao .= "       inner join orcdotacao on o58_funcao = o52_funcao";
    $sSqlFuncao .= " where o52_funcao > 0";
    $sSqlFuncao .= "   and o58_anousu <= {$iAno}";
    $sSqlFuncao .= "       union";
    $sSqlFuncao .= " select distinct";
    $sSqlFuncao .= "        o73_anousu  as anousu,";
    $sSqlFuncao .= "        o52_funcao as funcao,";
    $sSqlFuncao .= "        o52_descr as nome";
    $sSqlFuncao .= "   from orcfuncao ";
    $sSqlFuncao .= "        inner join orcdotacaorp on o73_funcao = o52_funcao";                
    $sSqlFuncao .= "  where o73_funcao > 0 ";
    $sSqlFuncao .= "    and o73_anousu <= {$iAno}";
    $rsFuncao    = db_query($sSqlFuncao);
    $iTotalLinhas = pg_num_rows($rsFuncao);
     
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno    =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oFuncao      = db_utils::fieldsMemory($rsFuncao, $i);
      
      $oFuncaoRetorno                      = new stdClass();
      $oFuncaoRetorno->funCodigoEntidade   = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oFuncaoRetorno->funMesAnoMovimento  = $sDiaMesAno;
      $oFuncaoRetorno->funExercicio        = $oFuncao->anousu;
      $oFuncaoRetorno->funCodigoFuncao     = str_pad($oFuncao->funcao, 2, "0", STR_PAD_LEFT);
      $oFuncaoRetorno->funNomeFuncao       = $oFuncao->nome;
      array_push($this->aDados, $oFuncaoRetorno);
      
    }
  }
  
  public function getNomeElementos() {
    
    $aElementos = array(
                        "funCodigoEntidade",
                        "funMesAnoMovimento",
                        "funExercicio",
                        "funCodigoFuncao",
                        "funNomeFuncao"
                       );
    return $aElementos;  
  }
  
}

?>