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

class AnexoReceitaCorrenteLiquida extends RelatoriosLegaisBase {
  
  /**
   * Método Construtor
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }

  /**
   * Método getDados
   *
   * @return array $aRetorno
   */
  public function getDados() {
    
    /**
     * Para executar este anexo é necessário buscar por todas as instituições
     * este trecho configura a variável de instituições
     */
    $rsBuscaInstit = db_query("select codigo from db_config");
    $aDadosInstit  = db_utils::getCollectionByRecord($rsBuscaInstit);
    $sVirgula      = "";
    $sDadosInstit  = "";
    
    /**
     * Percorre o ARRAY de retorno separando os resultados por vírgula
     */
    foreach ($aDadosInstit as $iCodInstit) {
      $sDadosInstit .= $sVirgula.$iCodInstit->codigo;
      $sVirgula      = ",";
    }
    $this->setInstituicoes($sDadosInstit);
    
    $oDaoPeriodo      = db_utils::getDao("periodo");
    $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
    $rsPeriodo        = db_query($sSqlDadosPeriodo);
    $oDadosPerido     = db_utils::fieldsMemory($rsPeriodo, 0); 
    $sDataInicial     = "{$this->iAnoUsu}-01-01";
    $iUltimoDiaMes    = cal_days_in_month(CAL_GREGORIAN, $oDadosPerido->o114_mesfinal, $this->iAnoUsu);
    $sDataFinal       = "{$this->iAnoUsu}-{$oDadosPerido->o114_mesfinal}-{$iUltimoDiaMes}";
    $sWhereReceita    = " o70_instit in ( {$this->getInstituicoes()} ) ";
    $rsReceita        = db_receitasaldo(11, 1, 3, true, 
                                        $sWhereReceita, 
                                        $this->iAnoUsu, 
                                        $sDataInicial, 
                                        $sDataFinal);
                                        
    $iTotalLinhasReceita   = pg_num_rows($rsReceita);
    $aLinhasRelatorio = $this->oRelatorioLegal->getLinhasCompleto();
    $aLinhas = array();

    for ($iLinha = 1; $iLinha <= count($aLinhasRelatorio); $iLinha++) {
       
      $aLinhasRelatorio[$iLinha]->setPeriodo($this->iCodigoPeriodo);
      $aColunasRelatorio  = $aLinhasRelatorio[$iLinha]->getCols($this->iCodigoPeriodo);
      $aColunaslinha      = array();
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
       
        $aValoresColunasLinhas = $aLinhasRelatorio[$iLinha]->getValoresColunas(null, null, $this->getInstituicoes(), 
                                                                              $this->iAnoUsu);

        foreach($aValoresColunasLinhas as $oValor) {
          $oLinha->{$oValor->colunas[0]->o115_nomecoluna} += $oValor->colunas[0]->o117_valor;
        }
        
        $oParametro = $aLinhasRelatorio[$iLinha]->getParametros($this->iAnoUsu, $this->getInstituicoes());
        if ($aLinhasRelatorio[$iLinha]->desdobraLinha() && $oParametro->desdobrarlinha) {
          $oLinha->desdobrar  = true;          
        }
        /**
         * linhas que usam o balancete de receita.
         */
        for ($i = 0; $i < $iTotalLinhasReceita; $i++) {
  
          $oReceita      = db_utils::fieldsMemory($rsReceita, $i);
          $oLinhaCalcula = clone $oReceita;
          foreach ($oParametro->contas as $oConta) {
            
            $oVerificacao  = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oReceita, 1);
            if ($oVerificacao->match) {
  
              if ($oVerificacao->exclusao) {
                $oLinhaCalcula->saldo_inicial  *= -1;  
              }
              $oLinha->valor += $oLinhaCalcula->saldo_inicial;
            }
          }
          unset ($oLinhaCalcula);
        }
      }
      
      $aLinhas[$iLinha] = $oLinha;
    }
    /*
     * calcula os totalizadores do relatório, aplicando as formulas.
     */
    foreach ($aLinhas as $iLinha => $oLinha) {

      if ($oLinha->totalizar) {

        foreach ($oLinha->colunas as $iColuna => $oColuna) {
           
          if (trim($oColuna->o116_formula) != "") {
             
            $sFormulaOriginal = ($oColuna->o116_formula);
            $sFormula         = $this->oRelatorioLegal->parseFormula('aLinhas', $sFormulaOriginal, $iColuna, $aLinhas);
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
    return $aLinhas;
  }
  
  /**
   * Método getValorRCL()
   * 
   * Retorna o valor da Receita Corrente Liquida
   * @return float
   */
  public function getValorRCL() {
    
    $aDados = $this->getDados();
    return $aDados[27]->valor;
  }
}

?>