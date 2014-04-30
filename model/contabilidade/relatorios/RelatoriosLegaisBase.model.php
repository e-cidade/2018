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

/**
 * classe para controle dos valores dos anexos legais da RGF/LRF 
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 * 
 */
abstract class RelatoriosLegaisBase {
  

  /**
   * instacia da classe RelatorioContabil
   *
   * @var relatorioContabil
   */
  protected $oRelatorioLegal;
  
  /**
   * Exericio do relatorio
   *
   * @var integer
   */
  protected $iAnoUsu;
  
  /**
   * Codigo do relatorio 
   *
   * @var integer
   */
  protected $iCodigoRelatorio;
  
  /**
   * Linhas do Relatório
   *
   * @var integer
   * 
   */
  protected $aDados = array();
  
  /**
   * lista de Instituições
   *
   * @var string
   */
  protected $sListaInstit;
  /**
   * Codigo do periodo de emissao
   *
   * @var integer
   * 
   */
  protected $iCodigoPeriodo;
  
  /**
   *
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
     
    $this->iCodigoRelatorio = $iCodigoRelatorio;
    $this->iAnoUsu          = $iAnoUsu;
    $this->iCodigoPeriodo   = $iCodigoPeriodo;
    $this->oRelatorioLegal = new relatorioContabil($iCodigoRelatorio, false);
  }
  
  /**
   * retorna os dados do relatorio.
   *
   */
  public function getDados() {
   
   
  }
 
  /**
   * retorna os dados necessários para o relatorio simplidicado
   *
   */
  public function getDadosSimplificado() {
   
  }
  
  /**
   * define as instituicoes que serao usadas no relatorio
   *
   * @param integer $sInstituicoes lista das instituicoes, seperadas por virgula
   */
  public function setInstituicoes($sInstituicoes) {
    $this->sListaInstit = $sInstituicoes;
  }
  
  /**
   * retorna as instituicoes selecionadas para o relatorio
   *
   * @return string
   */
  public function getInstituicoes() {
    return $this->sListaInstit;
  }
  
  /**
   * Processa as formulas do relatorio
   *
   * @param array $aLinhas linhas dos relatorios
   */
  public function processaTotalizadores ($aLinhas)  {
    
    foreach ($aLinhas as $oLinha) {

      if ($oLinha->totalizar) {

        foreach ($oLinha->colunas as $iColuna => $oColuna) {
           
          if (trim($oColuna->o116_formula) != "") {
             
            $sFormulaOriginal = ($oColuna->o116_formula);
            $sFormula         = $this->oRelatorioLegal->parseFormula('aLinhas', $sFormulaOriginal, $iColuna, $aLinhas);
            $evaluate         = "\$oLinha->{$oColuna->o115_nomecoluna} = {$sFormula};";
            eval($evaluate);
          }
        }
      }
    }
  }
  
  /**
   * Retorna os periodos cadastras para o relatorio
   *
   * @return array();
   */
  public  function getPeriodos() {
    
    return $this->oRelatorioLegal->getPeriodos();
  }
  
  /**
   * Monta a nota explicativa
   *
   * @param FPDF $oPdf instancia do PDf
   * @param integer $iPeriodo Codigo do periodo
   * @param integer $iTam Tamanho da celula
   * @return void
   */
  public function getNotaExplicativa($oPdf, $iPeriodo,$iTam = 190) {
    return $this->oRelatorioLegal->getNotaExplicativa($oPdf, $iPeriodo,$iTam = 190);
  }
}