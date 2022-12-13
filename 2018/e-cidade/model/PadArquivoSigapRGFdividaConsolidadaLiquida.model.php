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
 * Prove dados para a geração do arquivo dados da RGF divida consolidada liquida no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Luiz Marcelo Schmitt
 * @version $Revision: 1.2 $
 */
final class PadArquivoSigapRGFdividaConsolidadaLiquida extends PadArquivoSigap {
  
  /**
   * Metodo construtor da classe
   */
  public function __construct() {
    
    $this->sNomeArquivo = "RGFDividaConsolidadaLiquida";
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
                         "dclCodigoEntidade",
                         "dclMesAnoMovimento",
                         "dclSinal",
                         "dclSaldoExercicioAnterior",
                         "dclSaldoAte1oQuad",
                         "dclSaldoAte2oQuad",
                         "dclSaldoAte3oQuad",
                         "dclContaLRF",
                         "dclDescricaoContaLRF",
                         "dclConta"
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