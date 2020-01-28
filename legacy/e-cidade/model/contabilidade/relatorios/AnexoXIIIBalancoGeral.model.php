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
 * classe para controle dos valores do Anexo XIIIV do balanço Geral
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 * 
 */

class AnexoXIIIBalancoGeral extends RelatoriosLegaisBase  {
  
  protected $lConsolidado = false;
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
     $oDaoPeriodo        = db_utils::getDao("periodo");
     $sSqlDadosPeriodo   = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
     $rsPeriodo          = db_query($sSqlDadosPeriodo);
     $oDadosPerido       = db_utils::fieldsMemory($rsPeriodo, 0); 
     $sDataInicial       = "{$this->iAnoUsu}-01-01";
     $iUltimoDiaMes      = cal_days_in_month(CAL_GREGORIAN, $oDadosPerido->o114_mesfinal, $this->iAnoUsu);
     $sDataFinal         = "{$this->iAnoUsu}-{$oDadosPerido->o114_mesfinal}-{$iUltimoDiaMes}";
     $sWherePlano        = " c61_instit in ({$this->getInstituicoes()}) AND c52_descrred = 'F'";
     $sWhereRP           = " e60_instit in ({$this->getInstituicoes()}) ";
     $sWhereReceita      = " o70_instit in ({$this->getInstituicoes()}) ";
     $sWhereDespesa      = " o58_instit in ({$this->getInstituicoes()}) ";
     $aLinhasUsamPlano   = array(21, 24, 29, 30, 33, 34, 35, 36, 42, 43, 45, 50, 51, 54, 55,56, 57);
     $aLinhasUsamRP      = array(48, 49);
     $aLinhasUsamDespesa = array(27, 28, 48, 49);
     
     /**
      * Poocessa os restos a pagar
      */
     $oDaoEmpResto       = db_utils::getDao("empresto");
     $sSqlRestos         = $oDaoEmpResto->sql_rp_novo($this->iAnoUsu , $sWhereRP, $sDataInicial, $sDataFinal, '');
     $rsDespesaRestos    = db_query($sSqlRestos);
     $iTotalLinhasRestos = pg_num_rows($rsDespesaRestos);
     /**
      * processa o balancete de verificação
      * 
      */
     $rsPlano = db_planocontassaldo_matriz($this->iAnoUsu, 
                                           $sDataInicial, 
                                           $sDataFinal, 
                                           false,
                                           $sWherePlano,
                                           '',
                                           'true',
                                           'false');
                                           
     $iTotalLinhasPlano = pg_num_rows($rsPlano);
     $rsReceita        = db_receitasaldo(11, 1, 3, true, 
                                        $sWhereReceita, 
                                        $this->iAnoUsu, 
                                        $sDataInicial, 
                                        $sDataFinal);
                                        
     $iTotalLinhasReceita = pg_num_rows($rsReceita);
     
     $rsDespesa = db_dotacaosaldo(7,3,2, true, $sWhereDespesa, $this->iAnoUsu, $sDataInicial, $sDataFinal);
     $iTotalLinhasDespesa = pg_num_rows($rsDespesa);
     
