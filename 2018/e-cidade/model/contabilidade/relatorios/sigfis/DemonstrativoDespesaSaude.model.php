<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

class DemonstrativoDespesaSaude extends RelatoriosLegaisBase {
  
  private $sInstituicao;
  private $iPeriodo;
  
  public function getInstituicao() {
    return $this->sInstituicao;
  }
  
  public function setInstituicao($sInstituicao) {
    $this->sInstituicao = $sInstituicao;
  }
    
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }
  
  public function getDados(){
    
    /**
     * buscamos os parametros relacionados ao periodo
     * data inicial e final
     * 
     */
    $oDaoPeriodo   = db_utils::getDao('periodo');  
    $sSqlPeriodo   = $oDaoPeriodo->sql_query($this->iCodigoPeriodo);
    $rsPeriodo     = $oDaoPeriodo->sql_record($sSqlPeriodo);
    $oDadosPeriodo = db_utils::fieldsMemory($rsPeriodo, 0);
    $dDataInicial  = $this->iAnoUsu . "-" . $oDadosPeriodo->o114_mesinicial . "-" . $oDadosPeriodo->o114_diainicial;
    $dDataFinal    = $this->iAnoUsu . "-" . $oDadosPeriodo->o114_mesfinal   . "-" . $oDadosPeriodo->o114_diafinal;
    
    
    
    $aLinhasRelatorio = $this->oRelatorioLegal->getLinhasCompleto();
    $iTotalLinhas     = count($aLinhasRelatorio);
    $aLinhas          = array();
    $sWhereDespesa    = "o58_instit in ({$this->getInstituicao()})";
    $rsDespesa        = db_dotacaosaldo(8, 2, 3, true, $sWhereDespesa, $this->iAnoUsu,
                                       $dDataInicial, $dDataFinal);
    
    $iLinhasDespesa   = pg_num_rows($rsDespesa);
    for ($iLinha = 1; $iLinha <= $iTotalLinhas; $iLinha++ ) {

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
        $oLinha->valor = 0;
        foreach($aValoresColunasLinhas as $oValor) {

          foreach ($oValor->colunas as $iIndice => $oValorColuna) {
            $oLinha->{$oValorColuna->o115_nomecoluna} += $oValorColuna->o117_valor;
          }
        }
        
        $oParametros   = $aLinhasRelatorio[$iLinha]->getParametros($this->iAnoUsu, $this->getInstituicoes());
        if ($aLinhasRelatorio[$iLinha]->desdobraLinha() && $oParametros->desdobrarlinha) {
          $oLinha->desdobrar  = true;          
        }
      
        for ($iRowDespesa = 0; $iRowDespesa < $iLinhasDespesa; $iRowDespesa++) {

          $oDadoDespesa  = db_utils::fieldsMemory($rsDespesa, $iRowDespesa);
          foreach ($oParametros->contas as $oConta) {

            $oDadosDespesaVerificar = clone $oDadoDespesa; 
            $oVerificacao = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametros->orcamento, $oDadoDespesa, 2);
            if ($oVerificacao->match) {
            
              /*
               * Verifica se a conta é conta de exclusão e diminui o valor.
               */
              if ($oVerificacao->exclusao) {
                
                $oDadosDespesaVerificar->dot_ini   *= -1;
                $oDadosDespesaVerificar->atual     *= -1;
                $oDadosDespesaVerificar->empenhado *= -1;
                $oDadosDespesaVerificar->anulado   *= -1;
                $oDadosDespesaVerificar->liquidado *= -1;
                $oDadosDespesaVerificar->pago      *= -1;
              }
              
              $oLinha->previni   += $oDadosDespesaVerificar->dot_ini;
              $oLinha->prevatu   += ($oDadosDespesaVerificar->dot_ini + $oDadosDespesaVerificar->suplementado_acumulado) - $oDadosDespesaVerificar->reduzido_acumulado;
              $oLinha->empenhado += $oDadosDespesaVerificar->empenhado - $oDadosDespesaVerificar->anulado;
              $oLinha->liquidado += $oDadosDespesaVerificar->liquidado;
              $oLinha->pago      += $oDadosDespesaVerificar->pago;
            }
          }
        }
      }
      $aLinhas[$iLinha] = $oLinha;
    }
    /*
     * Executa um parse das formulas cadastradas pelo usuario.
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
    return $aLinhas;
  }
}