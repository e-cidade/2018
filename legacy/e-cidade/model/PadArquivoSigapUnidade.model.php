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

final class PadArquivoSigapUnidade extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "UnidadeOrcamentaria";
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
    
    $sSqlUnidades  = "select distinct ";
    $sSqlUnidades .= "       o41_anousu  as anousu,";
    $sSqlUnidades .= "       o41_orgao  as orgao,";
    $sSqlUnidades .= "       o41_unidade as unidade,";
    $sSqlUnidades .= "       o41_descr as nome,";
    $sSqlUnidades .= "       o41_ident as identificador,"; 
    $sSqlUnidades .= "       cgc  as cnpj ";
    $sSqlUnidades .= "  from orcunidade";
    $sSqlUnidades .= "       inner join orcdotacao on o58_orgao  = o41_orgao";
    $sSqlUnidades .= "                            and o58_unidade= o41_unidade";
    $sSqlUnidades .= "                            and o58_anousu = o41_anousu ";
    $sSqlUnidades .= "       inner join orcorgao   on o41_orgao  = o40_orgao ";
    $sSqlUnidades .= "                            and o41_anousu = o40_anousu";
    $sSqlUnidades .= "                            and o41_instit in ({$iCodigoInstit})";
    $sSqlUnidades .= "       inner join db_config on codigo = o58_instit ";
    $sSqlUnidades .= " where o58_anousu <=  $iAno ";
    $sSqlUnidades .= "   and o58_instit in ({$iCodigoInstit})";
    $rsUnidades    = db_query($sSqlUnidades);
    $iTotalLinhas = pg_num_rows($rsUnidades);
     
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno    =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oUnidade      = db_utils::fieldsMemory($rsUnidades, $i);
      
      $oUnidadeRetorno                               = new stdClass();
      $oUnidadeRetorno->uorCodigoEntidade            = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oUnidadeRetorno->uorMesAnoMovimento           = $sDiaMesAno;
      $oUnidadeRetorno->uorExercicio                 = $oUnidade->anousu;
      $oUnidadeRetorno->uorCodigoOrgao               = str_pad($oUnidade->orgao, 2, "0", STR_PAD_LEFT);
      $oUnidadeRetorno->uorCodigoUnidadeOrcamentaria = str_pad($oUnidade->unidade, 2, "0", STR_PAD_LEFT);
      $oUnidadeRetorno->uorNomeUnidadeOrcamentaria   = $oUnidade->nome;
      $oUnidadeRetorno->uorIdentificador             = str_pad($oUnidade->identificador, 2, "0", STR_PAD_LEFT);
      $oUnidadeRetorno->uorCNPJ                      = $oUnidade->cnpj;
      array_push($this->aDados, $oUnidadeRetorno);
      
    }
  }
  public function getNomeElementos() {
    
    $aElementos = array(
                        "uorCodigoEntidade",
                        "uorMesAnoMovimento",
                        "uorExercicio",
                        "uorCodigoOrgao",
                        "uorCodigoUnidadeOrcamentaria",
                        "uorNomeUnidadeOrcamentaria",
                        "uorIdentificador", 
                        "uorCNPJ"
    
                       );
    return $aElementos;  
  }
}

?>