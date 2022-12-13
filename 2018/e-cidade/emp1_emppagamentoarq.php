<?
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


$debug = false; //se true mensagens de teste na tela

//variáveis nescessárias
/*
   $e60_numemp
   $vlrpag
   $dados   ->array
*/

$result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp));
db_fieldsmemory($result, 0);

$sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = ".db_getsession("DB_anousu");
$res = db_query($sql);

$pago = ($e60_vlrpag + $vlrpag);
$xverifica = (($e60_vlremp - $e60_vlranu) - ($e60_vlrpag));
$xverifica = number_format($xverifica, "2", ".", "");

if (trim($vlrpag) > trim($xverifica)) {
	$erro_msg = "No empenho $e60_codemp, o valor digitado não está disponivel para ser pago (1)!";
	$sqlerro = true;
}

if ($sqlerro == false) {
	// este teste verifica se poderá ser feito lancamento na data e se tem saldo no empenho

	if ($e60_anousu < db_getsession("DB_anousu"))
		$codteste = "35"; // pagamento restos a pagar
	else
		$codteste = "5"; // pagamento empenho exercicio

	if (isset ($data_usuario)) {
		// se usuario informou a data na tela ...
		// tem outro if desses mais abaixo !!
		$datausu_v = $data_usuario;
	} else {
		$datausu_v = date("Y-m-d", db_getsession("DB_datausu"));
	}

	$sql = "select fc_verifica_lancamento(".$e60_numemp.",'".$datausu_v."',".$codteste.",".$vlrpag.")";

	$result_erro = db_query($sql);
	if ($debug == true) {
		db_criatabela($result_erro);
	}
	$erro_msg = pg_result($result_erro, 0, 0);
	if (substr($erro_msg, 0, 2) > 0) {
		$erro_msg = substr($erro_msg, 3);
		$sqlerro = true;
	}
}

//  echo "passou";exit;
if ($sqlerro == false) {
	$clempempenho->e60_vlrpag = "$pago";
	$clempempenho->e60_numemp = $e60_numemp;
	$clempempenho->alterar($e60_numemp);
	if ($clempempenho->erro_status == 0) {
		$sqlerro = true;
		$erro_msg = $clempempenho->erro_msg;
	} else {
		$ok_msg = $clempempenho->erro_msg;
	}
}
//array que irá armazenar os valores de cada elemento para fazer os lancamentos contabeis
$arr_codeleval = array ();
$arr_dados = split("#", $dados);
$tam = count($arr_dados);
for ($i = 0; $i < $tam; $i ++) {
	$arr_ele = split("-", $arr_dados[$i]);
	//$arr_ele[0] é o codigo do elemento   $arr_ele[1] é o valor que será pago  $arr_ele[2] é o valor que já foi pago
	$arr_codeleval[$arr_ele[0]] = $arr_ele[1];

	//rotina que atualiza os valores dos elementos
	$result03 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, $arr_ele[0], "e64_vlrpag,e64_vlranu,e64_vlremp"));
	if ($clempelemento->numrows > 0) {
		db_fieldsmemory($result03, 0);
	} else {
		$e64_vlrpag = 0;
	}

	//rotina que atualiza a tabela empelemento
	$clempelemento->e64_numemp = $e60_numemp;
	$clempelemento->e64_codele = $arr_ele[0];
	$tot_empele = $arr_ele[1] + $e64_vlrpag;

	$xverifica = (($e60_vlremp - $e60_vlranu) - ($e60_vlrpag));
	$xverifica = db_formatar($xverifica, 'p');
	if (trim($arr_ele[1]) > trim($xverifica)) {
		$erro_msg = "No empenho $e60_codemp, o  valor digitado não está disponivel para ser pago (2)!";
		$sqlerro = true;
	}

	if ($sqlerro == false) {
		$clempelemento->e64_vlrpag = "$tot_empele";
		$clempelemento->alterar($e60_numemp, $arr_ele[0]);
		$erro_msg = $clempelemento->erro_msg;
		if ($clempelemento->erro_status == 0) {
			$sqlerro = true;
			break;
		}
	}
	//final
	$elemento = $arr_ele[0];
	//rotina que atualiza a tabela pagordemele, caso tenha entrado por ordem de pagamento
	if (isset ($pag_ord) || isset ($pagamento_auto)) {
		$result05 = $clpagordemele->sql_record($clpagordemele->sql_query_file($e50_codord, $elemento));
		if ($clpagordemele->numrows > 0 ){
	  	   db_fieldsmemory($result05, 0);
                } else {
		   $sqlerro=true;
		   $erro_msg="Ordem de pagamento não localizada e/ou elemento da OP diferente do elemento do Empenho";
		   break;
		}
		$clpagordemele->e53_codord = $e50_codord;
		$clpagordemele->e53_codele = $elemento;
		$pagar = $arr_ele[1] + $e53_vlrpag;
		$xverifica = (($e60_vlremp - $e60_vlranu) - ($e60_vlrpag));
		$xverifica = db_formatar($xverifica, 'p');
		if (trim($arr_ele[1]) > trim($xverifica)) {
			$erro_msg = "No empenho $e60_codemp, o valor digitado não está disponivel para ser pago (3)!";
			$sqlerro = true;
		}
		if ($sqlerro == false) {
			$clpagordemele->e53_vlrpag = "$pagar";
			$clpagordemele->alterar($e50_codord);
			$erro_msg = $clpagordemele->erro_msg;
			if ($clpagordemele->erro_status == 0) {
				$sqlerro = true;
				break;
			}
		}
	}
	//final
}

