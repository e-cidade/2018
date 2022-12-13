<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once ("model/contabilidade/relatorios/RelatoriosLegaisBase.model.php");

class AnexoDemonstrativoReceitaPublica extends RelatoriosLegaisBase {
  

  /**
   * Método Construtor
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }
  
  public function getDados() {

    $aLinhasRelatorio = $this->oRelatorioLegal->getLinhasCompleto();
    $aLinhasRetorno   = array();

    for ($iLinha = 1; $iLinha <= count($aLinhasRelatorio); $iLinha++ ) {
      
      $aColunasRelatorio  = $aLinhasRelatorio[$iLinha]->getCols($this->iCodigoPeriodo);
      $oLinha             = new stdClass();
      $oLinha->totalizar  = $aLinhasRelatorio[$iLinha]->isTotalizador();
      $oLinha->descricao  = $aLinhasRelatorio[$iLinha]->getDescricaoLinha();
      $oLinha->colunas    = $aColunasRelatorio; 
      $oLinha->desdobrar  = false;
      $oLinha->contas     = array();
      $oLinha->nivellinha = $aLinhasRelatorio[$iLinha]->getNivel();
      foreach ($aColunasRelatorio as $oColuna) {
       
        $oLinha->{$oColuna->o115_nomecoluna} = 0;
        if (!$aLinhasRelatorio[$iLinha]->isTotalizador()) {
          $oColuna->o116_formula = '';
        }
      }
      if (!$aLinhasRelatorio[$iLinha]->isTotalizador()) {
        
        $aColunaValor = $aLinhasRelatorio[$iLinha]->getValoresColunas(null,null,$this->getInstituicoes(),$this->iAnoUsu);
        if ($iLinha == 1) {
          
          if (count($aLinhasRelatorio) == 0) {
            throw new Exception("Nenhuma linha configurada para este relatório.");
          }
          
          $oLinha->dadoslinha = array();
          foreach ($aColunaValor as $oDadosColuna) { 
            
            $oDadosUsuario             = new stdClass();
            $oDadosUsuario->descricao  = $oDadosColuna->colunas[0]->o117_valor;
            $oDadosUsuario->valor      = $oDadosColuna->colunas[1]->o117_valor;
            $oLinha->dadoslinha[]      = $oDadosUsuario;
            $oLinha->valor            += $oDadosColuna->colunas[1]->o117_valor;
          }
        }
        
        /**
         * Caso a Linha seja a 3, será feito o calculo da Receita Corrente Liquida
         * Quando não houver valores digitados pelo usuário na aba parâmetros, será buscado o valor
         * total da receita corrente liquida
         */
        if ($iLinha == 3) {
        
          $iTotalRCL = 0;
          foreach ($aColunaValor as $oDadosColuna) { 
             $iTotalRCL += $oDadosColuna->colunas[0]->o117_valor;
          }
          
          if ($iTotalRCL == 0) {
            
            $oModelRCL = new AnexoReceitaCorrenteLiquida($this->iAnoUsu, 119, $this->iCodigoPeriodo);
            $iTotalRCL = $oModelRCL->getValorRCL();
          }
          $oLinha->valor = $iTotalRCL;
        }
      }
      $aLinhasRetorno[$iLinha] = $oLinha;
    }
    
    $aLinhasRetorno[2]->valor = $aLinhasRetorno[1]->valor;
    
    /**
      * calcula os totalizadores do relatório, aplicando as formulas.
      */
    foreach ($aLinhasRetorno as $iLinha => $oLinha) {

      if ($oLinha->totalizar) {

        foreach ($oLinha->colunas as $iColuna => $oColuna) {
           
          if (trim($oColuna->o116_formula) != "") {
             
            $sFormulaOriginal = ($oColuna->o116_formula);
            $sFormula         = $this->oRelatorioLegal->parseFormula('aLinhasRetorno', $sFormulaOriginal, $iColuna, $aLinhasRetorno);
            $evaluate         = "\$oLinha->{$oColuna->o115_nomecoluna} = {$sFormula};";
            ob_start();
            eval($evaluate);
            $sRetorno = ob_get_contents();
            ob_clean();
            if (strpos(strtolower($sRetorno), "parse error") > 0 || strpos(strtolower($sRetorno), "undefined" > 0)) {
              
              $sMsg =  "Linha {$iLinha} com erro no cadastro da formula<br>{$oColuna->o116_formula}";
              throw new Exception($sMsg);
            }
          }
        }
      }
    }
    return $aLinhasRetorno;
  }
}
?>