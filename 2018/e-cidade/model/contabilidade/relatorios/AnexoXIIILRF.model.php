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

/**
 * classe para controle dos valores do Anexo XIII da LRF
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 * 
 */

class AnexoXIIILRF extends RelatoriosLegaisBase  {
  
  
  /**
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
     parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }
  
  /**
   * retorna os dados da classe em forma de objeto.
   * o objeto de retorno tera a seguinte forma:
   * 
   * @return array - Colecao de stdClass
   */
  public function getDados() {
  	
     $aRetorno        = array();
     $oLinhaRelatorio = new linhaRelatorioContabil($this->iCodigoRelatorio, 1);
     $oLinhaRelatorio->setPeriodo($this->iCodigoPeriodo);
     $aValoresColunasLinhas = $oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(), $this->iAnoUsu);
     foreach($aValoresColunasLinhas as $oValor) {
       
       $iAno = $oValor->colunas[0]->o117_valor;
       if (!isset($aRetorno[$iAno])) {
         
         $aRetorno[$iAno] = new stdClass();
         $aRetorno[$iAno]->ano                     = $iAno;    
         $aRetorno[$iAno]->receitasprevidenciarias = 0;    
         $aRetorno[$iAno]->despesasprevidenciarias = 0;
         $aRetorno[$iAno]->resultadoprevidenciario = 0;    
         $aRetorno[$iAno]->saldofinanceiro         = 0;    
       }
       
       $aRetorno[$iAno]->receitasprevidenciarias += $oValor->colunas[1]->o117_valor;    
       $aRetorno[$iAno]->despesasprevidenciarias += $oValor->colunas[2]->o117_valor; 
       $aRetorno[$iAno]->resultadoprevidenciario += $aRetorno[$iAno]->receitasprevidenciarias - 
                                                    $aRetorno[$iAno]->despesasprevidenciarias;     
     }
     
     /*
      * Ordena os Resultados no array sem perder indices
      * E Calcula Saldo Financeiro do exercicio anterior
      * com exercicio atual
      * 
      */
     ksort($aRetorno);
     foreach ($aRetorno as $iAno => &$oRetorno) {

       $nValorAnterior = 0;
       if (isset($aRetorno[$iAno-1])) {
         $nValorAnterior = $aRetorno[$iAno-1]->saldofinanceiro;
       }
       $oRetorno->saldofinanceiro = $nValorAnterior + $aRetorno[$iAno]->resultadoprevidenciario;
     }
     return $aRetorno;    
  }
  
  /**
   * Método que retorna para o anexo XVIII
   * as receitas,  despesas e   resultado para os exercicios os proximos 10 , 20 e 35 a frente
   * @return Objeto com os dados 
   */
  public function getDadosSimplificado() {
  
      /*
       * inicia o metodo anterior, para receber os valores calculados
       */
     $oRetorno        = new stdClass();
     
     $oRetorno->receitasprevidenciarias                   = new stdClass();
     $oRetorno->receitasprevidenciarias->exercicio        = 0;
     $oRetorno->receitasprevidenciarias->exercicio10      = 0;
     $oRetorno->receitasprevidenciarias->exercicio20      = 0;
     $oRetorno->receitasprevidenciarias->exercicio35      = 0;
     
     $oRetorno->despesasprevidenciarias                   = new stdClass();
     $oRetorno->despesasprevidenciarias->exercicio        = 0;
     $oRetorno->despesasprevidenciarias->exercicio10      = 0;
     $oRetorno->despesasprevidenciarias->exercicio20      = 0;
     $oRetorno->despesasprevidenciarias->exercicio35      = 0;
     
     $oRetorno->resultadoprevidenciario                   = new stdClass();
     $oRetorno->resultadoprevidenciario->exercicio        = 0;
     $oRetorno->resultadoprevidenciario->exercicio10      = 0;
     $oRetorno->resultadoprevidenciario->exercicio20      = 0;
     $oRetorno->resultadoprevidenciario->exercicio35      = 0;     
     
     
     $aLinhaRelatorio = $this->getDados();
     
     /*
      * Define as variaveis para o exercicio corrente
      * e 10,20,35 anos a frente do exercicio corrente
      */
     $iAno   = $this->iAnoUsu-1;
     $iAno10 = $iAno+10;
     $iAno20 = $iAno+20;
     $iAno35 = $iAno+35;
     
     // Valida se o ano corrente está na lista
     if (isset($aLinhaRelatorio[$iAno])) {
       
       $oRetorno->receitasprevidenciarias->exercicio        += $aLinhaRelatorio[$iAno]->receitasprevidenciarias;
       $oRetorno->despesasprevidenciarias->exercicio        += $aLinhaRelatorio[$iAno]->despesasprevidenciarias;
       $oRetorno->resultadoprevidenciario->exercicio        += $aLinhaRelatorio[$iAno]->resultadoprevidenciario;
     }
     
     // Testa se o ano corrente +10 esta cadastrado
     if (isset($aLinhaRelatorio[$iAno10])) {
       
       $oRetorno->receitasprevidenciarias->exercicio10        += $aLinhaRelatorio[$iAno10]->receitasprevidenciarias;
       $oRetorno->despesasprevidenciarias->exercicio10        += $aLinhaRelatorio[$iAno10]->despesasprevidenciarias;
       $oRetorno->resultadoprevidenciario->exercicio10        += $aLinhaRelatorio[$iAno10]->resultadoprevidenciario;              
     }
     
     // Testa se o ano corrente +20 esta cadastrado
     if (isset($aLinhaRelatorio[$iAno20])) {
       
       $oRetorno->receitasprevidenciarias->exercicio20        += $aLinhaRelatorio[$iAno20]->receitasprevidenciarias;
       $oRetorno->despesasprevidenciarias->exercicio20        += $aLinhaRelatorio[$iAno20]->despesasprevidenciarias;
       $oRetorno->resultadoprevidenciario->exercicio20        += $aLinhaRelatorio[$iAno20]->resultadoprevidenciario;              
     }     
     
     // Testa se o ano corrente +35 esta cadastrado e assume os valores calculados no metodo anterior
     if (isset($aLinhaRelatorio[$iAno35])) {
       
       $oRetorno->receitasprevidenciarias->exercicio35        += $aLinhaRelatorio[$iAno35]->receitasprevidenciarias;
       $oRetorno->despesasprevidenciarias->exercicio35        += $aLinhaRelatorio[$iAno35]->despesasprevidenciarias;
       $oRetorno->resultadoprevidenciario->exercicio35        += $aLinhaRelatorio[$iAno35]->resultadoprevidenciario;              
     }     
     
     return $oRetorno;   
    
  }
  
  
}