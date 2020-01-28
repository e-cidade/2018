<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

include(modification("libs/db_liborcamento.php"));
include(modification("classes/db_orcdotacao_classe.php"));
include(modification("classes/db_empempenho_classe.php"));
include(modification("classes/db_empanulado_classe.php"));
include(modification("classes/db_empanuladoele_classe.php"));
include(modification("classes/db_empelemento_classe.php"));
include(modification("classes/db_empautoriza_classe.php"));
include(modification("classes/db_pcprocitem_classe.php"));
include(modification("classes/db_orcreservaaut_classe.php"));
include(modification("classes/db_orcreserva_classe.php"));
include(modification("classes/db_orcreservasol_classe.php"));
include(modification("classes/db_empparametro_classe.php"));
include(modification("classes/db_empempenhonl_classe.php"));   // Comentado para a Tarefa 16697
include(modification("classes/db_empanuladotipo_classe.php"));

$clempempenho    = new cl_empempenho;
$clempanulado    = new cl_empanulado;
$clempanuladoele = new cl_empanuladoele;
$clempelemento   = new cl_empelemento;
$clorcdotacao    = new cl_orcdotacao;
$clempautoriza   = new cl_empautoriza;
$clempparametro  = new cl_empparametro;
$oDaoEmpenhoNl   = new cl_empempenhonl;   // Comentado para a Tarefa 16697

//lançamentos
include(modification("classes/db_conlancam_classe.php"));
include(modification("classes/db_conlancamele_classe.php"));
include(modification("classes/db_conlancamcompl_classe.php"));
include(modification("classes/db_conlancamlr_classe.php"));
include(modification("classes/db_conlancamcgm_classe.php"));
include(modification("classes/db_conlancamemp_classe.php"));
include(modification("classes/db_conlancamval_classe.php"));
include(modification("classes/db_conlancamdot_classe.php"));
include(modification("classes/db_conlancamdoc_classe.php"));
include(modification("classes/db_conplanoreduz_classe.php"));

$clconlancam      = new cl_conlancam;
$clconlancamele   = new cl_conlancamele;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamlr    = new cl_conlancamlr;
$clconlancamcgm   = new cl_conlancamcgm;
$clconlancamemp   = new cl_conlancamemp;
$clconlancamval   = new cl_conlancamval;
$clconlancamdot   = new cl_conlancamdot;
$clconlancamdoc   = new cl_conlancamdoc;
$clconplanoreduz  = new cl_conplanoreduz;

include(modification("libs/db_libcontabilidade.php"));
//retorna os arrays de lancamento...
$cltranslan = new cl_translan;
include(modification("classes/db_pcparam_classe.php"));
include(modification("classes/db_empautitem_classe.php"));
include(modification("classes/db_empempaut_classe.php"));
include(modification("classes/db_solicitemprot_classe.php"));
include(modification("classes/db_solandam_classe.php"));
include(modification("classes/db_solandpadraodepto_classe.php"));
include(modification("classes/db_proctransfer_classe.php"));
include(modification("classes/db_proctransferproc_classe.php"));
include(modification("classes/db_protprocesso_classe.php"));
include(modification("classes/db_solordemtransf_classe.php"));

$clpcparam           = new cl_pcparam;
$clempautitem        = new cl_empautitem;
$clsolicitemprot     = new cl_solicitemprot;
$clsolandam          = new cl_solandam;
$clsolandpadraodepto = new cl_solandpadraodepto;
$clproctransfer      = new cl_proctransfer;
$clproctransferproc  = new cl_proctransferproc;
$clprotprocesso      = new cl_protprocesso;
$clsolordemtransf    = new cl_solordemtransf;

include(modification("classes/db_matordemitem_classe.php"));
$clmatordemitem = new cl_matordemitem;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 22;
$db_botao = false;

// Parametro para verificar ou nao ordem de compra vinculado ao empenho, se 0 nao verificar se 1 verifica.
$res_parametros = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_verificarmatordem"));

if ($clempparametro->numrows > 0){
     db_fieldsmemory($res_parametros,0);
}

