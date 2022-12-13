<?
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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_solicitem_classe.php");
include ("classes/db_solicitemele_classe.php");
include ("classes/db_pcorcamitem_classe.php");
include ("classes/db_pcorcamjulg_classe.php");
include ("classes/db_pcorcamval_classe.php");
include ("classes/db_pcorcam_classe.php");
include ("classes/db_orcreserva_classe.php");
include ("classes/db_orcreservasol_classe.php");
include ("classes/db_orcreservaaut_classe.php");
include ("classes/db_pcparam_classe.php");
include ("classes/db_pcdotac_classe.php");
include ("classes/db_empautoriza_classe.php");
include ("classes/db_empautitem_classe.php");
include ("classes/db_empautidot_classe.php");
include ("classes/db_pcprocitem_classe.php");
include ("classes/db_pcproc_classe.php");
include ("classes/db_pcsubgrupo_classe.php");
include ("classes/db_solandam_classe.php");
include ("classes/db_solandamand_classe.php");
include ("classes/db_solandpadraodepto_classe.php");
include ("classes/db_solicitemprot_classe.php");
include ("classes/db_proctransfer_classe.php");
include ("classes/db_proctransferproc_classe.php");
include ("classes/db_proctransand_classe.php");
include ("classes/db_procandam_classe.php");
include ("classes/db_protprocesso_classe.php");
include ("classes/db_solordemtransf_classe.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clproctransfer = new cl_proctransfer;
$clproctransferproc = new cl_proctransferproc;
$clproctransand = new cl_proctransand;
$clsolicitemprot = new cl_solicitemprot;
$clprocandam = new cl_procandam;
$clprotprocesso = new cl_protprocesso;
$clsolicitem = new cl_solicitem;
$clsolicitemele = new cl_solicitemele;
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamjulg = new cl_pcorcamjulg;
$clpcorcamval = new cl_pcorcamval;
$clpcorcam = new cl_pcorcam;
$clorcreserva = new cl_orcreserva;
$clorcreservasol = new cl_orcreservasol;
$clorcreservaaut = new cl_orcreservaaut;
$clempautoriza = new cl_empautoriza;
$clempautitem = new cl_empautitem;
$clempautidot = new cl_empautidot;
$clpcparam = new cl_pcparam;
$clpcdotac = new cl_pcdotac;
$clpcprocitem = new cl_pcprocitem;
$clpcproc = new cl_pcproc;
$clpcsubgrupo = new cl_pcsubgrupo;
$clsolandam = new cl_solandam;
$clsolandamand = new cl_solandamand;
$clsolandpadraodepto = new cl_solandpadraodepto;
$clsolordemtransf = new cl_solordemtransf;
$clrotulo = new rotulocampo;
$clrotulo->label("pc80_codproc");
$clrotulo->label("pc80_resumo");
$clrotulo->label("e54_codcom");
$clrotulo->label("e54_codtipo");
$clrotulo->label("e54_destin");
$clrotulo->label("e54_numerl");
$clrotulo->label("e54_tipol");
$clrotulo->label("pc23_valor");
$db_opcao = 1;
$db_botao = true;
$gerautori = "";
if (isset ($incluir)) {
	$gerautori = "";
	$valor = split(",", $valores);
	// arrays para dados do empautoriza
	$arr_vals = Array ();
	$arr_cgms = Array ();
	$arr_help = Array ();
	$indexaut = 0;

	// arrays para dados do empautitem
	$arr_proc = Array ();
	$arr_hell = Array ();
	$indexitm = 0;
	$vir = "";

	db_inicio_transacao();
	$clpcdotac->sql_record("update empparametro set e39_anousu = e39_anousu where e39_anousu =".db_getsession("DB_anousu"));

	$diferenca = Array ();
	$difindex = Array ();
	$iindexdif = 0;
	for ($i = 0; $i < sizeof($valor); $i ++) {
		$sqlerro = false;
		$e54_login = db_getsession("DB_id_usuario");
		$e54_anousu = db_getsession("DB_anousu");
		$e54_emiss = date("Y-m-d", db_getsession("DB_datausu"));
		$e54_instit = db_getsession("DB_instit");

		$splitei = split("_", $valor[$i]);
		$pc81_codprocitem = $splitei[2];
		$pc22_orcamitem = $splitei[3];
		$pc23_orcamforne = $splitei[4];
		$pc13_coddot = $splitei[5];
		$result_cgmvalor = $clpcorcamval->sql_record($clpcorcamval->sql_query($pc23_orcamforne, $pc22_orcamitem, "z01_numcgm,pc23_valor,pc23_vlrun,pc23_quant,pc23_obs"));
		if ($clpcorcamval->numrows > 0) {
			db_fieldsmemory($result_cgmvalor, 0);
			$altcoddot = "false";
			$valres = "";

			$result_pcorcamitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterproc($pc22_orcamitem, "pc11_codigo"));
			if ($clpcorcamitem->numrows > 0) {
				db_fieldsmemory($result_pcorcamitem, 0);
			}

			$result_dotac = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc11_codigo, null, $pc13_coddot, "pc13_quant,pc13_valor"));
			if ($clpcdotac->numrows > 0) {
				db_fieldsmemory($result_dotac, 0);
			}

			$result_altext = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null, null, "o80_codres,o80_valor", "", "o80_coddot = $pc13_coddot and o82_solicitem = $pc11_codigo"));

			if (!isset ($diferenca[$pc13_coddot])) {
				$diferenca[$pc13_coddot] = 0;
				$difindex[$iindexdif] = $pc13_coddot;
				$iindexdif ++;
			}

			if ($clorcreservasol->numrows > 0) {
				db_fieldsmemory($result_altext, 0);
				$altcoddot = "true";
				$valres = $o80_codres;
				/*
				$pc23_valor = str_replace(".","",$pc23_valor);
				$pc23_valor = str_replace(",",".",$pc23_valor);
				$pc23_quant = str_replace(".","",$pc23_quant);
				$pc23_quant = str_replace(",",".",$pc23_quant);
				$pc13_quant = str_replace(".","",$pc13_quant);
				$pc13_quant = str_replace(",",".",$pc13_quant);
				*/
				if ($o80_valor != (($pc23_valor / $pc23_quant) * $pc13_quant)) {
					$diferenca[$pc13_coddot] += ((($pc23_valor / $pc23_quant) * $pc13_quant) - $o80_valor);
					//	  $valres = 
				}
			} else {
				$diferenca[$pc13_coddot] += (($pc23_valor / $pc23_quant) * $pc13_quant);
			}

			if (!isset ($arr_vals[$splitei[1]])) {
				$arr_vals[$splitei[1]] = 0;
				$arr_help[$indexaut] = $splitei[1];
				$indexaut ++;
			}
			$arr_vals[$splitei[1]] = $arr_vals[$splitei[1]] + (($pc23_valor / $pc23_quant) * $pc13_quant);
			$arr_cgms[$splitei[1]] = $z01_numcgm;
			if (!isset ($arr_proc[$splitei[1]])) {
				$arr_proc[$splitei[1]] = "";
				$vir = "";
			}
			$arr_proc[$splitei[1]] .= $vir.$pc81_codprocitem.'_'.$pc13_quant.'_'. (($pc23_valor / $pc23_quant) * $pc13_quant).'_'.$pc13_coddot.'_'.$altcoddot.'_'.$valres.'_'.$pc23_vlrun;
			$vir = ",";
		}
	}

	for ($i = 0; $i < sizeof($diferenca); $i ++) {
		//====================================================//
		//rotina que verifica se ainda existe saldo disponivel//
		//=========rotina para calcular o saldo final=========//
		$result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=".$difindex[$i], db_getsession("DB_anousu"));
		db_fieldsmemory($result, 0);
		
		$verificarsaldo = (0 + $atual_menos_reservado);
		$dif = $diferenca[$difindex[$i]];
		if ($verificarsaldo < $dif) {
			$erro_msg = "Usuário:\\n\\nDotação (".$difindex[$i].") sem saldo. Reserva não gerada. \\nAutorizações não geradas\\n\\nAministrador:";
			$sqlerro = true;
			break;
		}
	}

	$contaautori = 0;
	$soma_e55_valor = 0;
	if ($sqlerro == false) {
		for ($i = 0; $i < sizeof($arr_vals); $i ++) {
			$e54_numcgm = $arr_cgms[$arr_help[$i]];
			$e54_valor = $arr_vals[$arr_help[$i]];
			$clempautoriza->e54_numcgm = $e54_numcgm;
			$clempautoriza->e54_login = $e54_login;
			$clempautoriza->e54_codcom = $e54_codcom;
			$clempautoriza->e54_destin = $e54_destin;
			$clempautoriza->e54_valor = $e54_valor;
			$clempautoriza->e54_anousu = $e54_anousu;
			$clempautoriza->e54_tipol = $e54_tipol;
			$clempautoriza->e54_numerl = $e54_numerl;
			$clempautoriza->e54_praent = " ";
			$clempautoriza->e54_entpar = " ";
			$clempautoriza->e54_conpag = " ";
			$clempautoriza->e54_codout = " ";
			$clempautoriza->e54_contat = " ";
			$clempautoriza->e54_telef = " ";
			$clempautoriza->e54_numsol = 0;
			$clempautoriza->e54_anulad = "null";
			$clempautoriza->e54_emiss = $e54_emiss;
			$clempautoriza->e54_resumo = $pc80_resumo;
			$clempautoriza->e54_codtipo = $e54_codtipo;
			$clempautoriza->e54_instit = $e54_instit;
			$clempautoriza->e54_depto = db_getsession("DB_coddepto");
			$clempautoriza->incluir(null);
			$e54_autori = $clempautoriza->e54_autori;
			$erro_msg = $clempautoriza->erro_msg;
			if ($clempautoriza->erro_status == 0) {
				$sqlerro = true;
				break;
			}
			if ($sqlerro == false) {
				$arr_item = split(",", $arr_proc[$arr_help[$i]]);
				for ($iii = 0; $iii < sizeof($arr_item); $iii ++) {
					$arr_daditem = split("_", $arr_item[$iii]);
					$e55_sequen = $arr_daditem[0];
					$e55_quant = $arr_daditem[1];
					$e55_vltot = $arr_daditem[2];
					$dotacao = $arr_daditem[3];
					$altcoddot = $arr_daditem[4];
					$valres = $arr_daditem[5];
					$valorunitarioautitem = $arr_daditem[6];
					$result_mater = $clpcprocitem->sql_record($clpcprocitem->sql_query_pcmater($e55_sequen, "pc01_codmater as e55_item,pc11_resum as e55_descr,pc11_codigo as codigo,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant"));
					db_fieldsmemory($result_mater, 0);

					$result_elemen = $clsolicitemele->sql_record($clsolicitemele->sql_query_file($codigo, null, "pc18_codele as e55_codele"));
					if ($clsolicitemele->numrows > 0) {
						db_fieldsmemory($result_elemen, 0);
					} else {
						$erro_msg = "Usuário: \\n\\nItem ($e55_sequen) sem elemento. \\nContate o suporte.\\n\\nAdministrador:";
						$sqlerro = true;
						break;
					}

					if ($sqlerro == false) {
						$result_anousu = $clpcdotac->sql_record($clpcdotac->sql_query_file($codigo, null, $dotacao, "pc13_anousu as e56_anousu"));
						db_fieldsmemory($result_anousu, 0);

						$clempautitem->e55_autori = $e54_autori;
						$clempautitem->e55_item = $e55_item;
						$clempautitem->e55_sequen = $e55_sequen;
						$clempautitem->e55_quant = $e55_quant;
						$clempautitem->e55_vltot = $e55_vltot;
						// db_msgbox($valorunitarioautitem);
						$clempautitem->e55_vlrun = $valorunitarioautitem;

						if ((isset ($pc01_servico) && (trim($pc01_servico) == "f" || trim($pc01_servico) == "")) || !isset ($pc01_servico)) {
							$unid = trim(substr($m61_descr, 0, 10));
							if ($m61_usaquant == "t") {
								$unid .= " ($pc17_quant UNIDADES)";
							}
							$e55_descr = AddSlashes($unid."\n".$e55_descr);
						} else {
							$unid = "SERVIÇO";
							$e55_descr = AddSlashes($unid."\n".$e55_descr);
						}

						$result_pcorcamvalitem = $clpcorcam->sql_record($clpcorcam->sql_query_vallancados(null, "pc23_obs", "", "pc31_pcprocitem=$e55_sequen and pc24_pontuacao=1"));
						if ($clpcorcam->numrows > 0) {
							db_fieldsmemory($result_pcorcamvalitem, 0);
							if (trim($pc23_obs) != "") {
								$e55_descr .= "\nOBS.: ".$pc23_obs;
							}
						}
						$clempautitem->e55_descr = $e55_descr;
						$clempautitem->e55_codele = $e55_codele;
						$clempautitem->incluir($e54_autori, $e55_sequen);
						$soma_e55_valor += $e55_vltot;
						if ($clempautitem->erro_status == 0) {
							$erro_msg = $clempautitem->erro_msg;
							$sqlerro = true;
							break;
						}
						if ($sqlerro == false) {
							if ($altcoddot == "true") {
								$clorcreservasol->excluir($valres, $codigo);
								if ($clorcreservasol->erro_status == 0) {
									$erro_msg = $clorcreservasol->erro_msg;
									$sqlerro = true;
									break;
								}
								$clorcreserva->excluir($valres);
								if ($clorcreserva->erro_status == 0) {
									$erro_msg = $clorcreserva->erro_msg;
									$sqlerro = true;
									break;
								}
							}
						}
					}
					if ($sqlerro == false) {
						$result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
						db_fieldsmemory($result_conand, 0);

						if (isset ($pc30_contrandsol) && $pc30_contrandsol == 't') {
							//db_msgbox("Entrou");
							$result_proc = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($codigo));
							if ($clsolicitemprot->numrows > 0) {
								if ($iii == 0) {
									$result_proc = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($codigo));
									if ($clsolicitemprot->numrows > 0) {
										db_fieldsmemory($result_proc, 0);
									}
									$result_deptorec = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem = ".$codigo."   and pc47_pctipoandam = 4"));
									if ($clsolandpadraodepto->numrows > 0) {
										db_fieldsmemory($result_deptorec, 0);

										$clproctransfer->p62_hora = db_hora();
										$clproctransfer->p62_dttran = date("Y-m-d", db_getsession("DB_datausu"));
										$clproctransfer->p62_id_usuario = db_getsession("DB_id_usuario");
										$clproctransfer->p62_coddepto = db_getsession("DB_coddepto");
										$clproctransfer->p62_coddeptorec = $pc48_depto;
										$clproctransfer->p62_id_usorec = '0';
										$clproctransfer->incluir(null);
										$codtran = $clproctransfer->p62_codtran;
										if ($clproctransfer->erro_status == 0) {
											$sqlerro == true;
										} else {
											//db_msgbox("Inclui Transf $codtran");
										}
									}
								}
								if ($sqlerro == false) {
									$result_proc = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($codigo));
									if ($clsolicitemprot->numrows > 0) {
										db_fieldsmemory($result_proc, 0);
										$clproctransferproc->incluir($codtran, $pc49_protprocesso);
										if ($clproctransferproc->erro_status == 0) {
											$sqlerro = true;
											break;
										} else {
											//db_msgbox("Inclui Proctransferproc");
										}
									}
								}
								if ($sqlerro == false) {
									$clprocandam->p61_despacho = " ";
									$clprocandam->p61_publico = '0';
									$clprocandam->p61_codproc = $pc49_protprocesso;
									$data = date('Y-m-d');
									$hora = db_hora();
									$clprocandam->p61_dtandam = $data;
									$clprocandam->p61_hora = $hora;
									$clprocandam->p61_id_usuario = db_getsession("DB_id_usuario");
									$clprocandam->p61_coddepto = db_getsession("DB_coddepto");
									$clprocandam->incluir(null);
									if ($clprocandam->erro_status == "1") {
										$erro = 0;
										//db_msgbox("Inclui Procandam");
									} else {
										//$clprocandam->erro(true, false);
										$erro = 1;
										$sqlerro = true;
										break;
									}
								}
								if ($sqlerro == false) {
									//inclui a transferencia e o andamento do processo na tabela proctransand
									$clproctransand->p64_codtran = $codtran;
									$clproctransand->p64_codandam = $clprocandam->p61_codandam;
									$clproctransand->incluir(null);
									if ($clproctransand->erro_status == "1") {
										$erro = 0;
										//db_msgbox("Inclui Proctransand");							
									} else {
										//$clproctransand->erro(true, false);
										$erro = 1;
										$sqlerro = true;
										break;
									}
								}
								if ($sqlerro == false) {
									//atualiza codandam da tabela protprocesso; 
									$clprotprocesso->p58_codproc = $pc49_protprocesso;
									$clprotprocesso->p58_codandam = $clprocandam->p61_codandam;
									$clprotprocesso->p58_despacho = " ";
									$clprotprocesso->alterar($pc49_protprocesso);
									if ($clprotprocesso->erro_status == "1") {
										$erro = 0;
										//db_msgbox("Altera protprocesso");
									} else {
										//$clprotprocesso->erro(true, false);
										$sqlerro = true;
										$erro = 1;
										break;
									}
								}
								$result_depto = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem=$codigo and pc47_pctipoandam=4"));
								if ($clsolandpadraodepto->numrows > 0) {
									db_fieldsmemory($result_depto, 0);
									if ($sqlerro == false) {
										$clsolandam->pc43_depto = $pc48_depto;
										$clsolandam->pc43_ordem = $pc47_ordem;
										$clsolandam->pc43_solicitem = $pc49_solicitem;
										$clsolandam->incluir(null);
										if ($clsolandam->erro_status == 0) {
											$sqlerro = true;
											break;

										} else {
											//db_msgbox("inclui solandam ordem $pc47_ordem");
										}
									}
									if ($sqlerro == false) {
										$clsolandamand->pc42_codandam = $clprocandam->p61_codandam;
										$clsolandamand->incluir($clsolandam->pc43_codigo);
										if ($clsolandamand->erro_status == 0) {
											$sqlerro = true;
											break;
										} else {
											//db_msgbox("inclui solandamand");
										}
									}
								}
								if ($iii == 0) {
									$result_proc = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($codigo));
									if ($clsolicitemprot->numrows > 0) {
										db_fieldsmemory($result_proc, 0);
									}
									$result_deptorec = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem = ".$codigo."   and pc47_pctipoandam = 7"));
									if ($clsolandpadraodepto->numrows > 0) {
										db_fieldsmemory($result_deptorec, 0);
										$clproctransfer->p62_hora = db_hora();
										$clproctransfer->p62_dttran = date("Y-m-d", db_getsession("DB_datausu"));
										$clproctransfer->p62_id_usuario = db_getsession("DB_id_usuario");
										$clproctransfer->p62_coddepto = db_getsession("DB_coddepto");
										$clproctransfer->p62_coddeptorec = $pc48_depto;
										$clproctransfer->p62_id_usorec = '0';
										$clproctransfer->incluir(null);
										$codtran_transf = $clproctransfer->p62_codtran;
										if ($clproctransfer->erro_status == 0) {
											$sqlerro == true;
										} else {
											//db_msgbox("inclui Transf $codtran_transf depto $pc48_depto");
										}
									}
								}
								if ($sqlerro == false) {                                   
                                    $result_prox = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "pc47_ordem as ordem_prox", null, "pc47_solicitem = ".$codigo."   and pc47_pctipoandam = 7"));
                                    if ($clsolandpadraodepto->numrows > 0) {
                                       	db_fieldsmemory($result_prox,0);   
										$clsolordemtransf->pc41_solicitem = $codigo;
										$clsolordemtransf->pc41_codtran = $codtran_transf;
										$clsolordemtransf->pc41_ordem = $ordem_prox;
										$clsolordemtransf->incluir(null);
										if ($clsolordemtransf->erro_status == 0) {
											$sqlerro = true;
											$erro_msg = $clsolordemtransf->erro_msg;
										}
									}

								}
								if ($sqlerro == false) {
									$result_proc = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($codigo));
									if ($clsolicitemprot->numrows > 0) {
										db_fieldsmemory($result_proc, 0);
										$clproctransferproc->incluir($codtran_transf, $pc49_protprocesso);
										if ($clproctransferproc->erro_status == 0) {
											$sqlerro = true;
											break;
										} else {
											//db_msgbox("inclui proctransferproc");
										}
									}
								}
							}
						}
					}
				}
				if ($sqlerro == true) {
					break;
				}
				if ($sqlerro == false) {
					$clorcreserva->o80_anousu = db_getsession("DB_anousu");
					$clorcreserva->o80_coddot = $dotacao;
					$clorcreserva->o80_dtfim = date('Y', db_getsession('DB_datausu'))."-12-31";
					$clorcreserva->o80_dtini = date('Y-m-d', db_getsession('DB_datausu'));
					$clorcreserva->o80_dtlanc = date('Y-m-d', db_getsession('DB_datausu'));
					$clorcreserva->o80_valor = $soma_e55_valor;
					$clorcreserva->o80_descr = " ";
					$clorcreserva->incluir(null);
					$o80_codres = $clorcreserva->o80_codres;
					$soma_e55_valor = 0;
					if ($clorcreserva->erro_status == 0) {
						$sqlerro = true;
						$erro_msg = $clorcreserva->erro_msg;
						break;
					}
					if ($sqlerro == false) {
						$clorcreservaaut->o83_autori = $e54_autori;
						$clorcreservaaut->incluir($o80_codres);
						if ($clorcreservaaut->erro_status == 0) {
							$sqlerro = true;
							$erro_msg = $clorcreservaaut->erro_msg;
							break;
						} else {
							$arr_geraum[$o80_codres] = $e54_autori;
						}
					}
				}
				if ($sqlerro == false) {
					$clempautidot->e56_autori = $e54_autori;
					$clempautidot->e56_anousu = $e56_anousu;
					$clempautidot->e56_coddot = $dotacao;
					$clempautidot->incluir($e54_autori);
					if ($clempautidot->erro_status == 0) {
						$erro_msg = $clempautidot->erro_msg;
						$sqlerro = true;
						break;
					}
				}
			}
			if ($sqlerro == false) {
				$contaautori ++;
				if ($i == 0 || $i +1 == sizeof($arr_vals)) {
					if ($i == 0) {
						$gerautori .= "e54_autori_ini=$e54_autori";
					}
					if ($i +1 == sizeof($arr_vals)) {
						$gerautori .= "&e54_autori_fim=$e54_autori";
					}
				}
			}
		}
	}
	// $sqlerro=true;
	/*
	if ($sqlerro==true){
		db_msgbox("Erro!!");
	}else{
		db_msgbox("Certo!!");
	}
	exit;
	*/
	db_fim_transacao($sqlerro);
}
$numrows_itens = 0;
if (isset ($pc80_codproc) && trim($pc80_codproc) != "") {
	$result_itens = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_geraut(null, null, "distinct pc11_codigo,pc81_codprocitem,pc22_orcamitem,pc01_codmater,pc01_descrmater,pc13_coddot,pc13_codigo,z01_numcgm,z01_nome,pc23_orcamforne,pc23_valor,pc23_obs,pc23_vlrun,pc23_quant,pc13_quant,pc13_anousu,pc11_codigo,pc18_codele,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant", "z01_numcgm,pc13_coddot,pc18_codele,pc01_codmater,pc81_codprocitem", "pc81_codproc=$pc80_codproc and pc24_pontuacao=1 and pc10_instit=".db_getsession("DB_instit")));
	$numrows_itens = $clpcorcamjulg->numrows;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?



