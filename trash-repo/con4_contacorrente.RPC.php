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
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once "model/contabilidade/planoconta/ContaCorrente.model.php";
require_once "model/contabilidade/planoconta/ContaPlano.model.php";
db_app::import("configuracao.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("financeiro.*");
db_app::import("exceptions.*");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$iAnoSessao        = db_getsession("DB_anousu");
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch ($oParam->exec) {


  	/**
  	 * case para inclus�o de um novo detalhamento
  	 */
  	case 'incluirDetalhamento' :

      $oDaoContaCorrenteDetalhe = db_utils::getDao('contacorrentedetalhe');
      $oDaoVerificaDetalhe      = db_utils::getDao('contacorrentedetalhe');

      $iReduzido       = $oParam->iCodigoReduzido;
      $iContaCorrente  = $oParam->iContaCorrente;
      $iInstituicao    = db_getsession("DB_instit");
      $iAnoUsu         = db_getsession("DB_anousu");

      $iTipoReceita    = $oParam->iTipoReceita   ;
      $iConcarPeculiar = $oParam->iConcarPeculiar;
      $iContaBancaria  = $oParam->iContaBancaria ;
      $iEmpenho        = $oParam->iEmpenho       ;
      $iNome           = $oParam->iNome          ;
      $iOrgao          = $oParam->iOrgao         ;
      $iUnidade        = $oParam->iUnidade       ;
      $iAcordo         = $oParam->iAcordo        ;


      db_inicio_transacao();
      switch ($iContaCorrente) {

      	case 1:  //disponibilidade financeira

      		$sWhereVerificacao  = "     c19_contacorrente       = {$iContaCorrente}    ";
      		$sWhereVerificacao .= " and c19_orctiporec          = {$iTipoReceita}      ";
      		$sWhereVerificacao .= " and c19_instit              = {$iInstituicao}      ";
      		$sWhereVerificacao .= " and c19_concarpeculiar      = '{$iConcarPeculiar}' ";
      		$sWhereVerificacao .= " and c19_reduz               = {$iReduzido}         ";
      		$sWhereVerificacao .= " and c19_conplanoreduzanousu = {$iAnoUsu}           ";

      		$sSqlVerificaDetalhe  = $oDaoVerificaDetalhe->sql_query_file (null, "*", null, $sWhereVerificacao);
      		$rsVerificacao        = $oDaoVerificaDetalhe->sql_record($sSqlVerificaDetalhe);

      		if ($oDaoVerificaDetalhe->numrows > 0 ) {
      		  $sDescricaoContaCorrenteErro = "1 - Disponibilidade Financeira";
      		}

      		$oDaoContaCorrenteDetalhe->c19_contacorrente       = $iContaCorrente;
      		$oDaoContaCorrenteDetalhe->c19_orctiporec          = $iTipoReceita;
      		$oDaoContaCorrenteDetalhe->c19_instit              = $iInstituicao;
      		$oDaoContaCorrenteDetalhe->c19_concarpeculiar      = "'{$iConcarPeculiar}'";
      		$oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      		$oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnoUsu;


      	break;

      	case 2:  //domicilio bancario

      		$sWhereVerificacao  = "     c19_contacorrente       = {$iContaCorrente} ";
      		$sWhereVerificacao .= " and c19_instit              = {$iInstituicao}   ";
      		$sWhereVerificacao .= " and c19_reduz               = {$iReduzido}      ";
      		$sWhereVerificacao .= " and c19_conplanoreduzanousu = {$iAnoUsu}        ";
      		$sWhereVerificacao .= " and c19_contabancaria       = {$iContaBancaria} ";

      		$sSqlVerificaDetalhe = $oDaoVerificaDetalhe->sql_query_file (null, "*", null, $sWhereVerificacao);
      		$rsVerificacao       = $oDaoVerificaDetalhe->sql_record($sSqlVerificaDetalhe);

      		if ($oDaoVerificaDetalhe->numrows > 0 ) {
      		  $sDescricaoContaCorrenteErro = "2 - Domicilio Banc�rio";
      		}

      		$oDaoContaCorrenteDetalhe->c19_contacorrente       = $iContaCorrente ;
      		$oDaoContaCorrenteDetalhe->c19_instit              = $iInstituicao;
      		$oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnoUsu;
      		$oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      		$oDaoContaCorrenteDetalhe->c19_contabancaria       = $iContaBancaria;
      	break;

      	case 3: // credor fornecedor / devedor

      		$sWhereVerificacao  = "     c19_contacorrente       = {$iContaCorrente} ";
      		$sWhereVerificacao .= " and c19_instit              = {$iInstituicao}   ";
      		$sWhereVerificacao .= " and c19_reduz               = {$iReduzido}      ";
      		$sWhereVerificacao .= " and c19_conplanoreduzanousu = {$iAnoUsu}        ";
      		$sWhereVerificacao .= " and c19_numcgm              = {$iNome}          ";

      		$sSqlVerificaDetalhe = $oDaoVerificaDetalhe->sql_query_file (null, "*", null, $sWhereVerificacao);
      		$rsVerificacao       = $oDaoVerificaDetalhe->sql_record($sSqlVerificaDetalhe);

          if ($oDaoVerificaDetalhe->numrows > 0 ) {
      		  $sDescricaoContaCorrenteErro = "3 - Credor / Fornecedor / Devedor";
      		}

      		$oDaoContaCorrenteDetalhe->c19_contacorrente       = $iContaCorrente;
      		$oDaoContaCorrenteDetalhe->c19_instit              = $iInstituicao;
      		$oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      		$oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnoUsu;
      		$oDaoContaCorrenteDetalhe->c19_numcgm              = $iNome;
      	break;

      	case 19:  // ADIANTAMENTOS - CONCESS�O

      		$sWhereVerificacao  = "     c19_contacorrente       = {$iContaCorrente} ";
      		$sWhereVerificacao .= " and c19_instit              = {$iInstituicao}   ";
      		$sWhereVerificacao .= " and c19_reduz               = {$iReduzido}      ";
      		$sWhereVerificacao .= " and c19_conplanoreduzanousu = {$iAnoUsu}        ";
      		$sWhereVerificacao .= " and c19_numcgm              = {$iNome}          ";
      		$sWhereVerificacao .= " and c19_numemp              = {$iEmpenho}       ";
      		$sWhereVerificacao .= " and c19_orcunidadeanousu    = {$iAnoUsu}        ";
      		$sWhereVerificacao .= " and c19_orcunidadeorgao     = {$iOrgao}         ";
      		$sWhereVerificacao .= " and c19_orcunidadeunidade   = {$iUnidade}       ";
      		$sWhereVerificacao .= " and c19_orcorgaoanousu      = {$iAnoUsu}        ";
      		$sWhereVerificacao .= " and c19_orcorgaoorgao       = {$iOrgao}         ";

      		$sSqlVerificaDetalhe = $oDaoVerificaDetalhe->sql_query_file (null, "*", null, $sWhereVerificacao);
      		$rsVerificacao       = $oDaoVerificaDetalhe->sql_record($sSqlVerificaDetalhe);

      		if ($oDaoVerificaDetalhe->numrows > 0 ) {
      		  $sDescricaoContaCorrenteErro = "19 - Adiantamentos - Concess�o";
      		}

      		$oDaoContaCorrenteDetalhe->c19_contacorrente       = $iContaCorrente;
      		$oDaoContaCorrenteDetalhe->c19_instit              = $iInstituicao;
      		$oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      		$oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnoUsu;
      		$oDaoContaCorrenteDetalhe->c19_numcgm              = $iNome;
      		$oDaoContaCorrenteDetalhe->c19_numemp              = $iEmpenho;
      		$oDaoContaCorrenteDetalhe->c19_orcunidadeanousu    = $iAnoUsu;
      		$oDaoContaCorrenteDetalhe->c19_orcunidadeorgao     = $iOrgao;
      		$oDaoContaCorrenteDetalhe->c19_orcunidadeunidade   = $iUnidade;
      		$oDaoContaCorrenteDetalhe->c19_orcorgaoanousu      = $iAnoUsu;
      		$oDaoContaCorrenteDetalhe->c19_orcorgaoorgao       = $iOrgao;
      	break;

      	case 25: //CONTRATOS

      		$sWhereVerificacao  = "     c19_contacorrente       = {$iContaCorrente} ";
      		$sWhereVerificacao .= " and c19_instit              = {$iInstituicao}   ";
      		$sWhereVerificacao .= " and c19_reduz               = {$iReduzido}      ";
      		$sWhereVerificacao .= " and c19_conplanoreduzanousu = {$iAnoUsu}        ";
      		$sWhereVerificacao .= " and c19_numcgm              = {$iNome}          ";
      		$sWhereVerificacao .= " and c19_acordo              = {$iAcordo}        ";

      		$sSqlVerificaDetalhe = $oDaoVerificaDetalhe->sql_query_file (null, "*", null, $sWhereVerificacao);
      		$rsVerificacao       = $oDaoVerificaDetalhe->sql_record($sSqlVerificaDetalhe);

      		if ($oDaoVerificaDetalhe->numrows > 0 ) {
      			$sDescricaoContaCorrenteErro = "25 - Contratos";
      		}

      		$oDaoContaCorrenteDetalhe->c19_contacorrente       = $iContaCorrente;
      		$oDaoContaCorrenteDetalhe->c19_instit              = $iInstituicao;
      		$oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      		$oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnoUsu;
      		$oDaoContaCorrenteDetalhe->c19_acordo              = $iAcordo;
      		$oDaoContaCorrenteDetalhe->c19_numcgm              = $iNome;

      	break;

      }

      if ($oDaoVerificaDetalhe->numrows > 0) {

        $sMsgErro  = "Conta corrente [$sDescricaoContaCorrenteErro] com detalhamento selecionado j� ";
        $sMsgErro .= "inclu�do no sistema.\n\nProcedimento abortado.";
        throw new BusinessException($sMsgErro);
      }

      $oDaoContaCorrenteDetalhe->incluir(null);
      if ($oDaoContaCorrenteDetalhe->erro_status == 0 || $oDaoContaCorrenteDetalhe->erro_status == '0') {

      	throw new DBException('ERRO - [ 1 ] - Incluindo Detalhe de Conta Corrente : ' . $oDaoContaCorrenteDetalhe->erro_msg);
      }

      $oRetorno->message = urlencode("Detalhe inclu�do com sucesso.");
      db_fim_transacao(false);

  	break;


  	/*
  	 * case para buscar detalhamento de uma contacorrentedetalhe
  	 * especifica (tela de viewdetalhes)
  	 */
  	case "getDetalheContaCorrente" :

  		$sEmpenho                 = "";
  		$sContaBancaria           = "";
  		$oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");

  		$sCamposDetalhes  = "*";

  		$sWhereDetalhes   = "c19_sequencial = {$oParam->iDetalhe} ";

  		$sSqlDetalhes     = $oDaoContaCorrenteDetalhe->sql_query_viewDetalhes(null, $sCamposDetalhes, null, $sWhereDetalhes);
  		$rsDetalhes       = $oDaoContaCorrenteDetalhe->sql_record($sSqlDetalhes);
  		$oDetalhes        = db_utils::fieldsMemory($rsDetalhes, 0);

  		if ($oDetalhes->c19_numemp != '') {
  			$sEmpenho = $oDetalhes->e60_codemp . " / " . $oDetalhes->e60_anousu;
  		}
  		if ($oDetalhes->c19_contabancaria != '') {

  			$sBco         = $oDetalhes->db89_db_bancos;
  			$sAg          = $oDetalhes->db89_codagencia;
  			$iDigitoAg    = $oDetalhes->db89_digito;
  			$sConta       = $oDetalhes->db83_conta;
  			$iDigitoConta = $oDetalhes->db83_dvconta;

  			$sContaBancaria  = "<strong>Banco:</strong> {$sBco}   ";
  			$sContaBancaria .= "<strong>Ag�ncia:</strong> {$sAg} - {$iDigitoAg}    ";
  			$sContaBancaria .= "<strong>Conta:</strong> {$sConta} - {$iDigitoConta} ";
  		}

  		$oValoresDetalhes = new stdClass();
  		$oValoresDetalhes->c19_sequencial          = $oDetalhes->c19_sequencial                                        ;
      $oValoresDetalhes->c17_descricao           = $oDetalhes->c17_contacorrente . " - " . $oDetalhes->c17_descricao ;
      $oValoresDetalhes->o15_descr               = $oDetalhes->o15_descr                                             ;
      $oValoresDetalhes->nomeinst                = $oDetalhes->nomeinst                ;
      $oValoresDetalhes->c58_descr               = $oDetalhes->c58_descr               ;
      $oValoresDetalhes->c19_contabancaria       = urlencode($sContaBancaria)          ;
      $oValoresDetalhes->c19_reduz               = $oDetalhes->c19_reduz               ;
      $oValoresDetalhes->c19_numemp              = $sEmpenho                           ;
      $oValoresDetalhes->z01_nome                = $oDetalhes->z01_nome                ;
      $oValoresDetalhes->c19_orcunidadeanousu    = $oDetalhes->c19_orcunidadeanousu    ;
      $oValoresDetalhes->c19_orcunidadeorgao     = urlencode($oDetalhes->o40_descr);//$oDetalhes->c19_orcunidadeorgao     ;
      $oValoresDetalhes->c19_orcunidadeunidade   = urlencode($oDetalhes->o41_descr);//$oDetalhes->c19_orcunidadeunidade   ;
      $oValoresDetalhes->c19_orcorgaoanousu      = $oDetalhes->c19_orcorgaoanousu      ;
      $oValoresDetalhes->c19_orcorgaoorgao       = urlencode($oDetalhes->o40_descr);//$oDetalhes->c19_orcorgaoorgao       ;
      $oValoresDetalhes->c19_conplanoreduzanousu = $oDetalhes->c19_conplanoreduzanousu ;
      $oValoresDetalhes->c19_acordo              = $oDetalhes->c19_acordo              ;

  		$oRetorno->aDados = $oValoresDetalhes;

  		break;

  	/*
  	 * case para reprocessamento de contas correntes
  	 *
  	 */
  	case "reprocessarContaCorrente" :


			/*
			 * definimos as variaveis que ser�o utilizadas em filtros e processamento;
			 */
  		$iCodigoContaCorrente = $oParam->iCodigoContaCorrente;
  		$iMes                 = $oParam->iMes;
  		$dtInicial            = "{$iAnoSessao}-{$iMes}-01";
  		$dtFinal              = "{$iAnoSessao}-{$iMes}-".cal_days_in_month(CAL_GREGORIAN, $iMes, $iAnoSessao);
  		$aDetalhamentos       = $oParam->aContaCorrente;
  		$oDaoConlancamVal     = db_utils::getDao("conlancamval");
  		$sCodigosDetalhes     = "";

  		if ( count($aDetalhamentos) > 0 ) {

  			$aCodigosDetalhes = array();
  			foreach ($aDetalhamentos as $iIndice => $oValores) {
  				$aCodigosDetalhes[] = $oValores->sCodigo;
  			}
  			$sCodigosDetalhes = implode(", ", $aCodigosDetalhes);
  		}
  		$sCamposConlancamVal  = "c19_sequencial, ";
  		$sCamposConlancamVal .= "c69_data,       ";
  		$sCamposConlancamVal .= "c69_sequen,     ";
  		$sCamposConlancamVal .= "c19_reduz,      ";
  		$sCamposConlancamVal .= "c69_codlan      ";

  		$sWhereConlancamVal   = " c19_contacorrente = {$iCodigoContaCorrente} ";
  		/*
  		 * se houver lista de contas detalhes, incluimos no in do where
  		 */
  		if ($sCodigosDetalhes != "") {

  			$sWhereConlancamVal .= " and  c19_sequencial in ($sCodigosDetalhes) ";
  		}
  		/*
  		 * verificamos se foi selecionado um intervalo de datas para incluir no where
  		 */
			$sWhereConlancamVal .= " and c69_data between '{$dtInicial}' and '{$dtFinal}' ";

  		$sSqlConlancamVal = $oDaoConlancamVal->sql_query_contacorrentedetalhe(null, $sCamposConlancamVal, null, $sWhereConlancamVal);
  		$rsConlancamVal   = $oDaoConlancamVal->sql_record($sSqlConlancamVal);
  		$iTotalRegistros  = $oDaoConlancamVal->numrows;

  		if ($iTotalRegistros == 0) {
  		  throw new BusinessException('N�o foram encontrados v�nculos entre a conta corrente e seus lan�amentos cont�beis.');
  		}


 			db_inicio_transacao();

 			/**
 			 * Exclu�mos o saldo no per�odo informado para conseguirmos reprocessar
 			 */
 			for ($iExcluirRegistro = 0; $iExcluirRegistro < $iTotalRegistros; $iExcluirRegistro++) {

 			  $oDadosConLancamVal      = db_utils::fieldsMemory($rsConlancamVal, $iExcluirRegistro);
 			  $oDaoContaCorrenteSaldo = db_utils::getDao('contacorrentesaldo');
 			  $sWhereExcluirSaldo     = "     c29_anousu = {$iAnoSessao}";
 			  $sWhereExcluirSaldo    .= " and c29_mesusu = {$iMes}";
 			  $sWhereExcluirSaldo    .= " and c29_contacorrentedetalhe = {$oDadosConLancamVal->c19_sequencial}";
 			  $oDaoContaCorrenteSaldo->excluir(null, $sWhereExcluirSaldo);
 			  if ($oDaoContaCorrenteSaldo->erro_status == '0') {
 			    throw new DBException('ERRO [1] - Excluir saldo conta corrente.');
 			  }
 			  unset($oDaoContaCorrenteSaldo);
 			}

       /**
        * Atualizamos os valores
        */
 			for ($iRegistro = 0; $iRegistro < $iTotalRegistros; $iRegistro++) {

 			  $oDadosConLancamVal      = db_utils::fieldsMemory($rsConlancamVal, $iRegistro)	;
 			  $iSequencialDetalhe      = $oDadosConLancamVal->c19_sequencial;
 			  $iSequencialConlancam    = $oDadosConLancamVal->c69_codlan;
 			  $iSequencialConlancamVal = $oDadosConLancamVal->c69_sequen;
 			  $dtConLancamVal          = $oDadosConLancamVal->c69_data;
 			  $iReduzido               = $oDadosConLancamVal->c19_reduz;

 			  $oLancamentoAuxiliar     = new LancamentoAuxiliarContaCorrente($iSequencialConlancam);

 			  $oContaDetalhamento = ContaCorrenteFactory::getInstance($iSequencialConlancamVal,
 			                                                     $iReduzido,
 			  		                                               $oLancamentoAuxiliar);

 			  if ($oContaDetalhamento) {
 			  	$oContaDetalhamento->reprocessarSaldo($iSequencialDetalhe, $dtConLancamVal);
 			  }
 			}

 			db_fim_transacao(false);

 			$oRetorno->message = "Reprocessamento de conta corrente realizado com sucesso.";



  	break;


  	/*
  	 * case para implanta��o de saldo em conta corrente
  	 * sera implantado com mesusu 0 e para o ano de 2013
  	 */
  	case "implantarSaldoContaCorrente" :


  		 $iCodigoReduzido  = $oParam->iCodigoReduzido;
  		 $sTipoImplantacao = $oParam->sTipoImplantacao;
  		 $sColunaImplantar = "c29_credito";
  		 $sColunaZerar     = "c29_debito";
  		 $iAnoUsu          = db_getsession("DB_anousu");
  		 $aValores         = $oParam->aValores;

  		 if ($sTipoImplantacao == 'debito') {

  		 	 $sColunaImplantar = "c29_debito";
  		 	 $sColunaZerar = "c29_credito";
  		 }

  		 db_inicio_transacao();
  		 
  		 /**
        * Remove os registros existentes na contacorrentesaldo para o ano atual e mes 0
        */
		   $oDaoContaCorrenteSaldo = new cl_contacorrentesaldo();
		   $sWhereExcluir          = "c29_anousu = {$iAnoUsu} and c29_mesusu = 0";
  		 $oDaoContaCorrenteSaldo->excluir(null, $sWhereExcluir);
  		 
  		 if ( $oDaoContaCorrenteSaldo->erro_status == "0" ) {
  		   throw new DBException("ERRO [ 1 ] - Excluindo Registros - " . $oDaoContaCorrenteSaldo->erro_msg);
  		 }
  		 
  		 foreach ($aValores as $iIndiceValores => $oValores){

         if ($oValores->nValor <= 0) {
           continue;
         }

  		   /*
  		    * modifica��o para reajustar valores, basicamente devemos verificar se
  		    * ja foi feita implanta��o na contacorrentesaldo pelo detalhe em quest�o
  		    * se retornar registro, para o detalhe, ano e mes = 0, significa que devemos altera-lo
  		    * se n�o retornar significa que � a primeira vez que est� sendo implantado e logo devemos incluir registro na
  		    * contacorrentesaldo
  		    */
  		   $sWhereImplantacao  = "     c29_contacorrentedetalhe = {$oValores->iSequencial} " ;
  		   $sWhereImplantacao .= " and c29_anousu = {$iAnoUsu} ";
  		   $sWhereImplantacao .= " and c29_mesusu = 0 ";
  		   $sSqlImplantcao     = $oDaoContaCorrenteSaldo->sql_query_file(null, "*", null, $sWhereImplantacao);
  		   $rsImplantacao      = $oDaoContaCorrenteSaldo->sql_record($sSqlImplantcao);

  		   $oDaoContaCorrenteSaldo->c29_contacorrentedetalhe = $oValores->iSequencial;
  		   $oDaoContaCorrenteSaldo->c29_anousu               = $iAnoUsu;
  		   $oDaoContaCorrenteSaldo->c29_mesusu               = '0';
  		   $oDaoContaCorrenteSaldo->$sColunaImplantar        = $oValores->nValor;
  		   $oDaoContaCorrenteSaldo->$sColunaZerar            = '0';

  		   // se retornou registros devemos alterar
  		   if ($oDaoContaCorrenteSaldo->numrows > 0) {

  		   	 $oValoresInplantados = db_utils::fieldsMemory($rsImplantacao, 0);

  		   	$oDaoContaCorrenteSaldo->c29_sequencial = $oValoresInplantados->c29_sequencial;
  		   	$oDaoContaCorrenteSaldo->alterar($oDaoContaCorrenteSaldo->c29_sequencial);

  		   } else { // senao, incluimos

  		     $oDaoContaCorrenteSaldo->incluir(null);
  		   }


  		   if ( $oDaoContaCorrenteSaldo->erro_status == "0") {
  		   	 throw new DBException("ERRO [ 2 ] - Atualizando Registros - " . $oDaoContaCorrenteSaldo->erro_msg);
  		   }

  		 }

  		 db_fim_transacao(false);

  		 $oRetorno->message = "Procedimento realizado com sucesso.";

  	break;


  	/*
  	 * case para trazer o detalhamento da conta a partir do reduzido
  	 * para a rotina de implanta��o de saldo:
  	 *
  	 * Contabilidade > Procedimento > Utilitarios da Contabilidade > Conta Corrente > Implanta��o de Saldo
  	 *
  	 */
  	case "getDetalhamento" :

      /*
       * Tabelas envolvidas:
       *
       * conplanoreduz
       * conplanoexe
       * contacorrentedetalhe
       */
  		$iAnoUsu         = db_getsession("DB_anousu");
  		$iReduzido       = $oParam->iCodigoReduzido;
  		$aDetalhesContas = array();

  		$sWhereConplanoExe   = "     c62_anousu = {$iAnoUsu}   ";
  		$sWhereConplanoExe  .= " and c62_reduz  = {$iReduzido} ";

  		$sCamposConplanoExe  = " c62_vlrcre , ";
  		$sCamposConplanoExe .= " c62_vlrdeb   ";

  		// primeiro buscamos o saldo a credito ou a debito do reduzido na conplanoexe
  		$oDaoConplanoExe     = db_utils::getDao("conplanoexe");
  		$sSqlConplanoExe     = $oDaoConplanoExe->sql_query(null, null, $sCamposConplanoExe , null, $sWhereConplanoExe);
  		$rsConplanoExe       = $oDaoConplanoExe->sql_record($sSqlConplanoExe);
  		$oValoresConplanoExe = db_utils::fieldsMemory($rsConplanoExe, 0);
  		if ($oValoresConplanoExe->c62_vlrcre > 0 && $oValoresConplanoExe->c62_vlrdeb > 0) {

  			$sErroMessage = "ERRO [ 1 ] - Conta com valores a d�bito e a cr�dito.\n A conta deve ser corrigida a d�bito ou a cr�dito";
  			throw new BusinessException($sErroMessage);
  		}
  		if ($oValoresConplanoExe->c62_vlrcre <= 0 && $oValoresConplanoExe->c62_vlrdeb <= 0) {

  			$sErroMessage = "ERRO [ 2 ] - Conta com valores a d�bito e a cr�dito zerado.\n A conta deve ser corrigida a d�bito ou a cr�dito";
  			throw new BusinessException($sErroMessage);
  		}
  		/*
  		 * buscamos os detalhes das contas pelo estrutural
  		 * deve retornar as contas que ainda n�o foram incluidas na
  		 * contacorrentesaldo com o mes 0 - implantacao
  		 */
  		$oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");

  		$sWhereDetalhes  = "c19_reduz = {$iReduzido} ";

  		$sCamposDetalhe  = "distinct           ";
  		$sCamposDetalhe .= "c19_sequencial,    ";
  		$sCamposDetalhe .= "c17_sequencial,    ";
  		$sCamposDetalhe .= "c17_contacorrente, ";
  		$sCamposDetalhe .= "c17_descricao,     ";
  		$sCamposDetalhe .= "o15_descr,         ";  // CC1 - Disponibilidade Financeira
  		$sCamposDetalhe .= "db89_db_bancos,    ";
  		$sCamposDetalhe .= "db89_codagencia,   ";
  		$sCamposDetalhe .= "db89_digito,       ";
  		$sCamposDetalhe .= "db83_conta,        ";
  		$sCamposDetalhe .= "db83_dvconta,      "; // CC2 - Domicilio Bancario
  		$sCamposDetalhe .= "z01_nome,          ";
  		$sCamposDetalhe .= "c58_descr          ";

  		$sSqlDetalhe     = $oDaoContaCorrenteDetalhe->sql_query_fileAtributos(null, $sCamposDetalhe, null, $sWhereDetalhes);
  		$rsDetalhes      = $oDaoContaCorrenteDetalhe->sql_record($sSqlDetalhe);

  		if ($oDaoContaCorrenteDetalhe->numrows > 0) {

 		  	$sDescricao = "";

  		  for ($iDetalhe = 0; $iDetalhe < $oDaoContaCorrenteDetalhe->numrows; $iDetalhe++) {

  		  	$oValores                         = db_utils::fieldsMemory($rsDetalhes, $iDetalhe);
  		  	$oDadosDetalhes                   = new stdClass();
  		  	$oDadosDetalhes->nValorImplantado = 0;

  		  	switch ($oValores->c17_sequencial) {

  		  		case 1: //  1 | CC 1    | DISPONIBILIDADE FINANCEIRA
  		  			$sDescricao = $oValores->o15_descr . " - " . $oValores->c58_descr;
  		  		break;

  		  		case 2: //  2 | CC 2    | DOMIC�LIO BANC�RIO

  		  			$sBco         = $oValores->db89_db_bancos;
  		  			$sAg          = $oValores->db89_codagencia;
  		  			$iDigitoAg    = $oValores->db89_digito;
  		  			$sConta       = $oValores->db83_conta;
  		  			$iDigitoConta = $oValores->db83_dvconta;

  		  			$sDescricao  = "<strong>Banco:</strong> {$sBco}  ";
  		  			$sDescricao .= "<strong>Ag�ncia:</strong> {$sAg} - {$iDigitoAg}  ";
  		  			$sDescricao .= "<strong>Conta:</strong> {$sConta} - {$iDigitoConta} ";
  		  		break;

  		  		case 3: //  3 | CC 3    | CREDOR/FORNECEDOR/DEVEDOR
  		  			$sDescricao = $oValores->z01_nome;
  		  		break;

  		  		case 19: // 19 | CC 19   | ADIANTAMENTOS - CONCESS�O
  		  			$sDescricao = $oValores->z01_nome;
  		  		break;

  		  		case 25: // 25 | CC 25   | CONTRATOS
  		  			$sDescricao = $oValores->z01_nome;
  		  		break;

  		  	}
  		  	// setamoso sequencial dos detalhes e a descri��o que ir� na grid
  		  	$oDadosDetalhes->iCodigo          = $oValores->c19_sequencial;
  		  	$oDadosDetalhes->sConta           = urlencode($sDescricao);

  		  	/*
  		  	 * sql para verificar se existe valor ja implantado
  		  	 */
  		  	$oDaoContaCorrenteSaldo = db_utils::getDao("contacorrentesaldo");

  		  	$sWhereImplantacao    = "     c29_contacorrentedetalhe = {$oValores->c19_sequencial} " ;
  		    $sWhereImplantacao   .= " and c29_anousu = {$iAnoUsu} ";
  		    $sWhereImplantacao   .= " and c29_mesusu = 0 ";
  		  	$sSqlValorImplantado  = $oDaoContaCorrenteSaldo->sql_query_file (null,"*", null, $sWhereImplantacao);
  		  	$rsImplantado         = $oDaoContaCorrenteSaldo->sql_record($sSqlValorImplantado);

  		  	// se existir valor ja implantado, setamos eles no objeto que ir� retornar
  		  	if ($oDaoContaCorrenteSaldo->numrows > 0) {

  		  		$oValorInplantado = db_utils::fieldsMemory($rsImplantado, 0);

  		  		$oDadosDetalhes->nValorImplantado = $oValorInplantado->c29_credito;

  		  		if ($oValorInplantado->c29_credito == 0 || $oValorInplantado->c29_credito == null) {
  		  			$oDadosDetalhes->nValorImplantado = $oValorInplantado->c29_debito;
  		  		}
  		  	}

          // adicionamos o objeto com os atributos que ir�o para tela em array
  		  	$aDetalhesContas[] = $oDadosDetalhes;
  		  }
  		  $oRetorno->iCodigoDescricao        = $oValores->c17_sequencial;
  		  $oRetorno->sDescricaoContaCorrente = $oValores->c17_contacorrente . " - " . urlencode($oValores->c17_descricao);

  		} else {

        $oPlanoContaPCASP = new ContaPlanoPCASP(null,
                                                db_getsession('DB_anousu'),
                                                $iReduzido,
                                                db_getsession("DB_instit"));

        if (!$oPlanoContaPCASP->getContaCorrente() instanceof ContaCorrente) {

          $sDescricaoConta = $oPlanoContaPCASP->getDescricao();
          $sMsgErro  = "A conta {$iReduzido} - {$sDescricaoConta} n�o est� vinculada a nenhuma conta ";
          $sMsgErro .= "corrente.\n\nVerifique o cadastro.";
          throw new BusinessException(urlencode($sMsgErro));
        }

        $sDescricaoContaCorrente  = "{$oPlanoContaPCASP->getContaCorrente()->getContaCorrente()} - ";
        $sDescricaoContaCorrente .= $oPlanoContaPCASP->getDescricao();

        $oRetorno->iCodigoDescricao        = $oPlanoContaPCASP->getContaCorrente()->getCodigo();
        $oRetorno->sDescricaoContaCorrente = urlencode($sDescricaoContaCorrente);
  		}


  		$oRetorno->nSaldoCredito           = trim($oValoresConplanoExe->c62_vlrcre);
  		$oRetorno->nSaldoDebito            = trim($oValoresConplanoExe->c62_vlrdeb);
  		$oRetorno->aDados                  = $aDetalhesContas;

  	break;

    case "salvarVinculo":

      db_inicio_transacao();
      $oContaCorrente = new ContaCorrente($oParam->iCodigoContaCorrente);
      $oContaCorrente->vincularContasContabeisPorEstrutural($oParam->sEstrutural);
      $oRetorno->message = urlencode("Contas v�nculadas com sucesso.");
      db_fim_transacao(false);
      break;

    case "getContasVinculadas":

      $oContaCorrente   = new ContaCorrente($oParam->iCodigoContaCorrente);
      $aContasContabeis = $oContaCorrente->getContasContabeis();
      $aContasRetorno   = array();
      foreach ($aContasContabeis as $iIndice => $oContaPCASP) {

        $oStdContaRetorno               = new stdClass();
        $oStdContaRetorno->iCodigoConta = $oContaPCASP->getCodigoConta();
        $oStdContaRetorno->sEstrutural  = $oContaPCASP->getEstrutural();
        $oStdContaRetorno->sDescricao   = urlencode($oContaPCASP->getDescricao());
        $aContasRetorno[] = $oStdContaRetorno;
      }

      $oRetorno->aContas = $aContasRetorno;
      break;

    case "excluirVinculo":

      db_inicio_transacao();
      $oContaCorrente = new ContaCorrente($oParam->iCodigoContaCorrente);
      foreach ($oParam->aContas as $iCodigoConta) {
        $oContaCorrente->excluirVinculoComConta($iCodigoConta);
      }
      db_fim_transacao(false);
      $oRetorno->message = urlencode("V�nculo exclu�do com sucesso.");
      break;
  }

} catch (Exception $eException) {

  $oRetorno->message = urlencode($eException->getMessage());
  $oRetorno->status  = 2;
  db_fim_transacao(true);

} catch (BusinessException $eBException) {

  $oRetorno->message = urlencode($eBException->getMessage());
  $oRetorno->status  = 2;
  db_fim_transacao(true);

} catch (DBException $eDBException) {

  $oRetorno->message = urlencode($eDBException->getMessage());
  $oRetorno->status  = 2;
  db_fim_transacao(true);
} catch (ParameterException $eParameterException) {

  $oRetorno->message = urlencode($eParameterException->getMessage());
  $oRetorno->status  = 2;
  db_fim_transacao(true);
}

echo $oJson->encode($oRetorno);
?>