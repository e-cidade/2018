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
/**
 * Prove dados para a geração do arquivo de subsidio dos Vereadores
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.1 $
 */
final class PadArquivoSigapSubsidioVereadores extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "SubsidioVereadores";
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
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit = db_getsession("DB_instit");
    $sSqlSubsidio  = "select c16_mes,  ";
    $sSqlSubsidio .= "       c16_ano,";
    $sSqlSubsidio .= "       c16_subsidiomensal,";
    $sSqlSubsidio .= "       c16_subsidioextraordinario,";
    $sSqlSubsidio .= "       z01_nome, ";
    $sSqlSubsidio .= "       z01_cgccpf ";
    $sSqlSubsidio .= "  from padsigapsubsidiosvereadores "; 
    $sSqlSubsidio .= "       inner join cgm on z01_numcgm = c16_numcgm "; 
    $sSqlSubsidio .= " where c16_ano     = {$iAno} ";
    $sSqlSubsidio .= "   and c16_mes    <= $iMes"; 
    $sSqlSubsidio .= "   and c16_instit  = {$sListaInstit}";
    $sSqlSubsidio .= "   order by c16_ano, c16_mes";
    $rsSubsidio    = db_query($sSqlSubsidio); 
    $iTotalLinhas  = pg_num_rows($rsSubsidio);
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
      $oSubSidio  = db_utils::fieldsMemory($rsSubsidio, $i);
      
      $oRetorno  = new stdClass();
      $oRetorno->subCodigoEntidade   = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $oRetorno->subMesAnoMovimento  = $sDiaMesAno;
      $iUltimoDiaMes                 =  cal_days_in_month(CAL_GREGORIAN, $oSubSidio->c16_mes, $oSubSidio->c16_ano); 
      $oRetorno->subMesAnoReferencia = $oSubSidio->c16_ano."-".str_pad($oSubSidio->c16_mes, 2, "0", STR_PAD_LEFT)."-".
                                       str_pad($iUltimoDiaMes, 2, "0", STR_PAD_LEFT);
      $oRetorno->subNomeVereador     = substr($oSubSidio->z01_nome, 0, 80);                                       
      $oRetorno->subCPF              = str_pad($oSubSidio->z01_cgccpf, 11, "0", STR_PAD_LEFT);                                       
      $oRetorno->subMensal           = $this->corrigeValor($oSubSidio->c16_subsidiomensal, 13);                                       
      $oRetorno->subExtraordinario   = $this->corrigeValor($oSubSidio->c16_subsidioextraordinario, 13);                                       
      $oRetorno->subTotal            = $this->corrigeValor(($oSubSidio->c16_subsidioextraordinario+
                                                           $oSubSidio->c16_subsidiomensal), 13);
      array_push($this->aDados, $oRetorno);                                                                                                   
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
                        "subCodigoEntidade",
                        "subMesAnoMovimento",
                        "subMesAnoReferencia",
                        "subNomeVereador",
                        "subCPF",
                        "subMensal",
                        "subExtraordinario",
                        "subTotal",
                       );
    return $aElementos;  
  }
  private function corrigeValor ($valor, $quant) {
    
    if ($valor < 0) {
      
      $valor *= -1;
      $valor  = "-".str_pad(number_format($valor, 2, ".",""),  $quant-1, '0', STR_PAD_LEFT);
    } else {
      $valor  = str_pad(number_format($valor, 2, ".",""), $quant, '0', STR_PAD_LEFT);
    }
    return $valor;
  }
}

?>