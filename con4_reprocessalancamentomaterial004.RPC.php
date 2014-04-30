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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/BusinessException.php");
require_once("std/db_stdClass.php");
require_once("classes/materialestoque.model.php");

$oJson                  = new services_json();
$oParametro             = $oJson->decode(str_replace('\\', "" , $_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDocumentosPermitidos  = array(400, 401, 404);

try{

  db_inicio_transacao();

  switch ($oParametro->exec) {

    case "reprocessarLancamentoMaterial":

    	$oDaoConlancamVal = db_utils::getDao("conlancamval");
      $iTotalRequisicoes = count($oParametro->aRequisicoes);
      $aCodigoRequisicao = array();
      $oParametro->aRequisicoes;
      if (isset($oParametro->aRequisicoes) && $iTotalRequisicoes > 0) {

        foreach ($oParametro->aRequisicoes as $oStdDadosRequisicao) {
          $aCodigoRequisicao[] = $oStdDadosRequisicao->sCodigo;
        }
      }

      if (!in_array($oParametro->iCodigoDocumento, $aDocumentosPermitidos)) {
        throw new BusinessException("Documento {$oParametro->iCodigoDocumento} não habilitado para a rotina.");
      }

      if (empty($oParametro->dtInicial) && empty($oParametro->dtFinal) &&
          empty($oParametro->iCodigoLancamento) &&
          $iTotalRequisicoes == 0) {
        throw new BusinessException("Para não informar a data é preciso informar um código de lançamento contábil válido.");
      }

      $sRequisicoes   = implode(", ", $aCodigoRequisicao);
      $aWhereSaidas   = array();
      $aWhereSaidas[] = "conlancamdoc.c71_coddoc = {$oParametro->iCodigoDocumento}";
      if (!empty($oParametro->dtInicial) && !empty($oParametro->dtFinal)) {
        $aWhereSaidas[] = "conlancam.c70_data between '{$oParametro->dtInicial}' and '{$oParametro->dtFinal}'";
      }

      if (!empty($oParametro->iCodigoLancamento)) {
        $aWhereSaidas[] = "conlancam.c70_codlan = {$oParametro->iCodigoLancamento}";
      }


      switch ($oParametro->iCodigoDocumento) {

        /**
         * BAIXA DE ESTOQUE POR REQUISIÇÃO
         */
        case 400:

          $aWhereSaidas[] = "m80_codtipo = 17";
          if (!empty($sRequisicoes)) {
            $aWhereSaidas[] = "matrequiitem.m41_codmatrequi in ({$sRequisicoes})";
          }

          $sCamposLancamento     = "distinct conlancam.*, ";
          $sCamposLancamento    .= "         m82_codigo,      ";
          $sCamposLancamento    .= "         m60_codmater, conlancamdoc.c71_coddoc      ";
          $sWhereRequisicao      = implode(" and ", $aWhereSaidas);
          $oDaoConLancam         = db_utils::getDao('conlancam');
          $sSqlBuscaLancamentos  = $oDaoConLancam->sql_query_lancamento_requisicao_material(null,
                                                                                            $sCamposLancamento,
                                                                                            null,
                                                                                            $sWhereRequisicao);

          $rsBuscaLancamentos    = $oDaoConLancam->sql_record($sSqlBuscaLancamentos);
          if ($oDaoConLancam->numrows == '0') {
            throw new BusinessException("Nenhuma requisição encontrada para o filtro selecionado.");
          }


          break;

        /**
         * ESTORNO DE BAIXA ESTOQUE POR REQUISIÇÃO
         */
        case 401:

        	$aWhereSaidas[] = "m80_codtipo = 18";
        	if (!empty($sRequisicoes)) {
        		$aWhereSaidas[] = "matrequiitem.m41_codmatrequi in ({$sRequisicoes})";
        	}

        	$sCamposLancamento     = "distinct conlancam.*, ";
        	$sCamposLancamento    .= "         m82_codigo,      ";
        	$sCamposLancamento    .= "         m60_codmater, conlancamdoc.c71_coddoc      ";
        	$sWhereRequisicao      = implode(" and ", $aWhereSaidas);
        	$oDaoConLancam         = db_utils::getDao('conlancam');
        	$sSqlBuscaLancamentos  = $oDaoConLancam->sql_query_lancamento_estorno_saida_manual(null,
					                                                                                  $sCamposLancamento,
					                                                                                  null,
					                                                                                  $sWhereRequisicao);
        	$rsBuscaLancamentos    = $oDaoConLancam->sql_record($sSqlBuscaLancamentos);
        	if ($oDaoConLancam->numrows == 0) {
        		throw new BusinessException("Nenhuma requisição encontrada para o filtro selecionado.");
        	}


        	break;

        /**
         * Reprocessa os lançamentos da saída manual de materiais.
         */
        case 404:

          $aWhereSaidas[]       = "m80_codtipo = 5";
          $sWhereFinal          = implode(" and ", $aWhereSaidas);
          $sCamposSaida         = "distinct conlancam.*, m60_codmater, m82_codigo, conlancamdoc.c71_coddoc";
          $oDaoConLancam        = db_utils::getDao('conlancam');
          $sSqlBuscaSaidaManual = $oDaoConLancam->sql_query_lancamento_saida_manual(null, $sCamposSaida, null, $sWhereFinal);
          $rsBuscaLancamentos   = $oDaoConLancam->sql_record($sSqlBuscaSaidaManual);
          if ($oDaoConLancam->numrows == 0) {
            throw new BusinessException("Nenhuma saída manual encontrada para o filtro selecionado.");
          }

          break;
      }


      /**
       * Processamos as requisições de acordo com o documento selecionado
       */
      for ($iRowLancamento = 0; $iRowLancamento < $oDaoConLancam->numrows; $iRowLancamento++) {

        $oStdValorLancamento = db_utils::fieldsMemory($rsBuscaLancamentos, $iRowLancamento);


        /**
         * Descubro as contas crédito/débito de acordo com  a regra padrão para o documento.
        */
        $oMaterialEstoque    = new MaterialEstoque($oStdValorLancamento->m60_codmater);
        $oLancamentoAuxiliar = new LancamentoAuxiliarMovimentacaoEstoque();
        $oLancamentoAuxiliar->setMaterial($oMaterialEstoque);
        $oLancamentoAuxiliar->setValorTotal($oStdValorLancamento->c70_valor);

        if($oStdValorLancamento->c71_coddoc == 401){
          $oLancamentoAuxiliar->setSaida(false);
        }else{
          $oLancamentoAuxiliar->setSaida(true);
        }


        //  @todo - verificar se tem exceções de regra para ano da sessao
        $oEventoContabil = new EventoContabil($oStdValorLancamento->c71_coddoc, db_getsession("DB_anousu"));
        $aLancamentos    = $oEventoContabil->reprocessaLancamentos( $oStdValorLancamento->c70_codlan,
                                                                    $oLancamentoAuxiliar,
                                                                    $oStdValorLancamento->c70_data );


      }

      $sMsgRetorno  = "Procedimento executado com sucesso.\n\n";
      $sMsgRetorno .= "Foram atualizados {$oDaoConLancam->numrows} lançamentos contábeis.";
      $oRetorno->sMessage = urlencode($sMsgRetorno);
  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  $oRetorno->iStatus      = 2;
  $oRetorno->sMessage     = urlencode($eErro->getMessage());
  db_fim_transacao(true);
}
echo $oJson->encode($oRetorno);