////
//rotina que teste se é resto à pagar, se for entra na condição a baixo
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////LANÇAMENTO CONTÁBIL//////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*verifica se todos os  elementos da tabela empelemento existem no comparlancam*/
$result09 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_codele,o56_elemento"));
$numrows09 = $clempelemento->numrows;
/*final*/

//dados comuns
$anousu = db_getsession("DB_anousu");

if (isset ($data_usuario)) {
	// se usuario informou a data na tela ...
	// tem outro if desses mais abaixo !!
	$datausu = $data_usuario;
} else {
	$datausu = date("Y-m-d", db_getsession("DB_datausu"));
}

if ($e60_anousu == $anousu) {
	$c71_coddoc = '5';
} else {
	$c71_coddoc = '35';
}

if ($sqlerro == false) {
	for ($i = 0; $i < $numrows09; $i ++) {
		db_fieldsmemory($result09, $i); //pegas os dados do empelemento
		$valor_pagar = $arr_codeleval[$e64_codele];
		if ($valor_pagar == 0) {
			continue;
		}

		/*conlancam*/
		if ($sqlerro == false) {
			$clconlancam->c70_anousu = $anousu;
			$clconlancam->c70_data = $datausu;
			$clconlancam->c70_valor = $valor_pagar;
			$clconlancam->incluir(null);
			$erro_msg = $clconlancam->erro_msg;
			if ($clconlancam->erro_status == 0) {
				$sqlerro = true;
			} else {
				$c70_codlan = $clconlancam->c70_codlan;
			}
      $lEvento = EventoContabil::vincularLancamentoNaInstituicao($clconlancam->c70_codlan , db_getsession("DB_instit"));
      $lEvento = EventoContabil::vincularOrdem($clconlancam->c70_codlan);
		}
		/*fim-conlancam*/

		/*conlancamele*/
		if ($sqlerro == false) {
			$clconlancamele->c67_codlan = $c70_codlan;
			$clconlancamele->c67_codele = $e64_codele;
			$clconlancamele->incluir($c70_codlan);
			$erro_msg = $clconlancamele->erro_msg;
			if ($clconlancamele->erro_status == 0) {
				$sqlerro = true;
			}
		}
		/*fim-conlancamele*/

		if ($e60_anousu == db_getsession("DB_anousu")) {
			/*orcdotacaoval*/
			if ($sqlerro == false) {
				$result85 = db_query("select fc_lancam_dotacao($e60_coddot,'$datausu',$c71_coddoc,'$valor_pagar') as dotacao");
				db_fieldsmemory($result85, 0);
				if (substr($dotacao, 0, 1) == 0) { //quando o primeiro caractere for igual a zero eh porque deu erro
					$sqlerro = true;
					$erro_msg = "Erro na atualização do orçamento \\n ".substr($dotacao, 1);
				}
			}
			/*fim-orcdotacaoval*/
			/*inicio-conlancamdot*/
			if ($sqlerro == false) {
				$clconlancamdot->c73_data = $datausu;
				$clconlancamdot->c73_anousu = $anousu;
				$clconlancamdot->c73_coddot = $e60_coddot;
				$clconlancamdot->c73_codlan = $c70_codlan;
				$clconlancamdot->incluir($c70_codlan);
				$erro_msg = $clconlancamdot->erro_msg;
				if ($clconlancamdot->erro_status == 0) {
					$sqlerro = true;
				}
			}
			/*fim-conlancamdot*/
		}

		/*conlancamcgm*/
		if ($sqlerro == false) {
			$clconlancamcgm->c76_data = $datausu;
			$clconlancamcgm->c76_codlan = $c70_codlan;
			$clconlancamcgm->c76_numcgm = $e60_numcgm;
			$clconlancamcgm->incluir($c70_codlan);
			$erro_msg = $clconlancamcgm->erro_msg;
			if ($clconlancamcgm->erro_status == 0) {
				$sqlerro = true;
			}
		}
		/*fim-conlancamcgm*/

		/*conlancamemp*/
		if ($sqlerro == false) {
			$clconlancamemp->c75_codlan = $c70_codlan;
			$clconlancamemp->c75_numemp = $e60_numemp;
			$clconlancamemp->c75_data = $datausu;
			$clconlancamemp->incluir($c70_codlan);
			$erro_msg = $clconlancamemp->erro_msg;
			if ($clconlancamemp->erro_status == 0) {
				$sqlerro = true;
			}
		}
		/*fim-conlancamemp*/




		/*quando for por ordem de pagamento*/
		//lança na tabela conlancamord
		if ((isset ($pag_ord) || isset ($pagamento_auto)) && $sqlerro == false) {
			$clconlancamord->c80_codlan = $c70_codlan;
			$clconlancamord->c80_codord = $e50_codord;
			$clconlancamord->c80_data = $datausu;
			$clconlancamord->incluir($c70_codlan);
			$erro_msg = $clconlancamord->erro_msg;
			if ($clconlancamord->erro_status == 0) {
				$sqlerro = true;
				break;
			}
		}
		/*fim-conlancamord*/

		/*inicio-conlancamdoc*/
		if ($sqlerro == false) {
			$clconlancamdoc->c71_data = $datausu;
			$clconlancamdoc->c71_coddoc = $c71_coddoc;
			$clconlancamdoc->c71_codlan = $c70_codlan;
			$clconlancamdoc->incluir($c70_codlan);
			$erro_msg = $clconlancamdoc->erro_msg;
			if ($clconlancamdoc->erro_status == 0) {
				$sqlerro = true;
			}
		}
		/*fim-conlancamdoc*/

		if ($sqlerro == false) {
			//rotina que pega os campos  e c91_codconpas
			//  $result11 = $clconparlancam->sql_record($clconparlancam->sql_query_file(null,"c91_codele,c91_codconpas","","c91_codele=$e64_codele"));
			//  db_fieldsmemory($result11,0);

			//rotina que pega o codigo reduzido a partir do numero da conta
			//retorna $k13_reduz
			if ($sqlerro == false) {
				$result55 = $clsaltes->sql_record($clsaltes->sql_query_file($k13_conta, "k13_reduz"));
				if ($clsaltes->numrows > 0) {
					db_fieldsmemory($result55, 0);
				} else {
					$sqlerro = true;
					$erro_msg = "Conta tributária inválida!";
					break;
				}
			}
			/*conlancampag*/
			if ($sqlerro == false) {
				$clconlancampag->c82_codlan = $c70_codlan;
				$clconlancampag->c82_anousu = $anousu;
				$clconlancampag->c82_reduz = $k13_reduz;
				$clconlancampag->incluir($c70_codlan);
				$erro_msg = $clconlancampag->erro_msg;
				if ($clconlancampag->erro_status == 0) {
					$sqlerro = true;
				}
			}
			/*fim-conlancampag*/
			//rotina que atualiza os array de creditos e debitos conforme o elemento
			if ($e60_anousu < db_getsession("DB_anousu")) {
			   $cltranslan->db_trans_pagamento_resto($e64_codele, $k13_reduz, $e60_anousu, $e60_numemp);
			} else {
			   $cltranslan->db_trans_pagamento($e64_codele,$k13_reduz, $e60_anousu);
			}
			$arr_debito = $cltranslan->arr_debito;
			$arr_credito = $cltranslan->arr_credito;
			$arr_histori = $cltranslan->arr_histori;
			$arr_seqtranslr = $cltranslan->arr_seqtranslr;

			//conta usada na funçao empautemt
			$conta_emp = $cltranslan->conta_emp;

			//final da rotina de atualização de arrays/////////
			if ($debug == true) {
				print_r($arr_debito);
				print_r($arr_credito);
				print_r($arr_histori);
				echo "conta emp : $conta_emp ";
			}

			/* rotina que verifica se os array com os lançamentos naum estão vazios*/
			if (count($cltranslan->arr_debito) == 0 && $sqlerro == false) {
				$sqlerro = true;
				$erro_msg = 'Conta débito não cadastrada nas trasações.';
			}
			if (count($cltranslan->arr_credito) == 0 && $sqlerro == false) {
				$sqlerro = true;
				$erro_msg = 'Conta crédito não cadastrada nas trasações.';
			}
			if (count($cltranslan->arr_histori) == 0 && $sqlerro == false) {
				$sqlerro = true;
				$erro_msg = 'Histórico do lançamento nao encontrado.';
			}
			//final=========================================================
		}

		if ($sqlerro == false) {
			//ROTina que inclui no conlancamval
			for ($t = 0; $t < count($arr_credito); $t ++) {
				//rotina que teste se a conta reduzida foi incluida no conplanoreduz
				$clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null, null, 'c61_codcon', '', "c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=".$arr_debito[$t]));
				if ($clconplanoreduz->numrows == 0) {
					$sqlerro = true;
					$erro_msg = "(D) Conta ".$arr_debito[$t]." não dísponivel para o exercicio!";

				}
				$rrr = $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null, null, 'c61_codcon', '', "c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=".$arr_credito[$t]));
				if ($debug == true) {
					echo $clconplanoreduz->sql_query_file(null, null, 'c61_codcon', '', "c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=".$arr_credito[$t]);
					echo "<br> rows ".$clconplanoreduz->numrows;
					echo "<br> rrr  ".pg_numrows($rrr);

				}
				if ($clconplanoreduz->numrows == 0) {
					$sqlerro = true;
					$erro_msg = "(C) Conta ".$arr_credito[$t]." não dísponivel para o exercicio!";

				}
				//final

				//lança na tabela conlancamval
				if ($sqlerro == false) {
					$clconlancamval->c69_codlan = $c70_codlan;
					$clconlancamval->c69_credito = $arr_credito[$t];
					$clconlancamval->c69_debito = $arr_debito[$t];
					$clconlancamval->c69_codhist = $arr_histori[$t];
					$clconlancamval->c69_valor = "$valor_pagar";
					$clconlancamval->c69_data = $datausu;
					$clconlancamval->c69_anousu = $anousu;
					$clconlancamval->incluir(null);
					$erro_msg = $clconlancamval->erro_msg;
					if ($clconlancamval->erro_status == 0) {
						$sqlerro = true;
						break;
					} else {
						$c69_sequen = $clconlancamval->c69_sequen;
					}
				}

				/*conlancamlr   */
				if ($sqlerro == false) {
					$clconlancamlr->c81_sequen = $c69_sequen;
					$clconlancamlr->c81_seqtranslr = $arr_seqtranslr[$t];
					$clconlancamlr->incluir($c69_sequen, $arr_seqtranslr[$t]);
					$erro_msg = $clconlancamlr->erro_msg;
					if ($clconlancamlr->erro_status == 0) {
						$sqlerro = true;
						break;
					}
				}
				/*final*/
			}
			/*fim-conlancamval*/
		}
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FINAL LANÇAMENTO CONTÁBEIS////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//rotina que inclui no corrente//////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($sqlerro == false) {
	$ip = db_getsession("DB_ip");
	if (isset ($data_usuario) && ($data_usuario != "")) {
		$data = $data_usuario;
	} else {
		$data = date("Y-m-d", db_getsession("DB_datausu"));
	}
	$porta = 5001;

	//rotina que verifica se o ip do usuario irá imprimir autenticar ou naum ira fazer nada
	$result99 = $clcfautent->sql_record($clcfautent->sql_query_file(null, "k11_tipautent", '', "k11_ipterm = '".$ip."'
	                                                                and k11_instit = ".db_getsession("DB_instit")));
	if ($clcfautent->numrows > 0) {
		db_fieldsmemory($result99, 0);

	} else {
		$erro_msg = "Cadastre o ip ".$ip." como um caixa.";
		$sqlerro = true;
	}
}
//------
if ($sqlerro == false) {
  // Quando nao tem movimento de agenda
  if (!isset($codigomovimento) || trim($codigomovimento)==""){
       $codigomovimento = "null";
  }

	if($k11_tipautent!=3) {
		if (empty ($e91_codcheque) || $e91_codcheque == '') {
			$e91_codcheque = '0';
		}
		if (empty ($k12_cheque) || $k12_cheque == '') {
			$k12_cheque = '0';
		}
		//funcao que ira retornar a variavel $retorno
		if (isset ($e50_codord) && $e50_codord != '') {
			$orde = $e50_codord;
		} else {
			$orde = '0';
		}
		if ($e60_anousu < db_getsession("DB_anousu")) {
			/*RESTO A PAGAR*/
			if ($conta_emp==""){
			   $sqlerro=true;
			   $erro_msg = "Verifique o tipo do RP e o cadastro das transações ! ";
			} else {
			   $sql = "select fc_autentemp($e60_numemp,$k13_conta,$conta_emp,'".$datausu."','$vlrpag',$k12_cheque,'$ip','$e91_codcheque',$orde,".db_getsession("DB_instit").", $codigomovimento,0,0) as retorno";
			}
		} else {
			$sql = "select fc_autentemp($e60_numemp,$k13_conta,0,'".$datausu."','$vlrpag',$k12_cheque,'$ip','$e91_codcheque',$orde,".db_getsession("DB_instit").", $codigomovimento,0,0) as retorno";
		}
		$result = db_query($sql);
		if ($result == false) {
			$sqlerro = true;
			//echo $sql;
			$erro_msg = "Erro na autenticação do empenho.(função FC_AUTENTEMP). Contate suporte";
		} else {
			db_fieldsmemory($result, 0);
			if (substr($retorno,0,1) != '1'){

         $erro_msg = $retorno;
				 $sqlerro  = true;
      }

		}
	}
}

if ($sqlerro == false&&$k12_cheque!=0&&$k11_tipautent!=3) {
	    $res_compl = $clconlancamcompl->sql_record($clconlancamcompl->sql_query($c70_codlan));
        if ($clconlancamcompl->numrows > 0){
	           $clconlancamcompl->c72_codlan = $c70_codlan;
	           $clconlancamcompl->c72_complem = $k12_cheque;
	           $clconlancamcompl->alterar($c70_codlan);
	           $erro_msg = $clconlancamcompl->erro_msg;
	           if ($clconlancamcompl->erro_status == 0) {
	                $sqlerro = true;
	           }
        } else {
	           $clconlancamcompl->c72_codlan = $c70_codlan;
	           $clconlancamcompl->c72_complem = $k12_cheque;
	           $clconlancamcompl->incluir($c70_codlan);
	           $erro_msg = $clconlancamcompl->erro_msg;
	           if ($clconlancamcompl->erro_status == 0) {
	                $sqlerro = true;
	           }
        }
}
?>