db_input('valores', 8, 0, true, 'hidden', 3);
$locationh = true;
if ($numrows_itens == 0) {
	$locationh = false;
	echo "                                                                                                                                                                                                                                                                                   <br><br><br><br><br><br><br>
		            <strong>Não existem itens, neste processo, para gerar autorização.</strong>\n
		            <script>
			      parent.document.form1.incluir.disabled=true;
		            </script>
		           ";
} else {
	echo "<center>";
	echo "<table border='1' align='center'>\n";
	echo "<tr>";
	echo "  <td colspan='12' align='center'>$Lpc80_codproc";
	echo "    ";
	db_input('pc80_codproc', 8, $Ipc80_codproc, true, 'text', 3);
	$result_resumo = $clpcproc->sql_record($clpcproc->sql_query_file($pc80_codproc, "pc80_resumo"));
	if ($clpcproc->numrows > 0) {
		db_fieldsmemory($result_resumo, 0);
	}
	echo "    ";
	db_input('pc80_resumo', 8, $Ipc80_resumo, true, 'hidden', 3);
	echo "    ";
	db_input('e54_codcom', 8, $Ie54_codcom, true, 'hidden', 3);
	echo "    ";
	db_input('e54_codtipo', 8, $Ie54_codtipo, true, 'hidden', 3);
	echo "    ";
	db_input('e54_destin', 8, $Ie54_destin, true, 'hidden', 3);
	echo "    ";
	db_input('e54_numerl', 8, $Ie54_numerl, true, 'hidden', 3);
	echo "    ";
	db_input('e54_tipol', 8, $Ie54_tipol, true, 'hidden', 3);
	echo "  </td>";
	echo "</tr>";
	echo "<tr bgcolor=''>\n";
	echo "  <td nowrap class='bordas02' align='center' title='Marcar todos os itens de todas autorizações'><strong>";
	db_ancora("M", "js_marcatudo();", 1);
	echo "</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Item</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Referência</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Material</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Descrição</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Obs</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Fornecedor</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Dotação</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Quant.</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Val Unit.</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Val Tot.</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Reserva</strong></td>\n";
	echo "</tr>\n";

	$dot_ant = "";
	$forn_ant = "";
	$codele_ant = "";
	$contador = 1;
	$testatot = 0;

	$saldodotacoes = Array ();
	$indexdotacoes = Array ();
	$indexsaldodotacoes = 0;
	$itenssemdotac = "";
	$vircodprocitem = "";
	for ($i = 0; $i < $numrows_itens; $i ++) {
		db_fieldsmemory($result_itens, $i);

		$passa = true;
		$e54_autori = 0;

		if (trim($pc13_coddot) == "")
			continue;

		$result_empautitem = $clempautitem->sql_record($clempautitem->sql_query_file(null, $pc81_codprocitem));
		for ($autitem = 0; $autitem < $clempautitem->numrows; $autitem ++) {
			db_fieldsmemory($result_empautitem, $autitem);
			$result_empautidot = $clempautidot->sql_record($clempautidot->sql_query_file("", "*", "", " e56_autori = $e55_autori and e56_coddot = $pc13_coddot and e56_anousu = $pc13_anousu"));
			if ($clempautidot->numrows > 0) {
				db_fieldsmemory($result_empautidot, 0);
				$result_empautoriza = $clempautoriza->sql_record($clempautoriza->sql_query_file($e56_autori));
				db_fieldsmemory($result_empautoriza, 0);
				if ($e54_anulad == "") {
					$passa = false;
				} else {
					$passa = true;
				}
			}

		}

		if ($passa == false)
			continue;

		if ($i < $numrows_itens -1) {
			$proxitem = pg_result($result_itens, $i +1, "pc81_codprocitem");
			$proxdotac = pg_result($result_itens, $i +1, "pc13_coddot");
			if ($proxitem == $pc81_codprocitem and $proxdotac == $pc13_coddot)
				continue;
		}
		$locationh = false;

		if (!isset ($saldodotacoes[$pc13_coddot])) {
			$saldodotacoes[$pc13_coddot] = 0;
		}

		$result_altexttest = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null, null, "o80_codres,o80_valor", "", "o80_coddot = $pc13_coddot and o82_solicitem = $pc13_codigo"));
		if ($clorcreservasol->numrows > 0) {
			db_fieldsmemory($result_altexttest, 0);
			$saldodotacoes[$pc13_coddot] += $o80_valor;
		}
	}
	for ($i = 0; $i < $numrows_itens; $i ++) {
		db_fieldsmemory($result_itens, $i);

		if (trim($pc13_coddot) == "")
			continue;

		$passa = true;
		$result_empautitem = $clempautitem->sql_record($clempautitem->sql_query_file(null, $pc81_codprocitem));
		for ($autitem = 0; $autitem < $clempautitem->numrows; $autitem ++) {
			db_fieldsmemory($result_empautitem, $autitem);

			//----------------Controla andamento da solicitação-------------
			//------------------------Rogerio--------------------------
			$result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
			db_fieldsmemory($result_conand, 0);
			if (isset ($pc30_contrandsol) && $pc30_contrandsol == 't') {
				$result_prot = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($pc11_codigo));
				if ($clsolicitemprot->numrows > 0) {
					$result_andam = $clsolandam->sql_record($clsolandam->sql_query_file(null, "*", "pc43_codigo desc limit 1", "pc43_solicitem=$pc11_codigo"));
					if ($clsolandam->numrows > 0) {
						db_fieldsmemory($result_andam, 0);
						$result_tipo = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem=$pc11_codigo and pc47_ordem=$pc43_ordem"));
						if ($clsolandpadraodepto->numrows > 0) {
							db_fieldsmemory($result_tipo, 0);
							if ($pc47_pctipoandam != 3 || $pc48_depto != db_getsession("DB_coddepto")) {
								$simnaod = " disabled ";
							}
						}
					}
				}
			}

			$result_empautidot = $clempautidot->sql_record($clempautidot->sql_query_file("", "*", "", " e56_autori = $e55_autori and e56_coddot = $pc13_coddot and e56_anousu = $pc13_anousu"));
			if ($clempautidot->numrows > 0) {
				db_fieldsmemory($result_empautidot, 0);
				$result_empautoriza = $clempautoriza->sql_record($clempautoriza->sql_query_file($e56_autori));
				db_fieldsmemory($result_empautoriza, 0);
				if ($e54_anulad == "") {
					$passa = false;
				} else {
					$passa = true;
				}
			}
		}

		if ($passa == false)
			continue;

		if ($i < $numrows_itens -1) {
			$proxitem = pg_result($result_itens, $i +1, "pc81_codprocitem");
			$proxdotac = pg_result($result_itens, $i +1, "pc13_coddot");
			if ($proxitem == $pc81_codprocitem and $proxdotac == $pc13_coddot)
				continue;
		}

		//====================================================//
		//rotina que verifica se ainda existe saldo disponivel//
		//=========rotina para calcular o saldo final=========//
		$result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$pc13_coddot", db_getsession("DB_anousu"));
		db_fieldsmemory($result, 0);
		
		$valdisp = 'valdisp_'.$contador;
		$$valdisp = (0 + $atual_menos_reservado) + $saldodotacoes[$pc13_coddot];
		$valtesta = $$valdisp;
		if ($dot_ant != $pc13_coddot) {
			$testatot = 0;
		}
		if ($dot_ant != $pc13_coddot || $forn_ant != $z01_numcgm || $pc18_codele != $codele_ant) {
			if ($contador != 1) {
				echo "<tr>\n";
				echo "  <td nowrap colspan='12'align='left'><strong>&nbsp;</strong></td>\n";
				echo "<tr>\n";
			}
			$$valdisp = db_formatar($$valdisp, "v");
			echo "</tr>\n";
			echo "  <td nowrap colspan='1' class='bordas' align='center' title='Marcar apenas itens da $contador&ordf; autorização'><strong>";
			db_ancora("A", "js_marcaautoriza('". ($contador)."');", 1);
			echo "</strong></td>\n";
			echo "  <td nowrap colspan='5' class='bordas' align='left'><strong>". ($contador)."&ordf; AUTORIZAÇÃO </strong></td>\n";
			echo "  <td nowrap colspan='6' class='bordas' align='left'><strong>Saldo disponível: </strong>";
			db_input('valdisp_'.$contador, 12, 0, true, 'text', 3);
			echo "</td>\n";
			echo "</tr>\n";
			$$valdisp = str_replace(".", "", $$valdisp);
			$$valdisp = str_replace(",", ".", $$valdisp);
			$dot_ant = $pc13_coddot;
			$forn_ant = $z01_numcgm;
			$codele_ant = $pc18_codele;
			$contador ++;
		}

		$simnao = "Não";
		$simnaod = "disabled";
		$bordas = "bordas01";
		if ($valtesta >= 0) {
			$result_altext = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null, null, "o80_codres,o80_valor", "", "o80_coddot = $pc13_coddot and o82_solicitem = $pc13_codigo"));
			$altcoddot = false;
			if ($clorcreservasol->numrows > 0) {
				db_fieldsmemory($result_altext, 0);
				if($pc01_servico == 'f'){
				  if($valtesta < (($pc23_valor / $pc23_quant) * $pc13_quant)) {
				    $testatot = (($pc23_valor / $pc23_quant) * $pc13_quant);
                                  }
				}else{
				  if($valtesta < (((100 * $pc23_valor / $pc13_valor) / $pc23_quant) * $pc13_quant)) {
				    $testatot = (((100 * $pc23_valor / $pc13_valor) / $pc23_quant) * $pc13_quant);
                                  }
				}
			} else {

				/////// isso é novo, caso der problema, comentar para testar
				if($pc01_servico == 'f'){
			          if($valtesta < (($pc23_valor / $pc23_quant) * $pc13_quant)) {
				    $testatot = ($pc23_valor / $pc23_quant) * $pc13_quant;
				  }
				}else{
			          if($valtesta < (((100 * $pc23_valor / $pc13_valor) / $pc23_quant) * $pc13_quant)) {
				    $testatot = ((100 * $pc23_valor / $pc13_valor) / $pc23_quant) * $pc13_quant;
				  }
				}
				/////////////////////////////////////////////////////////////////////

			}

			if ($valtesta >= $testatot) {
				$altcoddot = true;
				$simnao = "Sim";
				$simnaod = "";
				$bordas = "bordas";
			}
		}
		$result_itemsemdotac = $clsolicitem->sql_record("select pc11_codigo as pc11_codigo_testa,pc11_quant as pc11_quant_testa,sum(pc13_quant) as pc13_quant_testa from solicitem inner join pcdotac on pcdotac.pc13_codigo=solicitem.pc11_codigo where pc11_codigo=$pc11_codigo group by pc11_codigo,pc11_quant");
		if ($clsolicitem->numrows > 0) {
			db_fieldsmemory($result_itemsemdotac, 0);
			if ($pc11_quant_testa != $pc13_quant_testa) {
				$simnaod = "disabled";
				$bordas = "bordas01";
				if (strpos($itenssemdotac, "Item: ".$pc81_codprocitem." - Código na solicitação: ".$pc11_codigo) == "") {
					$itenssemdotac .= $vircodprocitem."Item: ".$pc81_codprocitem." - Código na solicitação: ".$pc11_codigo;
					$vircodprocitem = "\\n";
				}
			}
		} else {
			$simnaod = "disabled";
			$bordas = "bordas01";
			if (strpos($itenssemdotac, "Item: ".$pc81_codprocitem." - Código na solicitação: ".$pc11_codigo) == "") {
				$itenssemdotac .= $vircodprocitem."Item: ".$pc81_codprocitem." - Código na solicitação: ".$pc11_codigo;
				$vircodprocitem = "\\n";
			}
		}

		echo "<tr>\n";
		echo "  <td nowrap class='$bordas' align='center' ><input type='checkbox' name='aut_". ($contador -1)."_".$pc81_codprocitem."_".$pc22_orcamitem."_".$pc23_orcamforne."_".$pc13_coddot."' value='aut_". ($contador -1)."_".$pc81_codprocitem."_".$pc22_orcamitem."_".$pc23_orcamforne."_".$pc13_coddot."' $simnaod></td>\n";
		echo "  <td nowrap class='$bordas' align='center' >$pc81_codprocitem</td>\n";
		if ((isset ($pc01_servico) && (trim($pc01_servico) == "f" || trim($pc01_servico) == "")) || !isset ($pc01_servico)) {
			$unid = trim(substr($m61_descr, 0, 10));
			if ($m61_usaquant == "t") {
				$unid .= " <BR>($pc17_quant UNIDADES)";
			}
		} else {
			$unid = "SERVIÇO";
		}
		echo "  <td nowrap class='$bordas' align='center' >$unid</td>\n";
		echo "  <td nowrap class='$bordas' align='center' >$pc01_codmater</td>\n";
		echo "  <td class='$bordas' align='left' >".ucfirst(strtolower($pc01_descrmater))."</td>\n";
		echo "  <td class='$bordas' align='left' >".ucfirst(strtolower($pc23_obs))."&nbsp;</td>\n";
		echo "  <td class='$bordas' align='left' >$z01_nome</td>\n";
		echo "  <td nowrap class='$bordas' align='center' >$pc13_coddot</td>\n";
		echo "  <td nowrap class='$bordas' align='right'  >$pc13_quant</td>\n";
		echo "  <td nowrap class='$bordas' align='right'  >R$ ".$pc23_vlrun."</td>\n";
		if($pc01_servico == 'f'){
		  echo "  <td nowrap class='$bordas' align='center'  >R$ ".db_formatar($pc23_valor / $pc23_quant * $pc13_quant, "f")."</td>\n";
		}else{
		  echo "  <td nowrap class='$bordas' align='center'  >R$ ".db_formatar((100 * $pc23_valor / $pc13_valor) / $pc23_quant * $pc13_quant, "f")."</td>\n";
		}
		echo "  <td nowrap class='$bordas' align='center' ><strong>$simnao</strong</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</center>";
	echo "<script>
		             parent.document.form1.incluir.disabled=false;
		            </script>";
}
if (isset ($itenssemdotac) && trim($itenssemdotac) != "") {
	db_msgbox("Usuário: \\n\\nItens abaixo com quantidade total das dotações incorretos. Verifique!. \\n $itenssemdotac \\n\\nAdministrador:");
}
?>
    </center>
    </td>
  </tr>