if (isset ($confirmar) || isset ($confirmarn)) {
	$sqlerro  = false;
  $erro_msg = "";

	db_query('begin');
	$result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$e60_coddot", db_getsession("DB_anousu"));
	@ db_fieldsmemory($result, 0); // carlos, colocado @ devido a erro
	db_query('rollback');

	db_inicio_transacao();

	$sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = ".db_getsession("DB_anousu");
	$res = db_query($sql);

  //---- verifica se tem valor pra anular
	//sleep(10);
	$result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp));
	db_fieldsmemory($result, 0);
	$valoranular = $e60_vlremp - $e60_vlrliq - $e60_vlranu;

	// echo "<br><br>disponivel: ".$valoranular."<br>digitado : ".$vlranu;

	$valoranular = number_format($valoranular, "2", ".", "");
	if ($vlranu > $valoranular) {
		$erro_msg = "Err 014 -Não existe valor disponivel para anular. Verifique!";
		$sqlerro = true;
	}
	// --------------------------------------------------

    if($sqlerro==false){

      // este teste verifica se poderá ser feito lancamento na data e se tem saldo no empenho

      if($e60_anousu < db_getsession("DB_anousu"))
        $codteste = "32";
      else
        $codteste = "2";

      $sql = "select fc_verifica_lancamento(".$e60_numemp.",'".date("Y-m-d",db_getsession("DB_datausu"))."',".$codteste.",".$valoranular.")";

      $result_erro = db_query($sql);

      //db_criatabela($result_erro);exit;

      $erro_msg = pg_result($result_erro,0,0);

      if(substr($erro_msg,0,2) > 0 ){

        $erro_msg = substr($erro_msg,3);
        $sqlerro = true;

      }

    }
    if ($sqlerro == false){

    // db_inicio_transacao();

    $result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
	db_fieldsmemory($result_conand, 0);
	if (isset ($pc30_contrandsol) && $pc30_contrandsol == 't') {
	    $result_itens_transf=$clempautitem->sql_record($clempautitem->sql_query_anuaut(null,null," distinct pc11_codigo as cod_item",null,"e54_anulad is null and e61_numemp = $e60_numemp"));
	    $numrows_itens_transf=$clempautitem->numrows;
	    if ($numrows_itens_transf>0){
	    	db_fieldsmemory($result_itens_transf,0);
	    	$result_ond = $clsolandam->sql_record($clsolandam->sql_query_andpad(null,"*",null,"pc43_solicitem = $cod_item and pc47_pctipoandam=6"));
	    	$where_sol="";
	    	if ($clsolandam->numrows>0){
	    		$where_sol=" pc47_pctipoandam=5 ";
	    	}else{
	    		$where_sol=" pc47_pctipoandam=3 ";
	    	}
	    	$result_dest_ord = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem=$cod_item and $where_sol"));
	    	if ($clsolandpadraodepto->numrows>0){
	    		db_fieldsmemory($result_dest_ord,0);
	    		$depto_dest = $pc48_depto;
	    		$ordem = $pc47_ordem;
	    		$clproctransfer->p62_hora = db_hora();
				$clproctransfer->p62_dttran = date("Y-m-d", db_getsession("DB_datausu"));
				$clproctransfer->p62_id_usuario = db_getsession("DB_id_usuario");
				$clproctransfer->p62_coddepto = db_getsession("DB_coddepto");
				$clproctransfer->p62_coddeptorec = $depto_dest;
				$clproctransfer->p62_id_usorec = '0';
				$clproctransfer->incluir(null);
				$codtran=$clproctransfer->p62_codtran;
				//db_msgbox("proctransfer");
				if ($clproctransfer->erro_status == 0) {
					$sqlerro = true;
					$erro_msg=$clproctransfer->erro_msg;
				}
	    	}
	    }
	    if (isset($codtran)&&$codtran!=""){
	    for($w=0;$w<$numrows_itens_transf;$w++){
	    	db_fieldsmemory($result_itens_transf,$w);
	    	if ($sqlerro == false) {
			   $result_proc=$clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($cod_item));
			   if ($clsolicitemprot->numrows>0){
				   db_fieldsmemory($result_proc,0);
	               $clproctransferproc->incluir($codtran,$pc49_protprocesso);
	              // db_msgbox("proctransferproc");
	               if ($clproctransferproc->erro_status==0){
	                   	$sqlerro=true;
	                   	$erro_msg=$clproctransferproc->erro_msg;
	                  	break;
	                }
	                if ($sqlerro == false) {
	                    $clprotprocesso->p58_codproc= $pc49_protprocesso;
	                    $clprotprocesso->p58_despacho="Empenho Anulado!!";
	                    $clprotprocesso->alterar($pc49_protprocesso);
	                //    db_msgbox("protprocesso");
	                   	if ($clprotprocesso->erro_status==0){
	                   	  	$sqlerro=true;
	                   	  	$erro_msg=$clprotprocesso->erro_msg;
	                   		break;
	              	    }
	           	    }
				}
				if ($sqlerro == false) {
					$clsolordemtransf->pc41_solicitem=$cod_item;
					$clsolordemtransf->pc41_codtran=$codtran;
					$clsolordemtransf->pc41_ordem=$ordem;
					$clsolordemtransf->incluir(null);
					//db_msgbox("solordemtransf");
					if($clsolordemtransf->erro_status==0){
						$sqlerro=true;
						$erro_msg=$clsolordemtransf->erro_msg;
					}
				}
			}
	    }
	}
	}
	/*
	if ($sqlerro==true){
		db_msgbox("Erro!!");
	}else{
		db_msgbox("ok!!");
	}
	exit;
	*/
	// db_fim_transacao($sqlerro);
	//exit;
    }

	if ($sqlerro == false) {
		$clempempenho->e60_vlranu = $e60_vlranu + $vlranu;
		$clempempenho->e60_numemp = $e60_numemp;
		$clempempenho->alterar($e60_numemp);
		if ($clempempenho->erro_status == 0) {
			$sqlerro = true;
			$erro_msg = $clempempenho->erro_msg;
		} else {
			$ok_msg = $clempempenho->erro_msg;
		}
	}

	// --------------------------------------------------
	//---------------------------------------------------
	//empanulado
	if ($sqlerro == false) {
		$clempanulado->e94_numemp = $e60_numemp;
		$clempanulado->e94_valor = $vlranu;
		$clempanulado->e94_saldoant = $vlranu; // carlos atualizado
		$clempanulado->e94_motivo = $c72_complem;
		$clempanulado->e94_empanuladotipo = $e94_empanuladotipo;
		$clempanulado->e94_data = date("Y-m-d", db_getsession("DB_datausu"));
		$clempanulado->incluir(null);
		$erro_msg = $clempanulado->erro_msg;
		if ($clempanulado->erro_status == 0) {
			$sqlerro = true;
		} else {
			$e95_codanu = $clempanulado->e94_codanu;
		}
	}

	//---------------------------------------------------------
	//array que irá armazenar os valores de cada elemento para fazer os lancamentos contabeis
	$arr_codeleval = array ();

	$arr_dados = split("#", $dados);
	$tam = count($arr_dados);
	if ($sqlerro == false) {
		for ($i = 0; $i < $tam; $i ++) {
			if ($sqlerro == true) {
				break;
			}
			$arr_ele = split("-", $arr_dados[$i]);
			$elemento = $arr_ele[0];
			$vdigitado = $arr_ele[1];

			//array utilizado nos lancamento contabeis
			$arr_codeleval[$elemento] = $vdigitado;

			//rotina que pega os valores do empelemento
			$result09 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, $elemento, "e64_vlranu,e64_vlremp"));
			db_fieldsmemory($result09, 0);

			$tot = $e64_vlranu + $vdigitado ;
			if (bccomp($tot,$e64_vlremp)>0){ // if $tot > $e64_vlremp
				$sqlerro = true;
				$erro_msg = "($tot ,$e64_vlremp) Não pode anular o valor digitado para o elemento $elemento do empenho. Verifique!";
				break;
			}
			if ($sqlerro == false) {
				$clempelemento->e64_numemp = $e60_numemp;
				$clempelemento->e64_codele = $elemento;
				$clempelemento->e64_vlranu = "$tot";
				$clempelemento->alterar($e60_numemp, $elemento);
				$erro_msg = $clempelemento->erro_msg;
				if ($clempelemento->erro_status == 0) {
					$sqlerro = true;
				}
			}

			if ($sqlerro == false) {
				$clempanuladoele->e95_codanu = $e95_codanu;
				$clempanuladoele->e95_codele = $elemento;
				$clempanuladoele->e95_valor = $vdigitado;
				$clempanuladoele->incluir($e95_codanu);
				$erro_msg = $clempanuladoele->erro_msg;

				if ($clempanuladoele->erro_status == 0) {
					$sqlerro = true;
					break;

				}
			}

		}
	} // end sqlerro
	// $sqlerro=true; // simula erro
	//rotina que testa se é resto à pagar, se for entra na condição a baixo
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////LANÇAMENTO CONTÁBIL//////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//==============================================================================================//
	//			rotina que verifica os elementos estam incluidos na tabela conparlancam      //
	//==============================================================================================//

	if ($sqlerro == false) {
		$result09 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_codele,o56_elemento,e64_vlranu"));
		$numrows09 = $clempelemento->numrows;
	}

	//===============================================================================================//

	if ($sqlerro == false) {
		for ($i = 0; $i < $numrows09; $i ++) {
			db_fieldsmemory($result09, $i); //pegas os dados do empelemento

			$valor_anular = $arr_codeleval["$e64_codele"];

			if ($valor_anular == 0) {
				continue;
			}
			if ($sqlerro == false) {
				// $valor_anular = $vlranu; // se deixar isto aqui, vai pegar o valor total da anulação, e não o valor anulado dos elementos
				$anousu = db_getsession("DB_anousu");
				$datausu = date("Y-m-d", db_getsession("DB_datausu"));
				if ($e60_anousu == $anousu) {
					$c71_coddoc = '2';
				} else {
					$c71_coddoc = '32';
				}
				if ($e60_anousu == db_getsession("DB_anousu")) {
					/*orcdotacaoval*/
					if ($sqlerro == false) {
						$result85 = db_query("select fc_lancam_dotacao($e60_coddot,'$datausu',$c71_coddoc,'$valor_anular') as dotacao");
						db_fieldsmemory($result85, 0);
						if (substr($dotacao, 0, 1) == 0) { //quando o primeiro caractere for igual a zero eh porque deu erro
							$sqlerro = true;
							$erro_msg = "Erro na atualização do orçamento \\n ".substr($dotacao, 1);
						}
					}
					/*fim-orcdotacaoval*/
				}
			}

			/*conlancam*/
			// $clconlancam->c70_codlan     =
			if ($sqlerro == false) {
				$clconlancam->c70_anousu = $anousu;
				$clconlancam->c70_data = $datausu;
				$clconlancam->c70_valor = $valor_anular;
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
			/*fim-conlancam/
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
			/*conlancamcompl*/
			if ($sqlerro == false) {
				if ($c72_complem == '') {
					$c72_complem = 'Anulação de empenho';
				}
				$clconlancamcompl->c72_codlan = $c70_codlan;
				$clconlancamcompl->c72_complem = $c72_complem;
				$clconlancamcompl->incluir($c70_codlan);
				$erro_msg = $clconlancamcompl->erro_msg;
				if ($clconlancamcompl->erro_status == 0) {
					$sqlerro = true;
				}
			}
			/*fim-conlancamcompl*/

			/*conlancamemp*/
			if ($sqlerro == false) {
				$clconlancamemp->c75_data = $datausu;
				$clconlancamemp->c75_codlan = $c70_codlan;
				$clconlancamemp->c75_numemp = $e60_numemp;
				$clconlancamemp->incluir($c70_codlan);
				$erro_msg = $clconlancamemp->erro_msg;
				if ($clconlancamemp->erro_status == 0) {
					$sqlerro = true;
				}
			}
			/*fim-conlancamemp*/

			/*inicio-conlancamdot*/
			if ($e60_anousu == db_getsession("DB_anousu")) {
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
			}
			/*fim-conlancamdot*/

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
				/*inicio-conlancamval*/

				if ($e60_anousu < db_getsession("DB_anousu")) {
					/* if($e60_anousu ==  db_getsession("DB_anousu")){ */
					$cltranslan->db_trans_estorna_empenho_resto($e60_codcom, $e60_anousu, $e60_numemp);
				} else {
					$cltranslan->db_trans_estorna_empenho($e60_codcom, $e60_anousu);
				}

				$arr_debito = $cltranslan->arr_debito;
				$arr_credito = $cltranslan->arr_credito;
				$arr_histori = $cltranslan->arr_histori;
				$arr_seqtranslr = $cltranslan->arr_seqtranslr;

				/*
				   print_r($arr_debito);
				   print_r($arr_credito);
				   print_r($arr_histori);
				   die();
				     */

				for ($t = 0; $t < count($arr_credito); $t ++) {
					//rotina que teste se a conta reduzida foi incluida no conplanoreduz
					$clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null, 'c61_codcon', '', "c61_anousu = ".DB_getsession("DB_anousu")." and c61_reduz=".$arr_debito[$t]));
					if ($clconplanoreduz->numrows == 0) {
						$sqlerro = true;
						$erro_msg = "Conta ".$arr_debito[$t]." não dísponivel para o exercicio!";

					}
					$clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null, 'c61_codcon', '', "c61_anousu = ".DB_getsession("DB_anousu")." and c61_reduz=".$arr_credito[$t]));
					if ($clconplanoreduz->numrows == 0) {
						$sqlerro = true;
						$erro_msg = "Conta ".$arr_credito[$t]." não dísponivel para o exercicio!";

					}
					//final

					if ($sqlerro == false) {
						$clconlancamval->c69_codlan = $c70_codlan;
						$clconlancamval->c69_credito = $arr_credito[$t];
						$clconlancamval->c69_debito = $arr_debito[$t];
						$clconlancamval->c69_codhist = $arr_histori[$t];
						$clconlancamval->c69_valor = $valor_anular;
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

				}
			}
		}
	}
	/*fim-conlancamval*/
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//FINAL LANÇAMENTO CONTÁBEIS////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// $sqlerro=true; //sumula erro

  // Rotina para recriar reserva da solicitacao de compras
  if ($sqlerro == false){

       $autori          = 0;
       $clempempaut     = new cl_empempaut;
       $result_itemsol  = $clempempaut->sql_record($clempempaut->sql_query_file($e60_numemp,"distinct e61_autori as autori"));
       $numrows_itemsol = $clempempaut->numrows;
       if ($numrows_itemsol > 0){
            db_fieldsmemory($result_itemsol,0);
       }

//       echo $vlranu." => ".$valoranular."<br><br>";
       // Se nao for RP e valor anulado nao for parcial e tiver solicitacao de compras
       if($e60_anousu >= db_getsession("DB_anousu")    &&
          ($e60_vlremp - ($vlranu + $e60_vlranu ) == 0) &&
          $autori     > 0){

	         $result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$e60_coddot", db_getsession("DB_anousu"));
           @db_fieldsmemory($result, 0);

           $saldo = (0 + $atual_menos_reservado);
           $saldo = trim(str_replace(".","",db_formatar($saldo,"f")));
           $vetor_dotacao[$e60_coddot] = str_replace(",",".",$saldo);

           if (isset($reservar) && $reservar == "true"){
                $flag_reservar = true;
           } else {
              $flag_reservar = false;
           }

           $clempautoriza->sql_anulaautorizacao($autori,false, $erro_msg, $sqlerro, $flag_saldo,$vetor_dotacao,$flag_reservar);
       }
  }

   //$sqlerro = true;
   db_fim_transacao($sqlerro);
