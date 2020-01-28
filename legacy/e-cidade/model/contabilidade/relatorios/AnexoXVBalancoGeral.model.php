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
 * classe para controle dos valores do Anexo XIV do balanço Geral
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 * 
 */

class AnexoXVBalancoGeral extends RelatoriosLegaisBase  {
  
  
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
  	
     $aLinhas                   = array();
     
     /**
      * montamos as datas, e processamos o balancete de verificação
      */
     $oDaoPeriodo      = db_utils::getDao("periodo");
     $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
     $rsPeriodo        = db_query($sSqlDadosPeriodo);
     $oDadosPerido     = db_utils::fieldsMemory($rsPeriodo, 0); 
     $sDataInicial     = "{$this->iAnoUsu}-01-01";
     $iUltimoDiaMes    = cal_days_in_month(CAL_GREGORIAN, $oDadosPerido->o114_mesfinal, $this->iAnoUsu);
     $sDataFinal       = "{$this->iAnoUsu}-{$oDadosPerido->o114_mesfinal}-{$iUltimoDiaMes}";
     $sWherePlano      = " c61_instit in ({$this->getInstituicoes()}) ";
     $sWhereReceita    = " o70_instit in ({$this->getInstituicoes()}) ";
     $sWhereDespesa    = " o58_instit in ({$this->getInstituicoes()}) ";
     $aLinhasUsamPlano = array(22, 23, 24, 25, 26, 27, 42, 43, 45, 46, 47);
     /**
      * processa o balancete de verificação
      */
     $rsPlano = db_planocontassaldo_matriz($this->iAnoUsu, 
                                           $sDataInicial, 
                                           $sDataFinal, 
                                           false,
                                           $sWherePlano,
                                           '',
                                           'true',
                                           'false'
                                           );
     $iTotalLinhasPlano = pg_num_rows($rsPlano);     
     $rsReceita        = db_receitasaldo(11, 1, 3, true, 
                                        $sWhereReceita, 
                                        $this->iAnoUsu, 
                                        $sDataInicial, 
                                        $sDataFinal);
                                        
     $iTotalLinhasReceita = pg_num_rows($rsReceita);
     
     $rsDespesa = db_dotacaosaldo(7, 3, 4, true, $sWhereDespesa, $this->iAnoUsu, $sDataInicial, $sDataFinal);
     $iTotalLinhasDespesa = pg_num_rows($rsDespesa);
     /**
      * percorremos a slinhas cadastradas no relatorio, e adicionamos os valores cadastrados manualmente.
      */
     $aLinhasRelatorio = $this->oRelatorioLegal->getLinhasCompleto();
     for ($iLinha = 1; $iLinha <= count($aLinhasRelatorio); $iLinha++) {
       
       $aLinhasRelatorio[$iLinha]->setPeriodo($this->iCodigoPeriodo);
       $aColunasRelatorio  = $aLinhasRelatorio[$iLinha]->getCols($this->iCodigoPeriodo);
       $aColunaslinha      = array();
       $oLinha             = new stdClass();
       $oLinha->totalizar  = $aLinhasRelatorio[$iLinha]->isTotalizador();
       $oLinha->descricao  = $aLinhasRelatorio[$iLinha]->getDescricaoLinha();
       $oLinha->colunas    = $aColunasRelatorio; 
       $oLinha->contas     = array(); 
       $oLinha->desdobrar  = false; 
       $oLinha->nivellinha = $aLinhasRelatorio[$iLinha]->getNivel();
       $aParametros        = $aLinhasRelatorio[$iLinha]->getParametros($this->iAnoUsu, $this->getInstituicoes());
       if ($aParametros->desdobrarlinha && $aLinhasRelatorio[$iLinha]->desdobraLinha()) {
         $oLinha->desdobrar  = true;
       }
       foreach ($aColunasRelatorio as $oColuna) {
         
         $oLinha->{$oColuna->o115_nomecoluna} = 0;
         if (!$aLinhasRelatorio[$iLinha]->isTotalizador()) {
           $oColuna->o116_formula = '';
         }
       }
       if (!$aLinhasRelatorio[$iLinha]->isTotalizador()) {
         
         
         $aValoresColunasLinhas = $aLinhasRelatorio[$iLinha]->getValoresColunas(null, null, $this->getInstituicoes(), 
                                                                                $this->iAnoUsu);
         
         $oLinha->valor         = 0;
         foreach($aValoresColunasLinhas as $oValor) {
           $oLinha->{$oValor->colunas[0]->o115_nomecoluna} += $oValor->colunas[0]->o117_valor;
         }
         
         /**
          * verificamos se a a conta cadastrada existe no balancente, e somamos o valor encontrado na linha
          */
         if ($iLinha >= 1 && $iLinha <= 21) {
           
           for ($i = 0; $i < $iTotalLinhasReceita; $i++) {
    
             $oReceita   = db_utils::fieldsMemory($rsReceita, $i);  
             $oParametro = $aParametros;
             foreach ($oParametro->contas as $oConta) {
                
               $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oReceita, 1);
               if ($oVerificacao->match) {
      
                 if ($oVerificacao->exclusao) {
                   $oReceita->saldo_arrecadado_acumulado  *= -1;  
                 }
                 $oLinha->valor += $oReceita->saldo_arrecadado_acumulado;
               }
             }
           }
           
         } else if (in_array($iLinha, $aLinhasUsamPlano)) {
           
           for ($i = 0; $i < $iTotalLinhasPlano; $i++) {
    
             $oResultado = db_utils::fieldsMemory($rsPlano, $i);  
             $oParametro  = $aParametros;
             foreach ($oParametro->contas as $oConta) {
                
               $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 3);
               if ($oVerificacao->match) {
      
                 if ($oVerificacao->exclusao) {
              
                   $oResultado->saldo_anterior *= -1;  
                   $oResultado->saldo_final    *= -1;  
                 }
                 if ($oLinha->desdobrar) {
                   
                   $oContaDesdobrada = new stdClass();
                   $oContaDesdobrada->descricao             = $oResultado->c60_descr; 
                   $oContaDesdobrada->valor                 = $oResultado->saldo_final; 
                   $oLinha->contas[$oResultado->estrutural] = $oContaDesdobrada;
                 }
                 $oLinha->valor += $oResultado->saldo_final;
               }
             }
           }
         } else if ($iLinha >= 33 && $iLinha <= 41) {
           
           for ($i = 0; $i < $iTotalLinhasDespesa; $i++) {
    
             $oResultado = db_utils::fieldsMemory($rsDespesa, $i);  
             $oParametro  = $aParametros;
             foreach ($oParametro->contas as $oConta) {
                
               $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 2);
               if ($oVerificacao->match) {
      
                 if ($oVerificacao->exclusao) {
              
                   $oResultado->empenhado_acumulado *= -1;  
                   $oResultado->anulado_acumulado   *= -1;  
                 }
                 $oLinha->valor += ($oResultado->empenhado_acumulado - $oResultado->anulado_acumulado);
               }
             }
           }
         }
       }
       $aLinhas[$iLinha] = $oLinha;
     }
     
     unset($aLinhasRelatorio);
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
     
     return $aLinhas;    
  }
  
}