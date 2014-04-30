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
 * Prove dados para a geração do arquivo dos dados da despesa do ppa no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Luiz Marcelo Schmitt
 * @version $Revision: 1.3 $
 */
final class PadArquivoSigapPpa extends PadArquivoSigap {
  
  private  $iCodigoVersao;
  /**
   * Metodo construtor da classe
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Ppa";
    $this->aDados       = array();
    $this->iCodigoVersao = '';
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
    /**.
     * Separamos a data do em ano, mes, dia
     */
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    $oInstituicao  = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit  = db_getsession("DB_instit");
    
    /**
     * selecionamos todos os dados da perspectiva do ppa escolhidase agrupamos por orgao, unidade, 
     * programa, projeto/atividade
     */
     $sSqlDespesaPPA  = "select o08_orgao, "; 
     $sSqlDespesaPPA .= "       o08_unidade, ";
     $sSqlDespesaPPA .= "       o08_programa, ";
     $sSqlDespesaPPA .= "       o08_projativ, ";
     $sSqlDespesaPPA .= "       o08_ano, ";
     $sSqlDespesaPPA .= "       sum(o05_valor) as valor";
     $sSqlDespesaPPA .= "  from ppadotacao ";    
     $sSqlDespesaPPA .= "       inner join ppaestimativadespesa on o08_sequencial = o07_coddot ";    
     $sSqlDespesaPPA .= "       inner join ppaestimativa        on o07_ppaestimativa = o05_sequencial";    
     $sSqlDespesaPPA .= " where o08_ppaversao = {$this->iCodigoVersao}";    
     $sSqlDespesaPPA .= "   and o08_instit    = ".db_getsession("DB_instit");    
     $sSqlDespesaPPA .= "   and o05_base is false ";    
     $sSqlDespesaPPA .= "  group by  o08_orgao, "; 
     $sSqlDespesaPPA .= "       o08_unidade, ";
     $sSqlDespesaPPA .= "       o08_programa, ";
     $sSqlDespesaPPA .= "       o08_projativ, ";
     $sSqlDespesaPPA .= "       o08_ano ";
     $rsDadosPPA      = db_query($sSqlDespesaPPA);
     $aDadosDespesas = array();
     for ($i = 0; $i < pg_num_rows($rsDadosPPA); $i++) {

        $oLinha = db_utils::fieldsMemory($rsDadosPPA, $i);
        $sHash  = $oLinha->o08_orgao.$oLinha->o08_unidade.$oLinha->o08_programa.$oLinha->o08_projativ;
        if (!isset($aDadosDespesas[$sHash])) {

          $oPrograma = new stdClass();
          $oPrograma->orgao    = $oLinha->o08_orgao;
          $oPrograma->unidade  = $oLinha->o08_unidade;
          $oPrograma->programa = $oLinha->o08_programa;
          $oPrograma->projativ = $oLinha->o08_projativ;
          $oPrograma->anos     = array();
          $aDadosDespesas[$sHash] = $oPrograma;
        }
        $oAno = new stdClass();
        $oAno->valorMeta   = $oLinha->valor;
        $oAno->valorFisico = 0;
        $sSqlValorFisico  = "select coalesce(sum(o28_valor), 0) as valorFisico"; 
        $sSqlValorFisico .= "  from orcprojativprogramfisica "; 
        $sSqlValorFisico .= " where o28_orcprojativ = {$oLinha->o08_projativ} ";  
        $sSqlValorFisico .= "   and o28_anoref = {$oLinha->o08_ano}";
        $rsValorFisico    = db_query($sSqlValorFisico);
        $oAno->valorFisico = db_utils::fieldsMemory($rsValorFisico, 0)->valorfisico; 
        $aDadosDespesas[$sHash]->anos[$oLinha->o08_ano] = $oAno; 
     }
     $sDiaMesAno =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
     foreach ($aDadosDespesas as $oDado) {
       
       
       $oPPA = new stdClass();
       $oPPA->ppaCodigoEntidade            = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
       $oPPA->ppaExercicio                 = str_pad($iAno, 4, "0", STR_PAD_LEFT);
       $oPPA->ppaCodigoOrgao               = str_pad($oDado->orgao, 4, "0", STR_PAD_LEFT);
       $oPPA->ppaCodigoPrograma            = str_pad($oDado->programa, 4, "0", STR_PAD_LEFT);
       $oPPA->ppaCodigoProjetoAtividade    = str_pad($oDado->projativ, 4, "0", STR_PAD_LEFT);
       $oPPA->ppaCodigoUnidadeOrcamentaria = str_pad($oDado->unidade, 4, "0", STR_PAD_LEFT);
       $oPPA->ppaMesAnoMovimento           = $sDiaMesAno;
       $iInicio      = 1;
       $nTotalMeta   = 0;
       $nTotalFisico = 0;
       foreach ($oDado->anos as $oValor) {
         
         $oPPA->{"ppaMetaFinanceira{$iInicio}Ano"} = $oValor->valorMeta;  
         $oPPA->{"ppaMetaFisica{$iInicio}Ano"}     = $oValor->valorFisico;
         $nTotalMeta   += $oValor->valorMeta; 
         $nTotalFisico += $oValor->valorFisico; 
         $iInicio++;
       }
       $oPPA->ppaMetaFinanceiraTotal = $nTotalMeta;
       $oPPA->ppaMetaFisicaTotal     = $nTotalFisico;
       array_push($this->aDados, $oPPA);
     }
     unset($aDadosDespesas);
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
                         "ppaCodigoEntidade",
                         "ppaCodigoOrgao",
                         "ppaCodigoPrograma",
                         "ppaCodigoProjetoAtividade",
                         "ppaCodigoUnidadeOrcamentaria",
                         "ppaExercicio",
                         "ppaMesAnoMovimento",
                         "ppaMetaFinanceira1Ano",
                         "ppaMetaFinanceira2Ano",
                         "ppaMetaFinanceira3Ano",
                         "ppaMetaFinanceira4Ano",
                         "ppaMetaFinanceiraTotal",
                         "ppaMetaFisica1Ano",
                         "ppaMetaFisica2Ano",
                         "ppaMetaFisica3Ano",
                         "ppaMetaFisica4Ano",
                         "ppaMetaFisicaTotal"
                       );
    return $aElementos;  
  }
  
  public function setCodigoVersao($iCodigoVersao) {
    $this->iCodigoVersao = $iCodigoVersao;
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