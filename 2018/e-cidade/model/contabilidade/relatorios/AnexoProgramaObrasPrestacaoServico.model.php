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

class AnexoProgramaObrasPrestacaoServico extends RelatoriosLegaisBase {
  
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
    
    $aLinhas                  = $this->oRelatorioLegal->getLinhasCompleto();
    $aParametrosColunaServico = $aLinhas[1]->getParametros($this->iAnoUsu)->contas;
    $aParametrosColunaObras   = $aLinhas[2]->getParametros($this->iAnoUsu)->contas;
    $rsDespesa = db_dotacaosaldo(7, 2, 2, true, $sWhereDespesa, $this->iAnoUsu, $sDataInicial, $sDataFinal);
    $aRetorno  = array();
    
    /**
     * Percorre o ResultSet organizando os dados dentro do array $aRetorno
     */
    for ($iRow = 0; $iRow <= pg_num_rows($rsDespesa); $iRow++) {
      
      $oDespesa    = db_utils::fieldsMemory($rsDespesa, $iRow);
      $nValorTotal = 0;
      
      /**
       * Valida o Tipo de Origem/Fase selecionado pelo usuário
       */
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
       * Verifica se o órgão atual já está setado dentro do array de retorno ($aRetorno)
       */
      if (!isset($aRetorno[$oDespesa->o58_orgao])) {

        $oOrgao           = new stdClass();
        $oOrgao->codigo   = $oDespesa->o58_orgao;
        $oOrgao->descr    = $oDespesa->o40_descr;
        $oOrgao->servicos = 0;
        $oOrgao->obras    = 0;
        $oOrgao->total    = 0;
        $oOrgao->unidades = array();
        
        $aRetorno[$oDespesa->o58_orgao] = $oOrgao;
      } else {
        $oOrgao = $aRetorno[$oDespesa->o58_orgao];
      }

      /**
       * Verifica se a unidade atual já está setada dentro do array de retorno ($aRetorno) na posição $oDespesa->o58_orgao
       */
      if (!isset($aRetorno[$oDespesa->o58_orgao]->unidades[$oDespesa->o58_unidade])) {
        
        $oUnidade             = new stdClass();
        $oUnidade->codigo     = $oDespesa->o58_unidade;
        $oUnidade->descr      = $oDespesa->o41_descr;
        $oUnidade->servicos   = 0;
        $oUnidade->obras      = 0;
        $oUnidade->total      = 0;
        $oUnidade->atividades = array();
        
        $aRetorno[$oDespesa->o58_orgao]->unidades[$oDespesa->o58_unidade] = $oUnidade;
      } else {
        $oUnidade = $aRetorno[$oDespesa->o58_orgao]->unidades[$oDespesa->o58_unidade];
      }

      /**
       * Verifica se a atividade atual já está setada dentro do array de retorno na posição do órgao e unidade correntes
       */
      if (!isset($aRetorno[$oDespesa->o58_orgao]->unidades[$oDespesa->o58_unidade]->programas[$oDespesa->o58_programa])) {
        
        $oPrograma           = new stdClass();
        $oPrograma->codigo   = $oDespesa->o58_programa;
        $oPrograma->descr    = $oDespesa->o54_descr;
        $oPrograma->servicos = 0;
        $oPrograma->obras    = 0;
        $oPrograma->total    = 0;
        
        $aRetorno[$oDespesa->o58_orgao]->unidades[$oDespesa->o58_unidade]->programas[$oDespesa->o58_programa] = $oPrograma;
      } else {
        $oPrograma = $aRetorno[$oDespesa->o58_orgao]->unidades[$oDespesa->o58_unidade]->programas[$oDespesa->o58_programa];
      }

      foreach ($aParametrosColunaServico as $oConta) {

        if (substr($oConta->estrutural,0, $oConta->nivel) == substr($oDespesa->o58_elemento."00", 0, $oConta->nivel)) {
          
          $nValorSomar = $nValorTotal;
          if ($oConta->exclusao) {
            $nValorSomar *= -1;
          }
          $oOrgao->servicos    += $nValorSomar;
          $oUnidade->servicos  += $nValorSomar;
          $oPrograma->servicos += $nValorSomar;
          $oPrograma->total    += $nValorSomar;
          $oOrgao->total       += $nValorSomar;
          $oUnidade->total     += $nValorSomar;
        }
      }
      
      foreach ($aParametrosColunaObras as $oConta) {

        if (substr($oConta->estrutural,0, $oConta->nivel) == substr($oDespesa->o58_elemento."00", 0, $oConta->nivel)) {
          
          $nValorSomar = $nValorTotal;
          if ($oConta->exclusao) {
            $nValorSomar *= -1;
          }
          $oOrgao->obras    += $nValorSomar;
          $oUnidade->obras  += $nValorSomar;
          $oPrograma->obras += $nValorSomar;
          $oPrograma->total += $nValorSomar;
          $oOrgao->total    += $nValorSomar;
          $oUnidade->total  += $nValorSomar;
        }
      }
    }
    return $aRetorno;
  }
}
?>