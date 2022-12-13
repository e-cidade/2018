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
 * Prove dados para a geração do arquivo dos dados da despesa do ppa loa no periodo
 * do municipio para o SIGAP
 * @package Pad
 * @author  Luiz Marcelo Schmitt
 * @version $Revision: 1.3 $
 */
final class PadArquivoSigapPpaLoa extends PadArquivoSigap {
  
  /**
   * Metodo construtor da classe
   */
  public function __construct() {
    
    $this->sNomeArquivo = "PpaLoa";
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
    $oInstituicao  = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit  = db_getsession("DB_instit");
    $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
    for ($i = 0; $i < 1; $i++) {
       
       $oPPA = new stdClass();
       $oPPA->ploCodigoEntidade                  = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT); 
       $oPPA->ploComplementacao                  = '';
       $oPPA->ploDataLDO                         = '';
       $oPPA->ploDataLeiPPA                      = '';
       $oPPA->ploDataPubLDO                      = '';
       $oPPA->ploDataPubLeiAlteracao             = '';
       $oPPA->ploDataPubLeiPPA                   = '';
       $oPPA->ploExercicio                       = '';
       $oPPA->ploMesAnoMovimento                 = $sDiaMesAno;
       $oPPA->ploMetaResultadoNominal1oBimestre  = 0;
       $oPPA->ploMetaResultadoNominal2oBimestre  = 0;
       $oPPA->ploMetaResultadoNominal3oBimestre  = 0;;
       $oPPA->ploMetaResultadoNominal4oBimestre  = 0;
       $oPPA->ploMetaResultadoNominal5oBimestre  = 0;
       $oPPA->ploMetaResultadoNominal6oBimestre  = 0;
       $oPPA->ploMetaResultadoPrimario1oBimestre = 0;;
       $oPPA->ploMetaResultadoPrimario2oBimestre = 0;
       $oPPA->ploMetaResultadoPrimario3oBimestre = 0;
       $oPPA->ploMetaResultadoPrimario4oBimestre = 0;
       $oPPA->ploMetaResultadoPrimario5oBimestre = 0;
       $oPPA->ploMetaResultadoPrimario6oBimestre = 0;
       $oPPA->ploNumLDO                          = 0;
       $oPPA->ploNumLeiAlteracao                 = 0;
       $oPPA->ploNumLeiPPA                       = 0;
       $oPPA->ploPercCreditoAdicional            = 0;
       $oPPA->ploPercCreditoAntecipacao          = 0;
       $oPPA->ploPerOpCreditoExterno             = 0;
       $oPPA->ploPerOpCreditoIntermo             = 0;
       $this->aDados[] = $oPPA;
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
                         "ploCodigoEntidade",
                         "ploComplementacao",
                         "ploDataLDO",
                         "ploDataLeiPPA",
                         "ploDataPubLDO",
                         "ploDataPubLeiAlteracao",
                         "ploDataPubLeiPPA",
                         "ploExercicio",
                         "ploMesAnoMovimento",
                         "ploMetaResultadoNominal1oBimestre",
                         "ploMetaResultadoNominal2oBimestre",
                         "ploMetaResultadoNominal3oBimestre",
                         "ploMetaResultadoNominal4oBimestre",
                         "ploMetaResultadoNominal5oBimestre",
                         "ploMetaResultadoNominal6oBimestre",
                         "ploMetaResultadoPrimario1oBimestre",
                         "ploMetaResultadoPrimario2oBimestre",
                         "ploMetaResultadoPrimario3oBimestre",
                         "ploMetaResultadoPrimario4oBimestre",
                         "ploMetaResultadoPrimario5oBimestre",
                         "ploMetaResultadoPrimario6oBimestre",
                         "ploNumLDO",
                         "ploNumLeiAlteracao",
                         "ploNumLeiPPA",
                         "ploPercCreditoAdicional",
                         "ploPercCreditoAntecipacao",
                         "ploPerOpCreditoExterno",
                         "ploPerOpCreditoIntermo"
                       );
    return $aElementos;  
  }
  
  private function corrigeValor($valor, $quant) {
  	
    if (empty($valor)) {
      $valor = 0;
    }
    
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