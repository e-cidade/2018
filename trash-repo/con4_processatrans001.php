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
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libcontabilidade.php");
require_once("classes/db_conlancam_classe.php");
require_once("classes/db_conlancamlr_classe.php");
require_once("classes/db_conlancamval_classe.php");
require_once("classes/db_conplanoreduz_classe.php");
require_once("classes/db_conlancamretif_classe.php");
require_once("model/contabilidade/GrupoContaOrcamento.model.php");
require_once("model/contabilidade/DocumentoContabil.model.php");
require_once("model/patrimonio/Bem.model.php");
require_once("model/CgmFactory.model.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
require_once("model/contabilidade/planoconta/SistemaConta.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php");
require_once("model/empenho/EmpenhoFinanceiro.model.php");
require_once("model/Dotacao.model.php");
require_once("model/configuracao/Instituicao.model.php");
require_once("model/empenho/EmpenhoFinanceiroItem.model.php");
require_once("model/MaterialCompras.model.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarContaCorrente.model.php");

db_app::import("patrimonio.*");
db_app::import("exceptions.*");
db_app::import("contabilidade.planoconta.*");


$clconlancam      = new cl_conlancam;
$cltranslan       = new cl_translan;
$clconlancamlr    = new cl_conlancamlr;
$clconlancamval   = new cl_conlancamval;
$clconplanoreduz  = new cl_conplanoreduz;
$clconlancamretif = new cl_conlancamretif;

$debug = false;

db_postmemory($HTTP_POST_VARS);

/**
 * Documentos referentes ao PCASP
 */
$aDocumentosPCASP = array(107,109,111,113,115,117,416,419,     // arrecada��o de receita - tipo 100
                          306,412,84,506,502,310,202,204,206, // liquidacao - tipo 20
                          410,304,308,500,504,82,             // empenho - tipo 10
                          108,110,112,114,116,118,417,418,    // estorno de arrecadacao - tipo 101
                          85,203,205,207,307,311,413,503,507, // estorno de liquida��o - tipo 21
                          411,501,505,83,305,309);            // estorno de empenho - tipo 11

$aTables = array("cornump",
								 "corrente",
								 "corcla",
								 "corplacaixa",
								 "cornumpdesconto",
								 "conlancam",
								 "conlancamval",
								 "conlancamcgm",
								 "conlancamcompl",
								 "conlancamrec",
								 "conlancampag",
								 "conlancamslip",
								 "conlancamdoc",
								 "conlancamcorgrupocorrente",
								 "conlancamcorrente",
								 "contacorrentedetalheconlancamval",
								 "conlancamconcarpeculiar",

								 "reciborecurso",
								 "conplanoreduz",
								 "empempenhocontrato",
								 "contacorrentesaldo",
								 "conplanoorcamentoanalitica",
								 "conplanoreduz",
								 "contacorrentedetalheconlancamval",
								 "contrans",
								 "contranslr",
								 "empempenho",
								 "orcsuplem",
								 "orcreceita",
								 "conlancamdoc",
								 "conhistdoc",
								 "conlancamemp",
								 "empelemento",
								 "conlancamele"

);

if (isset ($processar)) {

	//Desativa autovacuum para tabelas definidas no array
	foreach ($aTables as $sTabela) {

		$sSqlAjusteDesempenho = "alter table {$sTabela} set (autovacuum_enabled = false, toast.autovacuum_enabled = false)";
		$rsAjusteDesempenho   = db_query($sSqlAjusteDesempenho);
	}
	$_SESSION["DB_desativar_account"] = true;

	try {

	  db_inicio_transacao();

		$sqlerro = false;

		$sWhere = " 1=1 ";
	  /**
	   * Caso tenha filtrado pelo tipo do documento
	   */
	  if(isset($iTipoDoc) && !empty($iTipoDoc)) {
	    $sWhere .= " and c53_tipo = {$iTipoDoc} ";
	  }

		if (isset ($c53_coddoc) && $c53_coddoc != '') {
			$sWhere .= " and  c71_coddoc = {$c53_coddoc} ";
		}

		if ($dti_dia != '' && $dti_mes != '' && $dti_ano != '') {
			$sWhere .= " and  c70_data >= '$dti_ano-$dti_mes-$dti_dia' ";
		}

		if ($dtf_dia != '' && $dtf_mes != '' && $dtf_ano != '') {
			$sWhere .= " and  c70_data <= '$dtf_ano-$dtf_mes-$dtf_dia' ";
	  }

		if (isset ($e60_numemp) && $e60_numemp != '') {
			$sWhere .= " and e60_numemp = {$e60_numemp} ";
	  }

		if (isset ($qcodlan) && $qcodlan != '') {
			$sWhere .= " and c70_codlan = {$qcodlan} ";
	  }


		$sCampos = "c53_coddoc,c70_codlan,c70_valor,
	              c70_data,c70_anousu,
	              e60_numemp,e60_anousu,
	              c67_codele as e64_codele,
	              e60_codcom,c82_reduz,o70_codfon,o70_instit,
	              c74_anousu,c74_codrec as codrec";

	  $sql = $clconlancam->sql_query_trans(null, $sCampos, "", $sWhere);
		if ($debug == true) {

			echo "<br><br>".$sql;
			flush();
		}

		$result  = $clconlancam->sql_record($sql);
	  $numrows = $clconlancam->numrows;

		if ($debug == true) {
	    echo "<br><br>Total de Linhas: {$numrows}<br><br>";
			db_criatabela($result);
	  }

		for ($i = 0; $i < $numrows; $i ++) {

			db_fieldsmemory($result, $i);

	    if ($c53_coddoc == 7 ||
	        $c53_coddoc == 8 ||
	        $c53_coddoc == 10 ||
	        $c53_coddoc == 12 ||
	        $c53_coddoc == 51 ||
	        $c53_coddoc == 52 ||
	        $c53_coddoc == 53 ||
	        $c53_coddoc == 54 ||
	        $c53_coddoc == 55 ||
	        $c53_coddoc == 56 ||
	        $c53_coddoc == 57 ||
	        $c53_coddoc == 58 ||
	        $c53_coddoc == 59 ||
	        $c53_coddoc == 60 ||
	        $c53_coddoc == 61 ||
	        $c53_coddoc == 62 ||
	        $c53_coddoc == 63 ||
	        $c53_coddoc == 64 ||
	        $c53_coddoc == 65 ||
	        $c53_coddoc == 71 ||
	        $c53_coddoc == 72 ||
	        $c53_coddoc == 73) {

				$verinst = "select o46_instit
						           from conlancamsup
						                inner join orcsuplem on c79_codsup = o46_codsup
						           where c79_codlan = $c70_codlan";
				$resres = db_query($verinst);
	      if (pg_numrows($resres) > 0) {

					if (pg_result($resres, 0, 0) != db_getsession("DB_instit")) {
						continue;
					}
				}
			}

	    if (!empty ($codrec)) {

				if ($o70_instit != db_getsession("DB_instit")) {
	        continue;
	      }
			}

	    if ($e60_numemp != "" && $e60_numemp != null) {

				$sql = "select e60_instit from conlancamemp inner join empempenho on e60_numemp = c75_numemp where c75_codlan = $c70_codlan";
	      $result111 = db_query($sql);

	      if ($result111 != false && pg_numrows($result111) > 0) {

	        $institit = pg_result($result111, 0, 0);

					if ($institit != db_getsession("DB_instit")) {
	          continue;
	        }
				}
	    }

	    $result01 = $clconlancamval->sql_record($clconlancamval->sql_query_file(null, "*", "", "c69_codlan=$c70_codlan"));

	    if ($debug==true) {
	      db_criatabela($result01);
	    }

	    $numrowscon = $clconlancamval->numrows;

			if ($numrowscon > 0) {

				for ($rem = 0; $rem < $numrowscon; $rem ++) {

					db_fieldsmemory($result01, $rem);

					if (USE_PCASP) {

						/**
						 * Guardando os dados para as opera��es seguintes:
						 * 		Atualizar saldo da conta corrente
						 * 		Excluir v�nculo entre a conlancamval e a contacorrentedetalhe
						 */
						$oStdConlanCamVal = new stdClass();
						$oStdConlanCamVal->c69_sequen  = $c69_sequen;
						$oStdConlanCamVal->c69_anousu  = $c69_anousu;
						$oStdConlanCamVal->c69_codlan  = $c69_codlan;
						$oStdConlanCamVal->c69_codhist = $c69_codhist;
						$oStdConlanCamVal->c69_credito = $c69_credito;
						$oStdConlanCamVal->c69_debito  = $c69_debito;
						$oStdConlanCamVal->c69_valor   = $c69_valor;
						$oStdConlanCamVal->c69_data    = $c69_data;

						/**
						 * Atualizando saldo da conta corrente
						 */
						try {
							ContaCorrenteBase::atualizarSaldoContaCorrenteReprocessamento($oStdConlanCamVal);
						} catch (BusinessException $oException) {

							$sqlerro  = true;
							$erro_msg = $oException->getMessage();
							break;
						}

						/**
						 * Excluindo v�nculo entre a conlancamval e a contacorrentedetalhe
						 */
						$oDaoContaCorrenteDetalheConLancamVal = db_utils::getDao("contacorrentedetalheconlancamval");
						$sWhere                               = " c28_conlancamval = {$oStdConlanCamVal->c69_sequen}";
						$oDaoContaCorrenteDetalheConLancamVal->excluir(null, $sWhere);

						if ($oDaoContaCorrenteDetalheConLancamVal->erro_status == "0") {

							$sqlerro  = true;
							$erro_msg = "Erro ao excluir contacorrente. Contate o suporte.";
							break;
						}
					}

	        $clconlancamlr->sql_record($clconlancamlr->sql_query_file($c69_sequen));

	        if ($clconlancamlr->numrows > 0) {

						$clconlancamlr->excluir($c69_sequen);
						$erro_msg = $clconlancamlr->erro_msg;
						if ($clconlancamlr->erro_status == "0") {
							$sqlerro = true;
							$erro_msg = $clconlancamlr->erro_msg;
							break;
						}
	        }

					$clconlancamval->excluir($c69_sequen);
	        $erro_msg = $clconlancamval->erro_msg;

	        if ($clconlancamval->erro_status == "0") {
	          
						$sqlerro   = true;
						$erro_msg  = "Voc� est� tentando reprocessar lan�amentos em um per�odo encerrado. Caso seja fundamental\n";
						$erro_msg .= "o reprocessamento desta forma, solicite ao respons�vel pela Contabilidade o desbloqueio da\n";
						$erro_msg .= "data de encerramento.";
						break;
	        }

					if ($debug == true) {
						 echo " excluindo registro $c69_sequen do conlancamval";
	        }

				}

	    }

	    if ($debug) {
	      echo "<br><br>C�digo do documento: {$c53_coddoc}<br><br>";
	    }

	    if( $sqlerro ){
	    	break;
	    }
	    
			switch ($c53_coddoc) {

				case 1   : //EMPENHAR
				case 82  : //EMPENHO DE PASSIVO SEM SUPORTE OR�AMENT�RIO
				case 304 : //EMPENHO DA PROVIS�O DE F�RIAS
				case 308 : //EMPENHO DA PROVIS�O DE 13� SAL�RIO
				case 410 : //EMPENHO SUPRIMENTO DE FUNDOS
				case 504 : //EMPENHO AMORT. DA D�VIDA
				case 500 : //EMPENHO DE PRECAT�RIOS

					$cltranslan->db_trans_empenho($e60_codcom, $e60_anousu, $c53_coddoc);
					break;

	      case 2   : //ESTORNAR EMPENHO
	      case 411 : //ESTORNO DE EMPENHO SUPRIMENTO DE FUNDOS
	      case 501 : //ESTORNO DE EMPENHO DE PRECAT�RIOS
	      case 505 : //ESTORNO AMORT. D�VIDA
	      case 83  : //ESTORNO DE EMPENHO DE PASSIVO SEM SUP OR�AMENT�RIO
	      case 305 : //ESTORNO DE EMPENHO DA PROVIS�O DE F�RIAS
	      case 309 : //ESTORNO DE EMPENHO DA PROVIS�O DE 13� SAL�RIO

					$cltranslan->db_trans_estorna_empenho($e60_codcom, $e60_anousu, $c53_coddoc);
					break;

				case 3   : //LIQUIDAR
				case 84  : //LIQUIDA��O DE EMPENHO DE PASSIVO SEM SUP. OR�AMENT
				case 202 : //LIQUIDA��O DESPESA COM SERVI�OS
				case 204 : //LIQUIDA��O DESPESA MATERIAL DE CONSUMO
				case 206 : //LIQUIDA��O AQUISI��O MATERIAL PERMANENTE
				case 306 : //LIQUIDA��O DA PROVIS�O DE F�RIAS
				case 310 : //LIQUIDA��O DA PROVIS�O DE 13� SAL�RIO
				case 412 : //LIQUIDA��O SUPRIMENTO DE FUNDOS
				case 506 : //LIQUIDA��O AMORT. D�VIDA
				case 502 : //LIQUIDA��O DE PRECAT�RIOS

					$cltranslan->db_trans_liquida($e60_codcom, $e64_codele, $e60_anousu, $c53_coddoc);
					break;

	      case 4   : //estornar liquida��o
	      case 85  : //ESTORNO DE LIQ DE EMP DE PASSIVO SEM SUP OR�AMENT
	      case 203 : //ESTORNO DE LIQUIDACAO DE DESPESA COM SERVI�OS
	      case 205 : //ESTORNO DE LIQ. DESPESA MATERIAL DE CONSUMO
	      case 207 : //ESTORNO DE LIQ. AQ. MATERIAL PERMANENTE
	      case 307 : //ESTORNO DE LIQUIDA��O DA PROVIS�O DE F�RIAS
	      case 311 : //ESTORNO DE LIQUIDA��O DA PROVIS�O DE 13� SAL�RIO
	      case 413 : //ESTORNO DE LIQUIDA��O SUPRIMENTO DE FUNDOS
	      case 503 : //ESTORNO DA LIQUIDA��O DE PRECAT�RIOS
	      case 507 : //ESTORNO LIQUIDA��O AMORT. D�VIDA
					if (!$cltranslan->db_trans_estorna_liquida($e60_codcom, $e64_codele, $e60_anousu, $c53_coddoc)) {

					  $sqlerro  = $cltranslan->sqlerro;
					  $erro_msg = $cltranslan->erro_msg;
					}
					break;

				case 5  : //PAGAMENTO

					$cltranslan->db_trans_pagamento($e64_codele, $c82_reduz, $e60_anousu, $e60_numemp, $c53_coddoc);
					//axo q tath errado no emp1_empapgamento002.php
					break;

	      case 6  : //ESTORNAR PAGAMENTO
					$cltranslan->db_trans_estorna_pagamento($e64_codele, $c82_reduz, $e60_anousu, $e60_numemp, $c53_coddoc);
					break;

				case 23 : //liquida capital
					$cltranslan->db_trans_liquida_capital($e60_codcom, $e64_codele, $e60_anousu);
					break;

				case 24 : //estorna liquida��o capital
					$cltranslan->db_trans_estorna_liquida_capital($e60_codcom, $e64_codele, $e60_anousu);
					break;

				case 31 : //anula��o de RP processado
					$cltranslan->db_trans_estorna_empenho_resto_processado($e60_codcom, $e60_anousu, $e60_numemp);
					break;

				case 32 : //anula��o de RP nao processado
					$cltranslan->db_trans_estorna_empenho_resto($e60_codcom, $e60_anousu, $e60_numemp);
					break;

				case 33 : //liquida RP
					$cltranslan->db_trans_liquida_resto($e60_codcom, $e64_codele, $e60_anousu, $e60_numemp);
					break;

				case 34 : //estorna liquida��o   RP
					$cltranslan->db_trans_estorna_liquida_resto($e60_codcom, $e64_codele, $e60_anousu, $e60_numemp);
					break;

				case 35 : //pagamento de RP
				case 37 :
					$cltranslan->db_trans_pagamento_resto($e64_codele, $c82_reduz, $e60_anousu, $e60_numemp,$c53_coddoc);
					break;

				case 36 : //estorna pagamento de RP
				case 38 :
					$cltranslan->db_trans_estorna_pagamento_resto($e64_codele, $c82_reduz, $e60_anousu, $e60_numemp,$c53_coddoc);
					break;

	      case 7:
	      case 8:
	      case 10:
	      case 12:
	      case 51:
	      case 52:
	      case 53:
	      case 54:
	      case 55:
	      case 56:
	      case 57:
	      case 58:
	      case 59:
	      case 60:
	      case 61:
	      case 62:
	      case 63:
	      case 64:
	      case 65:
	      case 71:
	      case 72:
	      case 73:

	        $estorna_sup=false;
	        if (  $c53_coddoc == 8 ||$c53_coddoc == 10 ||$c53_coddoc == 12 ) {
	          /*
			      * - tipo 41 - doc  8: estorno suplementa��o
			      * - tipo 51 - doc 10: credito especial
			      * - tipo 61 - doc 12: redu��o
			      */
	          $rrr= $clconlancamretif->sql_record($clconlancamretif->sql_query_file($c70_codlan,"c79_coddoc"));
	          db_fieldsmemory($rrr,0);
	          global $c79_coddoc;
	          $estorna_sup=true;
	        }

				  $verinst = "select o46_instit
					           	       from conlancamsup
								            inner join orcsuplem on c79_codsup = o46_codsup
	                          where c79_codlan = $c70_codlan";

					$resres = db_query($verinst);
					if ($debug==true) {
						 db_criatabela($resres);
	        }

					if (pg_numrows($resres) > 0) {
						if (pg_result($resres, 0, 0) != db_getsession("DB_instit")) {
							break;
	          }

						if($c53_coddoc != 58) {
	            $verdot = "select c73_coddot
	                              from conlancamdot
							  		     where c73_codlan = $c70_codlan";

						  $resultdot = db_query($verdot);
						  $coddotsup = pg_result($resultdot, 0, 0);

						  $verdot = "select o58_instit from orcdotacao
									      where o58_anousu = ".db_getsession("DB_anousu")." and
	                      o58_coddot = $coddotsup";

						  $resultdot = db_query($verdot);
	            $instit_dot = pg_result($resultdot, 0, 0);
	          } else {

						   $instit_dot = db_getsession("DB_instit");
						   $c79_coddoc = 58;
						}

						$instit_atual = db_getsession("DB_instit");
						$HTTP_SESSION_VARS["DB_instit"] = $instit_dot;
	          if($estorna_sup) {

						  $sql = $cltranslan->sql_query_lr(null,"c47_seqtranslr,c46_codhist,c47_credito,c47_debito,c47_ref",'',
	                        												"c45_coddoc = ".$c79_coddoc." and c47_anousu=".db_getsession("DB_anousu"));
	          } else {

	          	$sql = $cltranslan->sql_query_lr(null,"c47_seqtranslr,c46_codhist,c47_credito,c47_debito,c47_ref",'',
	                        												"c45_coddoc = ".$c53_coddoc." and c47_anousu=".db_getsession("DB_anousu"));
	          }

						$result_sup = $cltranslan->sql_record($sql);
						$cont = 0;

						$HTTP_SESSION_VARS["DB_instit"] = $instit_atual;

	          if ($result_sup == true && pg_numrows($result_sup) > 0) {

	            for ($sup = 0; $sup < pg_numrows($result_sup); $sup ++) {

								db_fieldsmemory($result_sup, $sup);
	              if ($c47_ref == 0 || ($c47_ref != 0 && $c47_ref == $codcom)) {

	                if ($estorna_sup==false) {

									    $cltranslan->arr_credito[$cont] = $c47_credito;
									    $cltranslan->arr_debito[$cont]  = $c47_debito;
	                } else {

										$cltranslan->arr_credito[$cont]= $c47_debito;
									  $cltranslan->arr_debito[$cont] = $c47_credito;
	                }

									$cltranslan->arr_histori[$cont] = $c46_codhist;
									$cltranslan->arr_seqtranslr[$cont] = $c47_seqtranslr;
									$cont ++;
								}
							}
	          } else {

							echo "<script>
								          alert('Lan�amentos n�o configurados');
									  </script>";
						}
	        }

					break;
				case 100 : //ARRECADA RECEITA
				case 107 : //ARRECADA��O DE RECEITA LAN�ADA
				case 109 : //RECEITA OPERA��O DE CR�DITO
				case 111 : //RECEITA DE ALIENA��O DE BENS
				case 113 : //RECEITA DE CONV�NIOS
				case 115 : //RECEITA DE TRANSFER�NCIAS
				case 117 : //RECEBIMENTO DE D�VIDA ATIVA
				case 416 : //DEVOLU��O DE ADIANTAMENTO
				case 419 :

	        // busca o codcon da conta no plano de contas para pegar o reduzido

          if (USE_PCASP) {

            $oDaoConPlanoOrcamentoAnalitica = db_utils::getDao("conplanoorcamentoanalitica");
            $sCampos                        = "conplanoreduz.c61_reduz, conplanoreduz.c61_codcon as deducao";
            $sOrder                         = "conplanoreduz.c61_reduz";
            $sWhere                         = "     conplanoorcamentoanalitica.c61_anousu = " . db_getsession("DB_anousu");
            $sWhere                        .= " and conplanoorcamentoanalitica.c61_codcon = {$o70_codfon}";
            $sWhere                        .= " and conplanoorcamentoanalitica.c61_instit = " . db_getsession("DB_instit");
            $sSql                           = $oDaoConPlanoOrcamentoAnalitica->sql_query_reduzVinculoAnalitica(null, null, $sCampos, $sOrder, $sWhere);
            $resultrec                      = $oDaoConPlanoOrcamentoAnalitica->sql_record($sSql);

            if ($oDaoConPlanoOrcamentoAnalitica->numrows == 0) {

              $erro_msg = 'Conta da Receita n�o cadastrada. (' . db_getsession("DB_anousu") . ', ' . $codrec . ') na conplanoreduz.';
              $sqlerro  = true;
              break;
            }
          } else {

    	      $sCamposConPlanoReduz = "c61_reduz, c61_codcon as deducao";
    	      $sOrderConPlanoReduz  = "c61_reduz";
    	      $sWhereConPlanoReduz  = " c61_anousu = " . db_getsession("DB_anousu") . " and c61_codcon = {$o70_codfon} and c61_instit = " . db_getsession("DB_instit");
    	      $sSqlConPlanoReduz    = $clconplanoreduz->sql_query(null,null, $sCamposConPlanoReduz, $sOrderConPlanoReduz, $sWhereConPlanoReduz);
    	      $sSqlConPlanoReduz    = analiseQueryPlanoOrcamento($sSqlConPlanoReduz);
    				$resultrec            = $clconplanoreduz->sql_record($sSqlConPlanoReduz);

    				if ($clconplanoreduz->numrows == 0) {

    				  $erro_msg = 'Conta da Receita n�o cadastrada. (' . db_getsession("DB_anousu") . ', ' . $codrec . ') na conplanoreduz.';
    				  $sqlerro  = true;
    				  break;
    				}
          }

					db_fieldsmemory($resultrec, 0);
					$sSql = "select c60_estrut
	                   from conplano
	                  where c60_codcon = {$deducao}
	                    and fc_conplano_grupo(" . db_getsession("DB_anousu") . ", substr(c60_estrut,1,2)||'%', 9000) is true";

					$resultded = db_query($sSql);
					if (pg_numrows($resultded) > 0) {
						$cltranslan->db_trans_estorno_receita($c82_reduz, $c61_reduz, $c74_anousu, $c53_coddoc, $o70_codfon);
					} else {
						$cltranslan->db_trans_arrecada_receita($c82_reduz, $c61_reduz, $c74_anousu, $c53_coddoc, $o70_codfon);
					}

					break;
	      case 101 : //ESTORNA ARRECADA��O DE RECEITA
	      case 108 : //ESTORDO DE ARRECADA��O DE RECEITA LAN�ADA
	      case 110 : //RECEITA OP. DE CR�DITO - ESTORNO
	      case 112 : //REC. DE ALIENA��O DE BENS - ESTORNO
	      case 114 : //RECEITA DE CONV�NIOS
	      case 116 : //RECEITA DE TRANSFER�NCIAS - ESTORNO
	      case 118 : //ESTORNO DE RECEBIMENTO DE D�VIDA ATIVA
	      case 417 : //ESTORNO DE DEVOLU��O DE ADIANTAMENTO
	      case 418 :


	      	if (USE_PCASP) {

	      		$oDaoConPlanoOrcamentoAnalitica = db_utils::getDao("conplanoorcamentoanalitica");
	      		$sCampos                        = "conplanoreduz.c61_reduz, conplanoreduz.c61_codcon as deducao";
	      		$sOrder                         = "conplanoreduz.c61_reduz";
	      		$sWhere                         = "     conplanoorcamentoanalitica.c61_anousu = " . db_getsession("DB_anousu");
	      		$sWhere                        .= " and conplanoorcamentoanalitica.c61_codcon = {$o70_codfon}";
	      		$sWhere                        .= " and conplanoorcamentoanalitica.c61_instit = " . db_getsession("DB_instit");
	      		$sSql                           = $oDaoConPlanoOrcamentoAnalitica->sql_query_reduzVinculoAnalitica(null, null, $sCampos, $sOrder, $sWhere);
	      		$resultrec                      = $oDaoConPlanoOrcamentoAnalitica->sql_record($sSql);

	      		if ($oDaoConPlanoOrcamentoAnalitica->numrows == 0) {

	      			$erro_msg = 'Conta da Receita n�o cadastrada. (' . db_getsession("DB_anousu") . ', ' . $codrec . ') na conplanoreduz.';
	      			$sqlerro  = true;
	      			break;
	      		}

	      	} else {

		        // busca o codcon da conta no plano de contas para pegar o reduzido
		        $sSql = $clconplanoreduz->sql_query(null,null, "c61_reduz,c61_codcon as deducao", "c61_reduz", " c61_anousu = ".db_getsession("DB_anousu")." and c61_codcon = $o70_codfon and c61_instit = ".db_getsession("DB_instit"));
		        $resultrec = $clconplanoreduz->sql_record($sSql);

		        if ($clconplanoreduz->numrows == 0) {

		        	$erro_msg = 'Conta da Receita n�o cadastrada.('.db_getsession("DB_anousu").','.$codrec.') no conplanoreduz.';
		        	$sqlerro = true;
		        	break;
		        }

	      	}

					db_fieldsmemory($resultrec, 0);

					$sql = "select c60_estrut
	                from conplano
	                where c60_anousu = ".db_getsession("DB_anousu")." and c60_codcon = $deducao and
	                      fc_conplano_grupo(".db_getsession("DB_anousu").",substr(c60_estrut,1,2)||'%',9000) is true";
	        $resultded = db_query($sql);

					if (pg_numrows($resultded) > 0) {
						$cltranslan->db_trans_arrecada_receita($c82_reduz, $c61_reduz, $c74_anousu, $c53_coddoc, $o70_codfon );
					} else {
						$cltranslan->db_trans_estorno_receita($c82_reduz, $c61_reduz, $c74_anousu, $c53_coddoc, $o70_codfon);
					}

					break;

	      case 200:
	      case 201:

	        $cltranslan->db_trans_controle_despesa_liquidacao($c53_coddoc);
	        break;

	      case 208:
	      case 209:

	        $oDaoBensNota  = db_utils::getDao("bensempnotaitem");
	        $sWhereEmpenho = "e69_numemp = {$e60_numemp}";
	        $sSqlBuscaBem  = $oDaoBensNota->sql_query_bens_nota(null, "distinct t52_bem", " t52_bem limit 1", $sWhereEmpenho);
	        $rsBuscaBem    = $oDaoBensNota->sql_record($sSqlBuscaBem);

	        if ($oDaoBensNota->numrows == 0) {

	          $sqlerro = true;
					  $erro_msg = "Bem n�o encontrado para o empenho {$e60_numemp}";
					  break;
	        }

	        $iCodigoBem                = db_utils::fieldsMemory($rsBuscaBem, 0)->t52_bem;
	        $oBem                      = new Bem($iCodigoBem);
	        $iCodigoContaClassificacao = $oBem->getClassificacao()->getContaContabil()->getReduzido();
	        $cltranslan->db_trans_controle_despesa_liquidacao_material_permanente($iCodigoContaClassificacao, $c53_coddoc);



	        break;
	      default :

	        $sqlerro = true;
					$erro_msg = "Documento n�o encontrado!";

			}

			$arr_debito     = $cltranslan->arr_debito;
			$arr_credito    = $cltranslan->arr_credito;
			$arr_histori    = $cltranslan->arr_histori;
	    $arr_seqtranslr = $cltranslan->arr_seqtranslr;

			if (count($arr_debito) == 0) {
				continue;
			}

			/* rotina que verifica se os array com os lan�amentos naum est�o vazios*/
			if (count($arr_debito) == 0 && $sqlerro == false) {

				$sqlerro = true;
				$erro_msg = 'Conta d�bito n�o cadastrada nas trasa��es.';
	    }

	    if (count($arr_credito) == 0 && $sqlerro == false) {

				$sqlerro = true;
				$erro_msg = 'Conta cr�dito n�o cadastrada nas trasa��es.';
	    }

	    if (count($arr_histori) == 0 && $sqlerro == false) {

				$sqlerro = true;
				$erro_msg = 'Hist�rico do lan�amento nao encontrado.';
	    }

			//final=========================================================
	    if ($debug == true) {

				echo "<br>Documento: $c53_coddoc ";
				echo "<br>Array de d�bitos   : ";
				print_r($arr_debito);
				echo "<br>Array de cr�ditos  : ";
				print_r($arr_credito);
				echo "<br>Array de hist�ricos: ";
				print_r($arr_histori);
				echo "<br><br>";
			  flush();
	    }

	    if ($sqlerro == false) {

	      for ($t = 0; $t < count($arr_credito); $t ++) {

//         echo "dentro do for {$t} codlan : {$c70_codlan} -- ".$arr_credito[$t]." -- ".$arr_debito[$t]." -- ".$arr_histori[$t]."<br>";

					//$clconlancamval->c69_sequen  = $c69_sequen;
					$clconlancamval->c69_codlan  = $c70_codlan;
					$clconlancamval->c69_credito = $arr_credito[$t];
					$clconlancamval->c69_debito  = $arr_debito[$t];
					$clconlancamval->c69_codhist = $arr_histori[$t];
					$clconlancamval->c69_valor   = $c70_valor;
					$clconlancamval->c69_data    = $c70_data;
					$clconlancamval->c69_anousu  = $c70_anousu;
					$clconlancamval->incluir(null);
					$erro_msg = $clconlancamval->erro_msg;

					if ($clconlancamval->erro_status == 0) {

						$sqlerro = true;
						break;
					}

					if (USE_PCASP) {

						$oLancamentoAuxiliarContaCorrente = new LancamentoAuxiliarContaCorrente($c70_codlan);
						$oContaCredito                    = ContaCorrenteFactory::getInstance($clconlancamval->c69_sequen, $clconlancamval->c69_credito, $oLancamentoAuxiliarContaCorrente);
						$oContaDebito                     = ContaCorrenteFactory::getInstance($clconlancamval->c69_sequen, $clconlancamval->c69_debito, $oLancamentoAuxiliarContaCorrente);

						if($oContaCredito !== false) {
							$oContaCredito->salvar($c70_data);
						}

						if($oContaDebito !== false) {
							$oContaDebito->salvar($c70_data);
						}

					}

				}

	    }

			if ($sqlerro == true) {
				break;
			}

	  }

		if ($numrows == 0) {
			$erro_msg = 'Nenhum lan�amento encontrado para este documento.';
		}

	  if ($debug == false) {
		 	db_fim_transacao($sqlerro);
		}
	} catch (Exception $eErro) {

		$sqlerro = true;
		$erro_msg = str_replace("\n", "\\n", $eErro->getMessage());
		db_fim_transacao(true);
	} catch (ParameterException $eErro) {

		$sqlerro = true;
		$erro_msg = str_replace("\n", "\\n", $eErro->getMessage());
		db_fim_transacao(true);
	} catch (BusinessException $eErro) {

		$sqlerro = true;
		$erro_msg = str_replace("\n", "\\n", $eErro->getMessage());
		db_fim_transacao(true);
	} catch (DBException $eErro) {

		$sqlerro = true;
		$erro_msg = str_replace("\n", "\\n", $eErro->getMessage());
		db_fim_transacao(true);
	}

	$_SESSION["DB_desativar_account"] = false;
	unset($_SESSION["DB_desativar_account"]);

	//Ativa autovacuum para tabelas definidas no array
	foreach ($aTables as $sTabela) {

		$sSqlAjusteDesempenho = "alter table {$sTabela} set (autovacuum_enabled = true, toast.autovacuum_enabled = true)";
		$rsAjusteDesempenho   = db_query($sSqlAjusteDesempenho);
	}
}

$clrotulo = new rotulocampo;
$clrotulo->label("c45_coddoc");
$clrotulo->label("c50_descr");
$clrotulo->label("c53_coddoc");
$clrotulo->label("c53_descr");
$clrotulo->label("e60_numemp");
$clrotulo->label("c70_codlan");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
      <script>
      var USE_PCASP = "<?php echo USE_PCASP ? "true" : "false";?>";

      function js_verifica() {

        if(USE_PCASP == "true") {
          if(document.form1.c53_coddoc.value == '' && document.form1.e60_numemp.value == '' && $F("iTipoDoc") == "") {

            alert('Indique o tipo do documento ou o c�digo do documento ou o n�mero do empenho.');
            return false;
          }
        } else {
          if(document.form1.c53_coddoc.value == '' && document.form1.e60_numemp.value == '') {
            alert('Indique o c�digo do documento ou o n�mero do empenho.');
            return false;
          }
        }

        var sMensagemConfirm = "Voc� est� prestes a recriar os lan�amentos cont�beis de acordo com o filtro ";
        sMensagemConfirm    += "selecionado.\n\nConfirma esta opera��o?";
        if (!confirm(sMensagemConfirm)) {
        	return false;
        }
        return true;
      }

      function js_focarCampo() {

        if(USE_PCASP == "true") {
          $("iTipoDoc").focus();
        } else {
          $("c53_coddoc").focus();
        }
      }
    </script>
  </head>

  <body bgcolor="#CCCCCC" style="margin-top:30px" onLoad="js_focarCampo();">
    <center>
      <form name="form1" id="form1"method="post" action="">
        <fieldset style="width: 600px;">
          <legend><b>Filtros</b></legend>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="left" valign="top" bgcolor="#CCCCCC">
                <center>
                    <table border="0">
                      <?php if(USE_PCASP) { ?>
                        <tr>
                          <td nowrap title="Tipo de documento">
                            <?php db_ancora("<b>Tipo de documento</b>", "js_tipoDoc(true);", 1);?>
                          </td>
                          <td>
                            <?php
                              db_input('iTipoDoc', 4, '1', true, 'text', 1, " onchange='js_tipoDoc(false);'");
                              db_input('sTipoDoc', 40, '0', true, 'text', 3, '');
                            ?>
                          </td>
                        </tr>
                      <?php } ?>

                      <tr>
                        <td nowrap title="<?php echo @$Tc53_coddoc?>">
                          <?php db_ancora(@ $Lc53_coddoc, "js_coddoc(true);", 1);?>
                        </td>
                        <td>
                          <?php
                            db_input('c53_coddoc', 4, $Ic53_coddoc, true, 'text', 1, " onchange='js_coddoc(false);'");
                            db_input('c53_descr', 40, $Ic53_descr, true, 'text', 3, '');
                          ?>
                        </td>
                      </tr>

                      <tr>
          	            <td nowrap title="<?php echo @$Te60_numemp?>">
          	              <?php db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true);",1); ?>
                      	</td>
                      	<td>
          	             <?php db_input('e60_numemp',15,$Ie60_numemp,true,'text',1," onchange='js_pesquisae60_numemp(false);'");?>
          	            </td>
                      </tr>

                      <tr>
          	            <td nowrap title="<?php echo @$Tc70_codlan?>">
          	              <?php db_ancora(@$Lc70_codlan,"",3); ?>
          	            </td>
          	            <td>
          	              <?php db_input('qcodlan',15,$Ic70_codlan,true,'text',1," "); ?>
          	            </td>
                      </tr>

                      <tr>
                        <td nowrap><b>Per�odo:</b></td>
                        <td>
                          <?php db_inputdata('dti',@$dti_dia,@$dti_mes,@$dti_ano,true,'text',1);  ?>
                              �
          	              <?php db_inputdata('dtf',@$dtf_dia,@$dtf_mes,@$dtf_ano,true,'text',1);  ?>

                        </td>
                      </tr>
                    </table>

                </center>
              </td>
            </tr>
          </table>
        </fieldset>
        <input style="margin-top: 10px;" name="processar" type="submit" id="db_opcao" value="Processar" onclick='return js_verifica();'>
        <input type="button" value="Voltar" onClick="js_voltar();" />
      </form>
    </center>
  <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));?>
  </body>
