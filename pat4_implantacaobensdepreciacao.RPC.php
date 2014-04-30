<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("model/patrimonio/Bem.model.php");
require_once("model/patrimonio/BemCedente.model.php");
require_once("model/patrimonio/BemClassificacao.model.php");
require_once("model/patrimonio/PlacaBem.model.php");
require_once("model/patrimonio/BemHistoricoMovimentacao.model.php");
require_once("model/patrimonio/BemDadosImovel.model.php");
require_once("model/patrimonio/BemDadosMaterial.model.php");
require_once("model/patrimonio/BemTipoAquisicao.php");
require_once("model/patrimonio/BensParametroPlaca.model.php");
require_once("model/patrimonio/BemTipoDepreciacao.php");
require_once("model/patrimonio/depreciacao/CalculoBem.model.php");
require_once("model/patrimonio/depreciacao/PlanilhaCalculo.model.php");
require_once("model/patrimonio/depreciacao/FormulaCalculo.model.php");
require_once("std/DBNumber.php");
require_once("model/CgmFactory.model.php");

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

switch ($oParam->exec) {

  case 'getBensClassificacao':

    $oDaoClaBens       = db_utils::getDao('clabens');
    $aClassificacaoBem = array();
    $sCamposBens       = " t64_class, t64_codcla, t64_descr, t52_descr, t52_bem, t52_ident, ";
    $sCamposBens      .= " t44_vidautil, t46_descricao, t46_sequencial ";

    $sWhereBens  = " t64_codcla BETWEEN {$oParam->t64_codcla_ini} ";
    $sWhereBens .= "                AND {$oParam->t64_codcla_fin} ";
    $sWhereBens .= "                AND t55_codbem is null ";
    $sWhereBens .= " AND t52_dtaqu <= t59_dataimplanatacaodepreciacao ";
    $sWhereBens .= " AND not exists (SELECT 1 from benshistoricocalculobem where t58_bens = t52_bem)";
    $sWhereBens .= " ORDER BY t64_class, t52_bem ";

    $sSqlBuscaBens = $oDaoClaBens->sql_query_itensporclassificacao(null, $sCamposBens, null, $sWhereBens);

    $rsBuscaBens = $oDaoClaBens->sql_record($sSqlBuscaBens);
    $iLinhasBens = $oDaoClaBens->numrows;

    if ($iLinhasBens > 0) {

      for ($iRow = 0; $iRow < $iLinhasBens; $iRow++ ) {

        $oDadoBem = db_utils::fieldsMemory($rsBuscaBens, $iRow);
        if (! isset($aClassificacaoBem[$oDadoBem->t64_codcla])) {

          $oClassificacao                           = new stdClass();
          $oClassificacao->codigo                   = $oDadoBem->t64_codcla;
          $oClassificacao->classificacao            = $oDadoBem->t64_class;
          $oClassificacao->descricao                = urlencode($oDadoBem->t64_descr);
          $oClassificacao->itens                    = array();
          $aClassificacaoBem[$oDadoBem->t64_codcla] = $oClassificacao;
        }
        $oDadoBem->t52_descr                               = urlencode($oDadoBem->t52_descr);
        $oDadoBem->t46_descricao                           = urlencode($oDadoBem->t46_descricao);
        $aClassificacaoBem[$oDadoBem->t64_codcla]->itens[] = $oDadoBem;
      }
      $oRetorno->aDados = $aClassificacaoBem;
    } else {

      $oRetorno->message = urlencode(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao.nao_ha_itens'));
      $oRetorno->status  = 2;
    }
    break;

  case 'processarImplementacao':
  	
    try {

      $oPlanilhaCalculo       = new PlanilhaCalculo();
      $lDepreciacaoHabilitado = $oPlanilhaCalculo->hasParametroDepreciacaoHabilitado();
      if (! $lDepreciacaoHabilitado) {
        throw new Exception(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao.parametro_nao_setado'));
      }
      $dtDataImplantacao = $oPlanilhaCalculo->getDataInicioDepreciacao();

      $sCodigosBensProcessamentoRetroativo = "";
      $sVirgula                            = "";
      $aDataDepreciacao                    = array();

      db_inicio_transacao();

      foreach ($oParam->aItens as $oItem) {

        $oBem = new Bem($oItem->iBem);
        $oBem->setTipoDepreciacao(new BemTipoDepreciacao($oParam->iTipoDepreciacao));
        $oBem->setVidaUtil($oParam->iVidaUtil);
        $oBem->setTipoAquisicao(new BemTipoAquisicao($oParam->iTipoAquisicao));

        /**
         * Calculos o valor residual do bem
         */
        $nValorResidual = 0;
        $nValorTotalBem = 0;
        if ($oBem->getValorAquisicao() > 0 && $oParam->nPercentualValorResidual) {

          $nValorResidual = round((($oBem->getValorAquisicao() * $oParam->nPercentualValorResidual) / 100), 2);
          $nValorTotalBem = $oBem->getValorAquisicao() - $nValorResidual;
          $oBem->setValorResidual($nValorResidual);

          $oBem->setValorAtual($oBem->getValorAquisicao());
          $oBem->setValorDepreciavel($oBem->getValorAquisicao() - $oBem->getValorResidual());
        } else {
          $oBem->setValorAtual(0);
        }
        $sCodigosBensProcessamentoRetroativo .= $sVirgula . $oBem->getCodigoBem();
        $sVirgula                             = ",";
        $oBem->salvar();
      }

      /**
       * Processa depreciação retroativa se o tipo da depreciação informardo for diferente de 4
       * 4 = Não processa depreciação
       */
      if ($oParam->iTipoDepreciacao != 4) {

        $oDaoBens = db_utils::getDao("bensdepreciacao");

        $sWhere                    = " t44_bens in ({$sCodigosBensProcessamentoRetroativo})";
        $sSqlPrimeiraDataAquisicao = $oDaoBens->sql_query_bem(null, "min(t52_dtaqu) as t52_dtaqu", null, $sWhere);
        $rsPrimeiraDataAquisicao   = $oDaoBens->sql_record($sSqlPrimeiraDataAquisicao);

        if ($oDaoBens->numrows == 0) {
          throw new Exception(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao.erro_inesperado'));
        }

        $dtDataAquisicaoBem = db_utils::fieldsMemory($rsPrimeiraDataAquisicao, 0)->t52_dtaqu;

        list($iAnoAquisicao, $iMesAquisicao, $iDiaAquisicao)       = explode("-", $dtDataAquisicaoBem);
        list($iAnoImplantacao, $iMesImplantacao, $iDiaImplantacao) = explode("-", $dtDataImplantacao);

        for ($iAno = $iAnoAquisicao; $iAno <= $iAnoImplantacao; $iAno++ ) {
        	
          for ($iMes = $iMesAquisicao; $iMes <= 12; $iMes++ ) {
          	
          	$oStdClass = new stdClass();
          	$oStdClass->iMes = $iMes;
          	$oStdClass->iAno = $iAno;
          	
          	$aDataDepreciacao[] = $oStdClass;         	
          	
            if (($iAno == $iAnoImplantacao) && ($iMes == $iMesImplantacao)) {
              break;
            }

            $oPlanilhaCalculo = new PlanilhaCalculo();
            $oPlanilhaCalculo->setMes($iMes);
            $oPlanilhaCalculo->setAno($iAno);
            $oPlanilhaCalculo->ativaProcessamentoRetroativo();
            $oPlanilhaCalculo->setCodigoBensProcessamentoRetroativo($sCodigosBensProcessamentoRetroativo);
            $oPlanilhaCalculo->setTipoProcessamento(1);
            $oPlanilhaCalculo->setTipoCalculo(1);
            $oPlanilhaCalculo->setUsuario(db_getsession("DB_id_usuario"));

            /*
             * Testo se o calculo nao retornou false pois podem nao existir bens cadastrados em um
             * determinado mes, deste modo o processo nao e abortado
             */
            if (! $oPlanilhaCalculo->processarCalculo()) {
              continue;
            }
            $oPlanilhaCalculo->salvar();
            $oRetorno->message = _M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao.processamento_executado');
          }
          $iMesAquisicao = 1;
        }
      }
      
      /**
       * Para cada ano e mês depreciado na implantação, lança na tabela bensdepreciacaolancamento
       */
      foreach ( $aDataDepreciacao  as $oDataDepreciacao ) {
      	
      	$oDaoBensDepreciacaoLancamento = db_utils::getDao("bensdepreciacaolancamento");
      	$oDaoBensDepreciacaoLancamento->t78_sequencial = null; 
      	$oDaoBensDepreciacaoLancamento->t78_usuario    = db_getsession('DB_id_usuario');
      	$oDaoBensDepreciacaoLancamento->t78_instit     = db_getsession('DB_instit');
      	$oDaoBensDepreciacaoLancamento->t78_mes        = $oDataDepreciacao->iMes;
      	$oDaoBensDepreciacaoLancamento->t78_ano        = $oDataDepreciacao->iAno;
      	$oDaoBensDepreciacaoLancamento->t78_data       = date('Y-m-d', db_getsession('DB_datausu'));
      	$oDaoBensDepreciacaoLancamento->t78_estornado  = 'false';
      	$oDaoBensDepreciacaoLancamento->incluir(null);     

      	if ( $oDaoBensDepreciacaoLancamento->erro_status == 0 ) {      		
      		throw Exception(_M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao.erro_tecnico'));
      	}
      }      

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()
          . "\n\n\n\nBem Numero:{$oItem->iBem}"));
    }

    break;

  case 'validarCalculosEfetuados':

    $oDaoBensHistoricoCalculo = db_utils::getDao("benshistoricocalculo");

    $sWhere  = "t57_ativo is true ";
    $sWhere .= " and t57_instituicao = " . db_getsession("DB_instit");

    $sSqlVerificaCalculoExistente = $oDaoBensHistoricoCalculo->sql_query_file(null,
                                                                             "t57_mes",
                                                                              "t57_sequencial desc limit 1",
                                                                              $sWhere);

    $rsVerificaCalculoExistente = $oDaoBensHistoricoCalculo->sql_record($sSqlVerificaCalculoExistente);
    if ($oDaoBensHistoricoCalculo->numrows > 0) {

      $oRetorno->status  = 2;
      $sMessage          = _M('patrimonial.patrimonio.pat4_implantacaobensdepreciacao.ja_existem_calculos');
      $oRetorno->message = urlencode($sMessage);

    }
    break;
}

echo $oJson->encode($oRetorno);