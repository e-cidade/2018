<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

class BalancoOrcamentarioRPPS extends RelatoriosLegaisBase {

  /**
   * Instancia o Balanco Financeiro do RPPS
   * Apenas instituicoes que sao do tipo RPPS sao processadas neste relatorio;.
   * @param int $iAnoUsu Ano de Emiss�o
   * @param int $iCodigoRelatorio C�digo do Relatorio
   * @param int $iCodigoPeriodo Codigo do Periodo
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $oDaoDBConfig  = new cl_db_config();
    $sSqlInstituicoesRPPS = $oDaoDBConfig->sql_query_tipoinstit(null, "codigo", null, "db21_tipoinstit in (5,6)");
    $rsInstituicoesRPPS   = $oDaoDBConfig->sql_record($sSqlInstituicoesRPPS);
    if (!$rsInstituicoesRPPS || $oDaoDBConfig->numrows == 0) {
      throw new BusinessException('N�o existem institui��es RPPS cadastradas');
    }

    $aInstituicoesRetorno = db_utils::getCollectionByRecord($rsInstituicoesRPPS);
    $aInstituicoes        = array();
    foreach ($aInstituicoesRetorno as $oInstituicao) {
      $aInstituicoes[] = $oInstituicao->codigo;
    }
    $this->setInstituicoes(implode(",", $aInstituicoes));
    unset($aInstituicoes);
  }

  /**
   * Retorna as linhas processadas do relatorio
   * @return array lista de linhas processadas do relatorio
   */
  public function getDados() {

    $sWhereReceita = " o70_instit in ({$this->getInstituicoes()}) ";
    $sWhereDespesa = " o58_instit in ({$this->getInstituicoes()}) ";

    $aLinhasComReceita = array(2, 3, 4, 6, 7, 8, 10, 11, 12, 14, 15, 16);
    $aLinhasComDespesa = array(23, 24, 26, 27, 28, 31,33, 34, 38, 39, 41, 42, 43, 46, 48, 49);
    $rsBalanceteReceita = db_receitasaldo(11, 1, 3, true,
                                          $sWhereReceita,
                                          $this->iAnoUsu,
                                          $this->getDataInicial()->getDate(),
                                          $this->getDataFinal()->getDate()
                                         );

    db_query("drop table work_receita");
    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
                                          $this->iAnoUsu,
                                          $this->getDataInicial()->getDate(),
                                          $this->getDataFinal()->getDate());
    $aLinhas = $this->getLinhasRelatorio();
    foreach ($aLinhas as $iLinha => $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                           $this->iAnoUsu
                                                                          );
      foreach($aValoresColunasLinhas as $oValores) {
        foreach ($oValores->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }

      if (in_array($iLinha, $aLinhasComReceita)) {

        $oPrevisao          = new stdClass();
        $oPrevisao->nome    = 'previsao';
        $oPrevisao->formula = '#saldo_inicial';

        $oExecucao          = new stdClass();
        $oExecucao->nome    = 'execucao';
        $oExecucao->formula = '#saldo_arrecadado_acumulado';

        $oDiferenca          = new stdClass();
        $oDiferenca->nome    = 'diferenca';
        $oDiferenca->formula = "($oPrevisao->formula) - ($oExecucao->formula)";
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteReceita,
                                                   $oLinha,
                                                   array($oPrevisao, $oExecucao, $oDiferenca),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
                                                  );

      }

      if (in_array($iLinha, $aLinhasComDespesa)) {


        $sRegra = "#dot_ini > 0";
        if ($iLinha >= 38) {
          $sRegra = "#dot_ini == 0";
        }
        $oFixacao          = new stdClass();
        $oFixacao->nome    = 'fixacao';
        $oFixacao->formula = "({$sRegra} ? #dot_ini + #suplementado_acumulado - #reduzido_acumulado : 0)";
        $oExecucao          = new stdClass();
        $oExecucao->nome    = 'execucao';
        $oExecucao->formula = "({$sRegra} ? #liquidado_acumulado : 0)";

        $oDiferenca          = new stdClass();
        $oDiferenca->nome    = 'diferenca';
        $oDiferenca->formula = "($oFixacao->formula) - ($oExecucao->formula)";
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
                                                   $oLinha,
                                                   array($oFixacao, $oExecucao, $oDiferenca),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
                                                  );

      }
    }

    $this->processaTotalizadores($aLinhas);
    return $aLinhas;
  }
}