</html>
<script>
/**
  * Fun��es para o filtro do tipo de documento
  * Para poder processar os lan�amentos de todo aquele tipo de documento
  */
function js_tipoDoc(lMostra) {

  if(lMostra) {
    js_OpenJanelaIframe('','db_iframe_tipodoc','func_tipodoc.php?funcao_js=parent.js_mostraTipoDoc|c57_sequencial|c57_descricao','Pesquisa Tipos de Documento',true);
  } else {
    if($F("iTipoDoc") != "") {
      js_OpenJanelaIframe("","db_iframe_tipodoc","func_tipodoc.php?pesquisa_chave=" + $F("iTipoDoc") + "&funcao_js=parent.js_mostraTipoDoc1","Pesquisa Tipos de Documento", false);
    } else {
      $("sTipoDoc").value = "";
    }
  }
}

/**
 * Voltar para pagina dos tipos de reprocessamento
 *
 * @access public
 * @return void
 */
function js_voltar() {

  js_divCarregando('Voltando...', 'msgBox');
  document.location.href = 'con4_processalancamentos001.php';
}

/**
  * Fun��o disparada ap�s fechar a lookup
  */
function js_mostraTipoDoc(c57_sequencial, c57_descricao) {

  $("iTipoDoc").value = c57_sequencial;
  $("sTipoDoc").value = c57_descricao;
  db_iframe_tipodoc.hide();
}

