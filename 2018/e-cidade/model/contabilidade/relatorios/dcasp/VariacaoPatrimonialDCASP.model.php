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

require_once("model/contabilidade/relatorios/RelatoriosLegaisBase.model.php");

/**
 * Model para busca dos dados do relatório de Variação Patrimonial do DCASP
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package contabilidade
 * @subpackage relatorios
 * @version $Revision: 1.6 $
 */
class VariacaoPatrimonialDCASP extends RelatoriosLegaisBase {

  const CODIGO_RELATORIO = 132;

  /**
   * Busca os dados que serão impressos no relatório
   * @return array stdClass - Retorna um array contendo stdClass
   */
  public function getDados() {

    $oDataInicialAnterior = clone $this->getDataInicial();
    $oDataInicialAnterior->modificarIntervalo('-1 year');
    $oDataFinalAnterior   = clone$this->getDataFinal();
    $oDataFinalAnterior->modificarIntervalo('-1 year');

    $rsBalancete         = $this->getBalanceteDeVerificacao($this->iAnoUsu, $this->getDataInicial(), $this->getDataFinal());
    $rsBalanceteAnterior = $this->getBalanceteDeVerificacao($this->iAnoUsu - 1, $oDataInicialAnterior, $oDataFinalAnterior);

    $aLinhasRelatorio = $this->getLinhasRelatorio();

    foreach ($aLinhasRelatorio as $iLinha => $oStdLinha) {

      if ($oStdLinha->totalizar) {
        continue;
      }

      $aValoresColunasLinhas = $oStdLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                              $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $oStdValor) {
        
        foreach ($oStdValor->colunas as $oColuna) {
          $oStdLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }


      $oColuna          = new stdClass();
      $oColuna->nome    = 'vlrexatual';
      $oColuna->formula = '#saldo_final';
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalancete,
                                                 $oStdLinha,
                                                 array($oColuna),
                                                 RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO);
      $oColuna          = new stdClass();
      $oColuna->nome    = 'vlrexanter';
      $oColuna->formula = '#saldo_final';
      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteAnterior,
                                                 $oStdLinha,
                                                 array($oColuna),
                                                 RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO);

    }
 
    $this->processaTotalizadores($aLinhasRelatorio);
    return $aLinhasRelatorio;
  }

  /**
   * Retorna o resource do balancente de verificação
   * @param integer  $iAno
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   * @return bool|resource|string
   */
  private function getBalanceteDeVerificacao($iAno, DBDate $oDataInicial, DBDate $oDataFinal) {

    $sWherePlano            = "c61_instit in ({$this->getInstituicoes()})";
    $rsBalanceteVerificacao = db_planocontassaldo_matriz($iAno,
                                                         $oDataInicial->getDate(),
                                                         $oDataFinal->getDate(),
                                                         false,
                                                         $sWherePlano,
                                                         '',
                                                         'true',
                                                         'false'
                                                       );
    db_query("drop table work_pl");
    return $rsBalanceteVerificacao;
  }
}