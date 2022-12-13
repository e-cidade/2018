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

class AnexoXIVBalancoGeral extends RelatoriosLegaisBase  {
  
  
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
     $sWhere           = " c61_instit in ({$this->getInstituicoes()}) ";
     
     /**
      * processa o balancete de verificação
      */
     $rsPlano = db_planocontassaldo_matriz(db_getsession("DB_anousu"), 
                                           $sDataInicial, 
                                           $sDataFinal, 
                                           false,
                                           $sWhere,
                                           '',
                                           'true',
                                           'true');
     $iTotalLinhas = pg_num_rows($rsPlano);     
                                           
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
         $oLinha->valor         = 0;
         foreach($aValoresColunasLinhas as $oValor) {
           
           foreach ($oValor->colunas as $oColuna) {
           	
             $oLinha->{$oValor->colunas[0]->o115_nomecoluna} += $oColuna->o117_valor;
           }
           
         }
         $sQuadro = "A";
         if ($iLinha > 24) {
           $sQuadro = "P";
         }
         /**
          * verificamos se a a conta cadastrada existe no balancente, e somamos o valor encontrado na linha
          */
         for ($i = 0; $i < $iTotalLinhas; $i++) {
  
           $oResultado = db_utils::fieldsMemory($rsPlano, $i);
           $oResultado->saldo_final = $this->verificaValor($oResultado->saldo_final, 
                                                             $oResultado->sinal_final,
                                                             $sQuadro);  
           $oParametro  = $aParametros;
           foreach ($oParametro->contas as $oConta) {

             $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 3);
             if ($oVerificacao->match) {
                 
               if ($oVerificacao->exclusao) {
            
                 $oResultado->saldo_anterior *= -1;  
                 $oResultado->saldo_final    *= -1;  
               }
               $oLinha->valor += $oResultado->saldo_final;
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
}