/**
  * Fun��o disparada quando digitar direto o c�digo do tipo, sem entrar na lookup
  */
function js_mostraTipoDoc1(sTipoDoc, lErro) {

  $("sTipoDoc").value = sTipoDoc;

  if(lErro) {

    $("iTipoDoc").focus();
    $("iTipoDoc").value = "";
  }
}

/**
  * Fun��es para o filtro do c�digo do documento
  */
function js_coddoc(mostra) {
  /**
    * Caso esteja tentando filtrar pelo tipo do documento no filtro
   */
  var iTipoDoc = "";

  if(USE_PCASP == "true") {
    iTipoDoc = $F("iTipoDoc");
  }
  var sTipoDoc = "&iCodigoTipoDocumento=";

  if(iTipoDoc != "") {
    sTipoDoc += iTipoDoc;
  }

  if(mostra == true) {
    js_OpenJanelaIframe('','db_iframe_conhistdoc','func_conhistdoc.php?lDocumentosProcessadosOutraRotina=true&funcao_js=parent.js_mostraconhistdoc1|c53_coddoc|c53_descr' + sTipoDoc + '','Pesquisa',true);
  } else {
     if(document.form1.c53_coddoc.value != '') {
        js_OpenJanelaIframe('','db_iframe_conhistdoc','func_conhistdoc.php?lDocumentosProcessadosOutraRotina=true&pesquisa_chave='+document.form1.c53_coddoc.value+'&funcao_js=parent.js_mostraconhistdoc' + sTipoDoc, 'Pesquisa',false);
     } else {
       document.form1.c53_descr.value = '';
     }
  }
}