     $rsDespesaFuncao           = db_dotacaosaldo(3,3,2, true, $sWhereDespesa, $this->iAnoUsu, $sDataInicial, $sDataFinal);
     $iTotalLinhasDespesaFUncao = pg_num_rows($rsDespesaFuncao);
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
         $oLinha->valor         = 0;
         foreach($aValoresColunasLinhas as $oValor) {
           $oLinha->{$oValor->colunas[0]->o115_nomecoluna} += $oValor->colunas[0]->o117_valor;
         }
         $aParametros        = $aLinhasRelatorio[$iLinha]->getParametros($this->iAnoUsu, $this->getInstituicoes());
         if ($aLinhasRelatorio[$iLinha]->desdobraLinha() && $aParametros->desdobrarlinha) {
           $oLinha->desdobrar  = true;          
         }
         /**
          * verificamos se a a conta cadastrada existe no balancete, e somamos o valor encontrado na linha
          */
         if ($iLinha >= 1 && $iLinha <= 18) {
           /**
            * linhas que usam o balancete de receita.
            */
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
           
           $aLinhasSaldoInicial   = array(33, 34, 35, 36);
           $aLinhasSaldoFinal     = array(21, 24, 42, 45, 54, 55, 56, 57);
           $aLinhasSaldoCredito   = array(29, 30);
           $aLinhasSaldoDebito    = array(50, 51);
           $aLinhasVerificarSaldo = array();
           $aLinhasBanco          = array(33, 34, 35, 36, 54, 56, 55, 57);
           /**
            * linhas que usam o balancete de verificação
            */
           $sQuadro = "A";
           if ($iLinha > 39) {
             $sQuadro = "P";
           }
           
           for ($i = 0; $i < $iTotalLinhasPlano; $i++) {
    
             $oResultado = db_utils::fieldsMemory($rsPlano, $i);  
             $oParametro = $aParametros;
             if (in_array($iLinha, $aLinhasVerificarSaldo)) {
             
               $oResultado->saldo_anterior = $this->verificaValor($oResultado->saldo_anterior, 
                                                                  $oResultado->sinal_anterior,
                                                                  $sQuadro);
                                                                   
               $oResultado->saldo_final = $this->verificaValor($oResultado->saldo_final, 
                                                               $oResultado->sinal_final,
                                                               $sQuadro);
             } else if (in_array($iLinha, $aLinhasBanco)) {
               
               if ($oResultado->sinal_final == "C") {
                 $oResultado->saldo_final *= -1;
               }
               if ($oResultado->sinal_anterior == "C") {
                 $oResultado->saldo_anterior *= -1;
               }
             }
             foreach ($oParametro->contas as $oConta) {

               $debug = false;
               $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 3);
               if ($oVerificacao->match) {

                 if ($oVerificacao->exclusao) {
              
                   $oResultado->saldo_anterior         *= -1;  
                   $oResultado->saldo_anterior_credito *= -1;  
                   $oResultado->saldo_anterior_debito  *= -1;  
                   $oResultado->saldo_final            *= -1;  
                 }
                 $nValorConta = 0;
                 if (in_array($iLinha, $aLinhasSaldoInicial)) {
                   
                   $nValorConta    = $oResultado->saldo_anterior; 
                   $oLinha->valor += $oResultado->saldo_anterior;
                 } 
                 
                 if (in_array($iLinha, $aLinhasSaldoCredito)) {
                   
                   $nValorConta    = $oResultado->saldo_anterior_credito;
                   $oLinha->valor += $oResultado->saldo_anterior_credito;
                 }
                 
                 if (in_array($iLinha, $aLinhasSaldoDebito)) {
                   
                   $nValorConta    = $oResultado->saldo_anterior_debito;
                   $oLinha->valor += $oResultado->saldo_anterior_debito;
                 }
                 if (in_array($iLinha, $aLinhasSaldoFinal)) {
                   
                   $nValorConta    = $oResultado->saldo_final;
                   $oLinha->valor += $oResultado->saldo_final;
                 }
                 if ($oLinha->desdobrar) {
                   
                   $oContaDesdobrada = new stdClass();
                   $oContaDesdobrada->descricao             = $oResultado->c60_descr; 
                   $oContaDesdobrada->valor                 = $nValorConta; 
                   $oLinha->contas[$oResultado->estrutural] = $oContaDesdobrada;
                 }
               }
             }
           }
         } else if (in_array($iLinha, $aLinhasUsamRP)) {
           
           for ($i = 0; $i < $iTotalLinhasRestos; $i++) {
    
             $oResultado = db_utils::fieldsMemory($rsDespesaRestos, $i);  
             $oParametro  = $aParametros;
             foreach ($oParametro->contas as $oConta) {
                
               $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 4);
               if ($oVerificacao->match) {
      
                 if ($oVerificacao->exclusao) {
              
                   $oResultado->vlranu         *= -1;  
                   $oResultado->vlrpag         *= -1;
                   $oResultado->vlrpagnproc    *= -1;    
                 }
                 $oLinha->valor += $oResultado->vlranu + $oResultado->vlrpag + $oResultado->vlrpagnproc;
               }
             }
           }
         } else if (in_array($iLinha, $aLinhasUsamDespesa)) {
           
           for ($i = 0; $i < $iTotalLinhasPlano; $i++) {
    
             $oResultado = db_utils::fieldsMemory($rsDespesa, $i);  
             $oParametro  = $aParametros;
             foreach ($oParametro->contas as $oConta) {
                
               $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 2);
               if ($oVerificacao->match) {
      
                 if ($oVerificacao->exclusao) {
              
                   $oResultado->empenhado_acumulado      *= -1;  
                   $oResultado->anulado_acumulado        *= -1;
                   $oResultado->liquidado_acumulado      *= -1;    
                   $oResultado->pago_acumulado           *= -1;    
                   $oResultado->atual_a_pagar            *= -1;    
                   $oResultado->atual_a_pagar_liquidado  *= -1;    
                 }
                 if ($iLinha == 27 || $iLinha == 28) {
                   $oLinha->valor += $oResultado->atual_a_pagar_liquidado + $oResultado->atual_a_pagar;
                 } 
               }
             }
           }
         }
       } 
       $aLinhas[$iLinha] = $oLinha;
     }
     
     $aLinhas[39]->subfuncao = array();
     for ($i = 0; $i < $iTotalLinhasDespesaFUncao; $i++) {

       $oResultado = db_utils::fieldsMemory($rsDespesaFuncao, $i);
       if ($oResultado->o52_descr == "") {
         continue; 
       }
       if (isset($aLinhas[39]->subfuncao[$oResultado->o58_funcao])) {
         
         $aLinhas[39]->subfuncao[$oResultado->o58_funcao]->valor += ($oResultado->empenhado - 
                                                                     $oResultado->anulado);
       } else {
         
         $oSubFuncao = new stdClass();
         $oSubFuncao->descricao  = $oResultado->o52_descr;
         $oSubFuncao->valor      =  ($oResultado->empenhado - $oResultado->anulado);   
         $aLinhas[39]->subfuncao[$oResultado->o58_funcao] = $oSubFuncao; 
       }
       $aLinhas[40]->valor += $aLinhas[39]->subfuncao[$oResultado->o58_funcao]->valor;
     }
     unset($aLinhasRelatorio);
     
     /**
      * caso o relatorio, for consolidado, temos que verificar se o totais das inerferencias ativas/passivas são iguais.
      * caso sejam, devemos inibir sua Impressao. 
      * 
      */
     $lInterferenciasConsolidadas = false;
     if ($this->isConsolidado()) {
       
       if ($aLinhas[21]->valor === $aLinhas[42]->valor) {

         $aLinhas[21]->valor          = 0;
         $aLinhas[22]->valor          = 0;
         $aLinhas[42]->valor          = 0;
         $aLinhas[43]->valor          = 0;
         $lInterferenciasConsolidadas = true;
       }
     }
     /**
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
             if (strpos(strtolower($sRetorno), "parse error") > 0) {
               
               $sMsg =  "Linha {$iLinha} com erro no cadastro da formula<br>{$oColuna->o116_formula}";
               throw new Exception($sMsg);
               
             }
           }
         }
       }
     }
     /**
      * Apos o calculo dos totalizadores, tiramos as linhas do relatorio
      * caso sejam, devemos inibir sua Impressao. 
      * 
      */
     if ($this->isConsolidado()) {
       
       if ($lInterferenciasConsolidadas) {

         unset($aLinhas[21]);
         unset($aLinhas[20]);
         unset($aLinhas[22]);
         unset($aLinhas[41]);
         unset($aLinhas[42]);
         unset($aLinhas[43]);
       }
     }
     return $aLinhas;    
  }
  
 /**
   * verifica se o saldo final está credito/debito
   *
   * @param float $nValor valor da conta
   * @param string $sSinal sinal "D" Débito "C" Credito
   * @param string $sQuadro qual quadro está sendo processado "A" Ativo "P" Passivo
   */
  public function verificaValor($nValor, $sSinal, $sQuadro) {

     if ($sQuadro == "A" and $sSinal == "C") {
        $nValor *= -1;
      } elseif ($sQuadro == "P" and $sSinal == "D") {
        $nValor *= -1;
      }
    return $nValor;
  }
  
  /**
   * define se o relatorio deve ser emitido consolidado.
   *
   * @param boolean $lConsolidado
   */
  public function setConsolidado ($lConsolidado) {
    $this->lConsolidado = $lConsolidado;
  }
  
  /**
   * verifica se relatorio é emitido de forma consolidada.
   *
   * @return boolean
   */
  public function isConsolidado() {
    return $this->lConsolidado;
  }
}