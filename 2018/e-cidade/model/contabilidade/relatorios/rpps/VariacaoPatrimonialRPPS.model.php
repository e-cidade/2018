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


/**
 * Classe para gera��o dos dados do balanco Financeiro do RPPS
 */
class VariacaoPatrimonialRPPS extends RelatoriosLegaisBase {

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
    unset($aInstituicoesRetorno);
  }

  /**
   * return array Linhas com os dados processados do relatorio
   */
  public function getDados() {

    $aLinhasUtilizamBalanceteReceita     = array(3, 4, 5, 6);
    $aLinhasUtilizamBalanceteDespesa     = array(26, 27, 28, 29);
    $aLinhasUtilizamBalanceteVerificacao = array();
    
    $sWhereReceita    = " o70_instit in({$this->getInstituicoes()})";
    $sWhereDespesa    = " o58_instit in({$this->getInstituicoes()})";
    $sWherePlano      = " c61_instit in({$this->getInstituicoes()})";

    $rsReceita = db_receitasaldo(11, 1, 3, true, $sWhereReceita, $this->iAnoUsu,
                                 $this->getDataInicial()->getDate(),
                                 $this->getDataFinal()->getDate()
                                );



    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
                                                  $this->iAnoUsu,
                                                  $this->getDataInicial()->getDate(),
                                                  $this->getDataFinal()->getDate()
                                                );


    $rsBalanceteVerificacao =  db_planocontassaldo_matriz($this->iAnoUsu,
                                                          $this->getDataInicial()->getDate(),
                                                          $this->getDataFinal()->getDate(),
                                                          false,
                                                          $sWherePlano,
                                                          '',
                                                          'true',
                                                          'false'
                                                        );
    
    $aLinhas = $this->getLinhasRelatorio();
    foreach ($aLinhas as $iLinha => $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                           $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $oValores) {
        foreach ($oValores->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }

      if (in_array($iLinha, $aLinhasUtilizamBalanceteReceita)) {

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '#saldo_arrecadado_acumulado';
        RelatoriosLegaisBase::calcularValorDaLinha($rsReceita,
                                                    $oLinha,
                                                    array($oColuna),
                                                    RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
                                                  );
        continue;
      }

      if (in_array($iLinha, $aLinhasUtilizamBalanceteDespesa)) {

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '#liquidado_acumulado';
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
                                                  $oLinha,
                                                  array($oColuna),
                                                  RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
                                                );
        continue;
      }

      $oColuna          = new stdClass();
      $oColuna->nome    = 'vlrexatual';
      $oColuna->formula = '#saldo_final';

      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacao,
                                                 $oLinha,
                                                 array($oColuna),
                                                 RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
                                                );
    }

    $this->processaTotalizadores($aLinhas);
    return $aLinhas;
  }
}