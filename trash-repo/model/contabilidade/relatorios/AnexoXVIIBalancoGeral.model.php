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

require_once ("RelatoriosLegaisBase.model.php");

/**
 * classe para controle dos valores do Anexo XVII do balan�o Geral
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 * 
 */

class AnexoXVIIBalancoGeral extends RelatoriosLegaisBase  {
  
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
      * montamos as datas, e processamos o balancete de verifica��o
      */
     $oDaoPeriodo      = db_utils::getDao("periodo");
     $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
     $rsPeriodo        = db_query($sSqlDadosPeriodo);
     $oDadosPerido     = db_utils::fieldsMemory($rsPeriodo, 0); 
     $sDataInicial     = "{$this->iAnoUsu}-01-01";
     $iUltimoDiaMes    = cal_days_in_month(CAL_GREGORIAN, $oDadosPerido->o114_mesfinal, $this->iAnoUsu);
     $sDataFinal       = "{$this->iAnoUsu}-{$oDadosPerido->o114_mesfinal}-{$iUltimoDiaMes}";
     $sWherePlano      = " c61_instit in ({$this->getInstituicoes()}) ";
     /**
      * processa o balancete de verifica��o
      */
     $rsPlano = db_planocontassaldo_matriz($this->iAnoUsu, 
                                           $sDataInicial, 
                                           $sDataFinal, 
                                           false,
                                           $sWherePlano,
                                           '',
                                           'true',
                                           'true');
     $iTotalLinhasPlano = pg_num_rows($rsPlano);     
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
         
         $aParametros           = $aLinhasRelatorio[$iLinha]->getParametros($this->iAnoUsu, $this->getInstituicoes());
         foreach($aValoresColunasLinhas as $oValor) {
           foreach ($oValor->colunas as $oColuna) {
             $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
           }
         }
         
         /**
          * verificamos se a a conta cadastrada existe no balancete, e somamos o valor encontrado na linha
          */
         for ($i = 0; $i < $iTotalLinhasPlano; $i++) {
  
           $oResultado = db_utils::fieldsMemory($rsPlano, $i);  
           $oParametro  = $aParametros;
           foreach ($oParametro->contas as $oConta) {
              
             $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 3);
             if ($oVerificacao->match) {
    
               if ($oVerificacao->exclusao) {
            
                 $oResultado->saldo_anterior         *= -1;
                 $oResultado->saldo_anterior_debito  *= -1;  
                 $oResultado->saldo_anterior_credito *= -1;    
                 $oResultado->saldo_final            *= -1;  
               }
               
               $oLinha->sd_ex_ant += $oResultado->saldo_anterior;
               $oLinha->inscricao += $oResultado->saldo_anterior_credito;
               $oLinha->baixa     += $oResultado->saldo_anterior_debito;
               $oLinha->sd_ex_seg += $oResultado->saldo_final;
             }
           }
         }
       }  
       $aLinhas[$iLinha] = $oLinha;
     }
     
     unset($aLinhasRelatorio);
     
     /**
      * calcula os totalizadores do relat�rio, aplicando as formulas.
      */
     foreach ($aLinhas as $oLinha) {

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
}