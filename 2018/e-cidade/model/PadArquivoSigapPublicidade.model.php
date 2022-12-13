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
 * Prove dados para a geração do arquivo dos dados da despesa com publicidade no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Luiz Marcelo Schmitt
 * @version $Revision: 1.3 $
 */
final class PadArquivoSigapPublicidade extends PadArquivoSigap {
  
  /**
   * Metodo construtor da classe
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Publicidade";
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
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    $sListaInstit = db_getsession("DB_instit");

    $oDaoPublicidadeSigap  = db_utils::getDao("publicidadesigap");
    
    $sCampos               = "publicidadesigap.c48_datapublicacao,publicidadesigap.c48_tiporelatoriofiscal, ";
    $sCampos              .= "meiocomunicacaosigap.c49_codigocomunicacao,meiocomunicacaosigap.c49_descricao ";
    $sWhere                = "publicidadesigap.c48_instit in({$sListaInstit}) ";
    $sWhere               .= "and publicidadesigap.c48_mes = {$iMes}          ";
    $sWhere               .= "and publicidadesigap.c48_ano = {$iAno}          ";
    $sSqlPublicidadeSigap  = $oDaoPublicidadeSigap->sql_query(null, $sCampos, null, $sWhere);
    $rsSqlPublicidadeSigap = $oDaoPublicidadeSigap->sql_record($sSqlPublicidadeSigap);
    $iTotalLinhas          = $oDaoPublicidadeSigap->numrows;
    
    for ($i = 0; $i < $iTotalLinhas; $i++) {
          
      $oPublicidadeSigap        = db_utils::fieldsMemory($rsSqlPublicidadeSigap, $i);
      
      $oPublicidadeSigapRetorno = new stdClass();
      $oPublicidadeSigapRetorno->pubCodigoEntidade      = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $sDiaMesAno                                       = "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT).
                                                           '-'.str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oPublicidadeSigapRetorno->pubMesAnoMovimento     = $sDiaMesAno;
      $oPublicidadeSigapRetorno->pubDescricao           = substr($oPublicidadeSigap->c49_descricao, 0, 255);
      $oPublicidadeSigapRetorno->pubExercicio           = $iAno;
      $oPublicidadeSigapRetorno->pubDataPublicacao      = $oPublicidadeSigap->c48_datapublicacao;
      $oPublicidadeSigapRetorno->pubTipoMeioComunicacao = str_pad($oPublicidadeSigap->c49_codigocomunicacao, 
                                                                  4, "0", STR_PAD_LEFT);
      $oPublicidadeSigapRetorno->pubTipoRelatorioFiscal = str_pad($oPublicidadeSigap->c48_tiporelatoriofiscal, 
                                                                  4, "0", STR_PAD_LEFT);
      
      array_push($this->aDados, $oPublicidadeSigapRetorno);
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
                         "pubCodigoEntidade",
                         "pubMesAnoMovimento",
                         "pubDescricao",
                         "pubExercicio",
                         "pubDataPublicacao",
                         "pubTipoMeioComunicacao",
                         "pubTipoRelatorioFiscal"
                       );
    return $aElementos;  
  }
}
?>