//   exit;

   if ($sqlerro == false){
     if (!isset($imprimir)){
       unset($e60_numemp);
     }
  }
}

if (isset ($e60_numemp)) {

  $db_opcao = 2;
  $db_botao = true;
  //rotina que traz os dados de empempenho
  $result = $clempempenho->sql_record($clempempenho->sql_query($e60_numemp));
  db_fieldsmemory($result, 0, true);

  // verificamos o exercicio do empenho
  if ($e60_anousu < db_getsession("DB_anousu")) {

  	  // este fonte é somente pra anulação de empenho
  	  db_msgbox("Para anular um RP, use a tela de Anulação de Restos a Pagar ! ");
  	  db_redireciona("emp1_empanulacao001.php");

      $sqlerro = true;
  }

  if (isset($e30_verificarmatordem) && $e30_verificarmatordem == 1){
       $result_test_ordem_compra = $clmatordemitem->sql_record($clmatordemitem->sql_query_anulado(null,"*",null," m53_codordem is null and m52_numemp = $e60_numemp "));
       if ($clmatordemitem->numrows>0) {


            db_msgbox("Não é possivel anular empenho com ordem de compra!!");
            db_redireciona("emp1_empanulacao001.php");
            $sqlerro = true;
       }
  }

  $result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
  if ($clpcparam->numrows == 0) {
  	db_msgbox("Nenhum parâmetro configurado no módulo Compras para esta Instituição!\\nPara configurar acessar: Mód. Compras >> Procedimentos >> Parâmetros");
  	db_redireciona('emp1_empanulacao001.php');
  	return false;
  } else {
    db_fieldsmemory($result_conand, 0);
  }

  if (isset ($pc30_contrandsol) && $pc30_contrandsol == 't') {

				$result_testitem=$clempautitem->sql_record($clempautitem->sql_query_anuaut(null,null," distinct pc11_codigo as cod_item",null,"e54_anulad is null and e61_numemp = $e60_numemp"));
    		    if ($clempautitem->numrows>0){
    		        for($w=0;$w<pg_numrows($result_testitem);$w++){
		        	    db_fieldsmemory($result_testitem,$w);
					    $result_prot = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($cod_item));
					    if ($clsolicitemprot->numrows > 0) {
						     $result_andam = $clsolandam->sql_record($clsolandam->sql_query_file(null, "*", "pc43_codigo desc limit 1", "pc43_solicitem=$cod_item"));
						     if ($clsolandam->numrows > 0) {
							     db_fieldsmemory($result_andam, 0);
							    $result_tipo = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem=$cod_item and pc47_ordem=$pc43_ordem"));
							    if ($clsolandpadraodepto->numrows > 0) {
								     db_fieldsmemory($result_tipo, 0);
								     if ($pc47_pctipoandam != 7 || $pc48_depto != db_getsession("DB_coddepto")) {
									     $db_botao =false;
								     }// end if
							    }// end if
						     }//end if
					   }// end if
		            }// end for
			    }// end if
			}// end if


    // Comentado para a Tarefa 16697
			$rsNotaLiquidacao  = $oDaoEmpenhoNl->sql_record(
                           $oDaoEmpenhoNl->sql_query_file(null,"e68_numemp","","e68_numemp = {$e60_numemp}"));
      if ($oDaoEmpenhoNl->numrows > 0) {
         echo "<script>location.href='emp4_anularempenho001.php?numemp={$e60_numemp}';</script>";
      }


}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?


include(modification("forms/db_frmempanulacao.php"));
?>
    </center>
	</td>
  </tr>
</table>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if ($db_opcao == 22) {
	echo "<script>document.form1.pesquisar.click();</script>";
}
if (isset ($confirmar) || isset ($confirmarn)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
	} else {
		db_msgbox($ok_msg);
		if (isset ($imprimir)) {
			echo "
				     <script>
				       jan = window.open('emp2_anulemp002.php?e60_numemp=$e60_numemp','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
				       jan.moveTo(0,0);
				     </script>
			       ";
		}

		echo "<script>document.form1.pesquisar.click();</script>";

	}
}
?>