</table>
</form>
<script>

function js_relatorio(){
  jan = window.open('emp2_emiteautori002.php?<?=$gerautori?>&instit=<?=db_getsession("DB_instit")?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_troca(codigo,orcamento,sol){
  top.corpo.document.location.href = 'com1_trocpcorcamtroca001.php?pc25_orcamitem='+codigo+'&orcamento='+orcamento+'&sol='+sol;
}
function js_unico(nome,campo,valor,TAB,dot){
  tcampo = campo.substr(0,campo.lastIndexOf("_"));
  vcampo = Number(eval("document.form1."+tcampo+".value"));
  if(vcampo>0){
    valor = Number(valor);
    if(eval("document.form1."+nome+".checked==true")){
      valorrest = vcampo-valor;
      if(valorrest>0){
	eval("document.form1."+tcampo+".value="+vcampo+"-"+valor);
      }
    }else if(eval("document.form1."+nome+".checked==false")){
      eval("document.form1."+tcampo+".value="+vcampo+"+"+valor);
    }
  }
  vcampo= Number(eval("document.form1."+tcampo+".value"));
  x = document.form1;
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=="text" && x.elements[i].name.search("pc23_valor")!=-1){
      b = x.elements[i-1].name.split("_");
      if(x.elements[i-1].checked==false){
        if(Number(dot)==Number(b[5])){
	  if(Number(x.elements[i].value)>Number(vcampo)){
	    x.elements[i-1].disabled = true;
	    x.elements[i+1].value    = "Não";
	  }else{
	    x.elements[i-1].disabled = false;
	    x.elements[i+1].value    = "Sim";
	  }
          if(eval('document.form1.valdisp_'+b[1]+'.value')!=vcampo){
            eval('document.form1.valdisp_'+b[1]+'.value ='+vcampo);
          }
        }
      }
    }
  }
}
function js_marcatudo(){
  x = document.form1;
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=='checkbox'){
      if(x.elements[i].disabled==false){
	if(x.elements[i].checked==true){
	  x.elements[i].checked=false;
	}else{
	  x.elements[i].checked=true;
	}        
      }
    }
  }
}
function js_marcaautoriza(valor){
  x = document.form1;
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=='checkbox'){
      splitei = x.elements[i].value.split("_");      
      if(x.elements[i].disabled==false){
	if(splitei[1]==valor){
	  if(x.elements[i].checked==true){
	    x.elements[i].checked=false;
	  }else{
	    x.elements[i].checked=true;
	  }
	}
      }
    }
  }
}
</script>
</body>
</html>
<?



if (isset ($incluir)) {
	if ($sqlerro == false) {
		//    db_msgbox("Usuário: \\n\\n$contaautori autorizações geradas com sucesso.\\n\\nAdministrador:");
		echo "<script>js_relatorio();</script>";
	} else
		if ($sqlerro == true) {
			db_msgbox($erro_msg);
		}
}
if ($locationh == true) {
	if ($numrows_itens > 0 && !isset ($incluir)) {
		db_msgbox("Usuário: \\n\\nItens deste processo de compras já incluídos em autorizações.\\n\\nAdministrador:");
	}
	echo "<script>parent.document.form1.voltar.click();</script>";
}
?>