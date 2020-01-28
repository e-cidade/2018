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

class BalancoPatrimonialRPPS extends RelatoriosLegaisBase {

  /**
   * Instancia o Balanco Financeiro do RPPS
   * Apenas instituicoes que sao do tipo RPPS sao processadas neste relatorio;.
   * @param int $iAnoUsu Ano de Emissão
   * @param int $iCodigoRelatorio Código do Relatorio
   * @param int $iCodigoPeriodo Codigo do Periodo
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $oDaoDBConfig  = new cl_db_config();
    $sSqlInstituicoesRPPS = $oDaoDBConfig->sql_query_tipoinstit(null, "codigo", null, "db21_tipoinstit in (5,6)");
    $rsInstituicoesRPPS   = $oDaoDBConfig->sql_record($sSqlInstituicoesRPPS);
    if (!$rsInstituicoesRPPS || $oDaoDBConfig->numrows == 0) {
      throw new BusinessException('Não existem instituições RPPS cadastradas');
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

    $alinhasCompensadoAtivo   = array(35, 36, 37, 38, 39, 40);
    $alinhasCompensadoPassivo = array(64, 65, 66, 67, 68, 69);

    $sWherePlano            = " c61_instit in({$this->getInstituicoes()})";
    $rsBalanceteVerificacao = db_planocontassaldo_matriz($this->iAnoUsu,
                                                         $this->getDataInicial()->getDate(),
                                                         $this->getDataFinal()->getDate(),
                                                         false,
                                                         $sWherePlano,
                                                         '',
                                                         'true',
                                                         'false'
                                                        );

    db_query("drop table work_pl");
    $aLinhas = $this->getLinhasRelatorio();

    $oRelatorioVariacoesPatrimoniais = new VariacaoPatrimonialRPPS($this->iAnoUsu, 136, $this->iCodigoPeriodo);
    $aLinhasVariacao                 = $oRelatorioVariacoesPatrimoniais->getDados();

    $oLinhaSuperavitPatrimonial = $aLinhasVariacao[41];
    $oLinhaDeficitPatrimonial   = $aLinhasVariacao[22];
    
    foreach ($aLinhas as $iLinha => $oLinha) {

      if ($iLinha == 62 && $oLinhaDeficitPatrimonial->vlrexatual > 0) {
        $oLinha->vlrexatual = $oLinhaDeficitPatrimonial->vlrexatual * -1;
      }

      if ($iLinha == 62 && $oLinhaSuperavitPatrimonial->vlrexatual > 0) {
        $oLinha->vlrexatual = $oLinhaSuperavitPatrimonial->vlrexatual;
      }

      if ($oLinha->totalizar) {
        continue;
      }

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, null,
                                                                           $this->iAnoUsu
                                                                          );
      foreach($aValoresColunasLinhas as $oValores) {
        foreach ($oValores->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }

      $sFormula = '#sinal_final =="C" ? #saldo_final * -1 : #saldo_final';
      if ($iLinha > 40) {
        $sFormula = '#sinal_final =="D" ? #saldo_final * -1 : #saldo_final';
      }
      
      $oColuna          = new stdClass();
      $oColuna->nome    = 'vlrexatual';

      $oColuna->formula = $sFormula;
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