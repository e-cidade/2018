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


require_once ('model/PadArquivoSigap.model.php');
/**
 * Prove dados para a geração do arquivo dos recursos vinculados para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.2 $
 */
final class PadArquivoSigapRecurso extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "RecursoVinculado";
    $this->aDados       = array();
  }
  
  /**
   * Gera os dados para utilizacao posterior. Metodo geralmente usado 
   * em conjuto com a classe PadArquivoEscritorXML
   * @return true;
   */
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
    
    $sSqlRecurso  = "select o15_codigo as codigo, ";
    $sSqlRecurso .= "       o15_descr  as nome,";
    $sSqlRecurso .= "       o15_codtri as codigorecurso,";
    $sSqlRecurso .= "       o15_finali as finalidade"; 
    $sSqlRecurso .= "  from orctiporec ";
    $sSqlRecurso .= " where trim(o15_descr) != ''";
    $sSqlRecurso .= " order by o15_codigo";
    
    $rsRecurso    = db_query($sSqlRecurso);
    $iTotalLinhas = pg_num_rows($rsRecurso); 
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oRecurso   = db_utils::fieldsMemory($rsRecurso, $i);
      
      $oRecursoRetorno                                = new stdClass();
      $oRecursoRetorno->recCodigoEntidade             = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oRecursoRetorno->recMesAnoMovimento            = $sDiaMesAno;
      $oRecursoRetorno->recCodigoRecursoVinculado     = str_pad($oRecurso->codigo, 4, "0", STR_PAD_LEFT);
      $oRecursoRetorno->recNomeRecursoVinculado       = substr($oRecurso->nome, 0, 80);
      $oRecursoRetorno->recFinalidadeRecursoVinculado = substr(str_replace("\n"," ", $oRecurso->finalidade), 0, 160);
      if (db_getsession("DB_anousu") >= 2011) {
        $oRecursoRetorno->recCodigoRecursoVinculado     = str_pad($oRecurso->codigorecurso, 6, "0", STR_PAD_LEFT);
      }
      array_push($this->aDados, $oRecursoRetorno);
      
    }
    return true;
  }
  
  /**
   * Publica quais elementos/Campos estão disponiveis para 
   * o uso no momento da geração do arquivo
   *
   * @return array com elementos disponibilizados para a geração dos arquivo
   */
  public function getNomeElementos() {
    
    $aElementos = array(
                        "recCodigoEntidade",
                        "recMesAnoMovimento",
                        "recCodigoRecursoVinculado",
                        "recNomeRecursoVinculado",
                        "recFinalidadeRecursoVinculado"
                       );
    if (db_getsession("DB_anousu") >= 2011) {

      $aElementos = array(
                        "recCodigoEntidade",
                        "recMesAnoMovimento",
                        "recCodigoRecursoVinculado",
                       );
    }
    return $aElementos;  
  }
  
}

?>