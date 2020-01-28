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

class AnexoSumarioGeralReceita extends RelatoriosLegaisBase {
  
  /**
   * Origem/Fase
   * 1 - Orçamento
   * 2 - Empenhado
   * 3 - Liquidado
   * 4 - Pago
   */
  protected $iOrigemFase;
  
  /**
   * Método Construtor
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }

  /**
   * Setter de iOrigemFase
   * @param integer $iOrigemFase
   */
  public function setOrigemFase($iOrigemFase) {
    $this->iOrigemFase = $iOrigemFase;    
  }
  
  /**
   * Método getDados
   * Retorna os dados de uma dotação organizados por despesa capital e despesa corrente
   *
   * @return array $aRetorno
   */
  public function getDados() {

    $oDaoPeriodo      = db_utils::getDao("periodo");
    $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
    $rsPeriodo        = db_query($sSqlDadosPeriodo);
    $oDadosPerido     = db_utils::fieldsMemory($rsPeriodo, 0); 
    $sDataInicial     = "{$this->iAnoUsu}-01-01";
    $iUltimoDiaMes    = cal_days_in_month(CAL_GREGORIAN, $oDadosPerido->o114_mesfinal, $this->iAnoUsu);
    $sDataFinal       = "{$this->iAnoUsu}-{$oDadosPerido->o114_mesfinal}-{$iUltimoDiaMes}";
    $sWhereDespesa    = " o58_instit in ({$this->getInstituicoes()}) ";
    $sWhereReceita    = " o70_instit in ({$this->getInstituicoes()}) ";
    $sWherePlano      = " c61_instit in ({$this->getInstituicoes()})";
    $rsDespesa        = db_dotacaosaldo(3, 3, 2, true, 
                                        $sWhereDespesa,
                                        $this->iAnoUsu,
                                        $sDataInicial,
                                        $sDataFinal);
    $rsReceita        = db_receitasaldo(11, 1, 3, true, 
                                        $sWhereReceita, 
                                        $this->iAnoUsu, 
                                        $sDataInicial, 
                                        $sDataFinal);

    $rsPlano          = db_planocontassaldo_matriz($this->iAnoUsu, 
                                                   $sDataInicial, 
                                                   $sDataFinal, 
                                                   false,
                                                   $sWherePlano,
                                                   '',
                                                   'true',
                                                   'false');                                        
    $iTotalLinhasReceita = pg_num_rows($rsReceita);
    $iTotalLinhasDespesa = pg_num_rows($rsDespesa);
    $iTotalLinhasPlano   = pg_num_rows($rsPlano);
    $aLinhasRelatorio    = $this->oRelatorioLegal->getLinhasCompleto();
    $aFuncoes            = array();
    /*
     * Processa os dados das Funcoes
     */
    for ($i = 0; $i < $iTotalLinhasDespesa; $i++) {

      $oDespesa = db_utils::fieldsMemory($rsDespesa, $i);
      
      if ($oDespesa->o52_descr == "") {
        continue; 
      }
      /**
       * Valida o Tipo de Origem/Fase selecionado pelo usuário
       */
      $nValorTotal = 0;
      switch ($this->iOrigemFase) {
        
        /*
         * Orçamento
         */
        case 1:
          $nValorTotal += $oDespesa->dot_ini;
        break;
        
        /*
         * Empenhado
         */
        case 2:
          $nValorTotal += ($oDespesa->empenhado_acumulado - $oDespesa->anulado_acumulado);
        break;
        
        /*
         * Liquidado
         */
        case 3:
          $nValorTotal += $oDespesa->liquidado_acumulado;
        break;
        
        /*
         * Pago
         */
        case 4:
          $nValorTotal += $oDespesa->pago_acumulado;
        break;
        
      }
      
      /**
       * Verifica se a função corrente já está no array de $aFuncoes
       * Caso esteja, é somado o resultado dos valores empenhado - anulado na propriedade TOTAL
       * 
       * Do contrário é criado uma nova posição no array ($aFuncoes)
       */
      if (isset($aFuncoes[$oDespesa->o58_funcao])) {
        $aFuncoes[$oDespesa->total]->total += $nValorTotal;
      } else {
         
        $oFuncao = new stdClass();
        $oFuncao->descricao              = $oDespesa->o52_descr;
        $oFuncao->total                  = $nValorTotal;   
        $aFuncoes[$oDespesa->o58_funcao] = $oFuncao; 
      }
    }
    
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
      if ($iLinha == 22) {
        $oLinha->funcoes = $aFuncoes;
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
                                                                              
        foreach($aValoresColunasLinhas as $oValor) {
          
          if ($iLinha == 22) {
          
            if (isset($oLinha->funcoes[$oValor->colunas[0]->o117_valor])) {
              $oLinha->funcoes[$oValor->colunas[0]->o117_valor]->total += $oValor->colunas[1]->o117_valor;
            }
          } else {
            $oLinha->{$oValor->colunas[0]->o115_nomecoluna} += $oValor->colunas[0]->o117_valor;
          }
        }
        
        $oParametro = $aLinhasRelatorio[$iLinha]->getParametros($this->iAnoUsu, $this->getInstituicoes());
        if ($aLinhasRelatorio[$iLinha]->desdobraLinha() && $oParametro->desdobrarlinha) {
          $oLinha->desdobrar  = true;          
        }
        /**
         *  verificamos se a a conta cadastrada existe no balancete, e somamos o valor encontrado na linha
         */
        if ($iLinha >= 1 && $iLinha <= 19) {
         
          /**
           * linhas que usam o balancete de receita.
           */
          for ($i = 0; $i < $iTotalLinhasReceita; $i++) {
    
            $oReceita    = db_utils::fieldsMemory($rsReceita, $i);
            $nCampoValor = "saldo_arrecadado_acumulado";
            
            if ($this->iOrigemFase == 1) {
              $nCampoValor = "saldo_inicial";
            }
            $oLinhaCalcula  = clone $oReceita;
            foreach ($oParametro->contas as $oConta) {
              
              $oVerificacao  = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oReceita, 1);
              if ($oVerificacao->match) {
    
                if ($oVerificacao->exclusao) {
                  $oLinhaCalcula->{$nCampoValor}  *= -1;  
                }
                $oLinha->total += $oLinhaCalcula->{$nCampoValor};
              }
            }
            unset ($oLinhaCalcula);
          }
        }
        
        /**
         * Processa os dados do plano de contas 
         */
        if ($this->iOrigemFase != 1 && ($iLinha == 20 || $iLinha == 23)) {
          
          for ($i = 0; $i < $iTotalLinhasPlano; $i++) {
    
            $oResultado     = db_utils::fieldsMemory($rsPlano, $i);  
            $oLinhaCalcula  = clone $oResultado;
            foreach ($oParametro->contas as $oConta) {
              
              $oVerificacao  = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 3);
              if ($oVerificacao->match) {
    
                if ($oVerificacao->exclusao) {
                  $oLinhaCalcula->saldo_final  *= -1;  
                }
                $oLinha->total += $oLinhaCalcula->saldo_final;
              }
            }
            unset ($oLinhaCalcula);
          }
        }
      }  
      $aLinhas[$iLinha] = $oLinha;
    }
    
    /**
     * Percorre o array de funções somando o valor total de cada função
     */
    foreach ($aLinhas[22]->funcoes as $oFuncao) {
      $aLinhas[22]->total += $oFuncao->total;
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
    
//    echo ("<pre>".print_r($aLinhas, 1)."</pre>");
    return $aLinhas;    
  }
}
?>