function js_mostraconhistdoc(chave,erro) {
  document.form1.c53_descr.value = chave;
  if(erro == true) {

    document.form1.c53_coddoc.focus();
    document.form1.c53_coddoc.value = '';
  }
}

function js_mostraconhistdoc1(chave1,chave2) {

  document.form1.c53_coddoc.value = chave1;
  document.form1.c53_descr.value = chave2;
  db_iframe_conhistdoc.hide();
}

/**
  * Fun��es para o filtro por empenho
  */
function js_pesquisae60_numemp(mostra) {
  if(mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp','Pesquisa',true);
  } else {
     if(document.form1.e60_numemp.value != '') {
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
     } else {
       document.form1.e60_numemp.value = '';
     }
  }
}

function js_mostraempempenho(chave,erro) {
  if(erro == true){

    document.form1.e60_numemp.focus();
    document.form1.e60_numemp.value = '';
  }
}

function js_mostraempempenho1(chave1,x) {

  document.form1.e60_numemp.value = chave1;
  db_iframe_empempenho.hide();
}

$('iTipoDoc').value   = "";
$('sTipoDoc').value   = "";
$('c53_coddoc').value = "";
$('c53_descr').value  = "";
$('e60_numemp').value = "";
$('qcodlan').value    = "";
$('dti').value        = "";
$('dtf').value        = "";

</script>
<?php
if (isset ($processar)) {
	if ($sqlerro == false) {
		db_msgbox("Total de registros atualizados:{$numrows}");
	} else {
		db_msgbox($erro_msg);
	}
}
?>