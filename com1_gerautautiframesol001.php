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
include ("classes/db_solicita_classe.php");
include ("classes/db_solicitem_classe.php");
include ("classes/db_pcsugforn_classe.php");
include ("classes/db_pcorcam_classe.php");
include ("classes/db_pcorcamitem_classe.php");
include ("classes/db_pcorcamitemsol_classe.php");
include ("classes/db_pcorcamitemproc_classe.php");
include ("classes/db_pcorcamtroca_classe.php");
include ("classes/db_pcorcamforne_classe.php");
include ("classes/db_pcorcamjulg_classe.php");
include ("classes/db_pcorcamval_classe.php");
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
require_once("classes/db_empautitempcprocitem_classe.php");

$pc10_resumo = null;
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clsolicita             = new cl_solicita;
$clsolicitem            = new cl_solicitem;
$clpcsugforn            = new cl_pcsugforn;
$clpcorcam              = new cl_pcorcam;
$clpcorcamitem          = new cl_pcorcamitem;
$clpcorcamitemsol       = new cl_pcorcamitemsol;
$clpcorcamitemproc      = new cl_pcorcamitemproc;
$clpcorcamtroca         = new cl_pcorcamtroca;
$clpcorcamforne         = new cl_pcorcamforne;
$clpcorcamjulg          = new cl_pcorcamjulg;
$clpcorcamval           = new cl_pcorcamval;
$clorcreserva           = new cl_orcreserva;
$clorcreservasol        = new cl_orcreservasol;
$clorcreservaaut        = new cl_orcreservaaut;
$clempautoriza          = new cl_empautoriza;
$clempautitem           = new cl_empautitem;
$clempautidot           = new cl_empautidot;
$clpcparam              = new cl_pcparam;
$clpcdotac              = new cl_pcdotac;
$clpcprocitem           = new cl_pcprocitem;
$clpcproc               = new cl_pcproc;
$clpcsubgrupo           = new cl_pcsubgrupo;
$clempautitempcprocitem = new cl_empautitempcprocitem;
$clrotulo               = new rotulocampo;

$clempautoriza->rotulo->label();
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_resumo");
$clrotulo->label("pc40_numcgm");
$clrotulo->label("e54_codcom");
$clrotulo->label("e54_codtipo");
$clrotulo->label("e54_destin");
$clrotulo->label("pc20_codorc");
$clrotulo->label("e54_numerl");
$clrotulo->label("e54_tipol");
$db_opcao = 1;
$db_botao = true;
$gerautori = "";

if (isset($pc10_resumo) && trim($pc10_resumo) != ""){
  $pc10_resumo=urldecode(stripslashes($pc10_resumo));     
}

if (isset ($incluir)) {

  $pc10_resumo = addslashes(stripslashes(chop($pc10_resumo)));

	if (isset ($pc10_numero) && trim($pc10_numero) != "") {
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

		$arr_soli = Array ();
		$arr_orci = Array ();
		$arr_forn = Array ();

		$sqlerro = false;
		$clpcproc->pc80_data     = date("Y-m-d", db_getsession("DB_datausu"));
		$clpcproc->pc80_usuario  = db_getsession("DB_id_usuario");
		$clpcproc->pc80_depto    = db_getsession("DB_coddepto");
	  $clpcproc->pc80_resumo   = addslashes(stripslashes(chop($pc10_resumo)));
	  $clpcproc->pc80_situacao = 2;

		$clpcproc->incluir(null);
    
		$pc80_codproc = $clpcproc->pc80_codproc;
		$erro_msg = $clpcproc->erro_msg;
		if ($clpcproc->erro_status == 0) {
			$sqlerro = true;
		}

		if ($sqlerro == false) {
			$result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_horas,pc30_dias"));
			db_fieldsmemory($result_pcparam, 0);
			$clpcorcam->pc20_dtate = date("Y-m-d", mktime(0, 0, 0, date("m", db_getsession("DB_datausu")), date("d", db_getsession("DB_datausu")) + $pc30_dias, date("Y", db_getsession("DB_datausu"))));
			$clpcorcam->pc20_hrate = $pc30_horas;
			$clpcorcam->incluir(null);
			$pc22_codorc = $clpcorcam->pc20_codorc;
			$erro_msg = $clpcorcam->erro_msg;
			if ($clpcorcam->erro_status == 0) {
				$sqlerro = true;
			}
			if ($sqlerro == false && isset ($pc20_codorc) && trim($pc20_codorc) != "") {
				$result_fornecedores = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_file(null, "pc21_numcgm", "", "pc21_codorc=$pc20_codorc"));
				$numrows_orcamforn = $clpcorcamforne->numrows;
				if ($clpcorcamforne->numrows > 0) {
					for ($i = 0; $i < $numrows_orcamforn; $i ++) {
						db_fieldsmemory($result_fornecedores, $i);
						$clpcorcamforne->pc21_codorc = $pc22_codorc;
						$clpcorcamforne->pc21_numcgm = $pc21_numcgm;
						$clpcorcamforne->pc21_importado = 't';
						$clpcorcamforne->incluir(null);
						$arr_forn[$pc21_numcgm] = $clpcorcamforne->pc21_orcamforne;
						$erro_msg = $clpcorcamforne->erro_msg;
						if ($clpcorcamforne->erro_status == 0) {
							$sqlerro = true;
						}
					}
				} else {
					$pc20_codorc = "";
				}
			}
		}
		if ($sqlerro == false) {
			$result_itenssol  = $clsolicitem->sql_record($clsolicitem->sql_query_file(null, "distinct pc11_codigo, (pc11_quant*pc11_vlrun) as valor_sol", "", " pc11_numero=$pc10_numero "));
			$numrows_itenssol = $clsolicitem->numrows;
			for ($i = 0; $i < $numrows_itenssol; $i ++) {
				db_fieldsmemory($result_itenssol, $i);
        if ($valor_sol == 0){
             $sqlerro  = true;
             $erro_msg = "Existem itens da solicitacao ".$pc10_numero." que estao com valor aprox. zerado.";
             break;
        }
				$clpcprocitem->pc81_codproc = $pc80_codproc;
				$clpcprocitem->pc81_solicitem = $pc11_codigo;
				$clpcprocitem->incluir(null);
				$pc81_codprocitem = $clpcprocitem->pc81_codprocitem;
				$erro_msg = $clpcprocitem->erro_msg;
				if ($clpcprocitem->erro_status == 0) {
					$sqlerro = true;
					break;
				}

				$clpcorcamitem->pc22_codorc = $pc22_codorc;
				$clpcorcamitem->incluir(null);
				$pc29_orcamitem = $clpcorcamitem->pc22_orcamitem;
				$arr_orci[$pc11_codigo] = $pc29_orcamitem;
				$erro_msg = $clpcorcamitem->erro_msg;
				if ($clpcorcamitem->erro_status == 0) {
					$sqlerro = true;
					break;
				}

				$clpcorcamitemproc->incluir($pc29_orcamitem, $pc81_codprocitem);
				$erro_msg = $clpcorcamitemproc->erro_msg;
				if ($clpcorcamitemproc->erro_status == 0) {
					$sqlerro = true;
					break;
				}
			}
			if ($sqlerro == false) {
				//$result_itensinclui = $clsolicitem->sql_record($clsolicitem->sql_query_rel(null, " distinct pc11_codigo as index,pc13_valor,pc13_quant,pc11_vlrun as valorunitarioincluir", "", " pc11_numero=$pc10_numero "));
				$result_itensinclui = $clsolicitem->sql_record($clsolicitem->sql_query_rel(null, " distinct pc11_codigo as index,(pc11_quant*pc11_vlrun) as valor_sol,pc11_quant,pc11_vlrun as valorunitarioincluir", "", " pc11_numero=$pc10_numero "));
				$numrows_itensinclui = $clsolicitem->numrows;
				if (trim($pc20_codorc) != "") {
					$result_orcamforne = $clpcorcamval->sql_record($clpcorcamval->sql_query_importa(null, null, "pc21_numcgm,pc29_solicitem,pc23_valor,pc23_quant,pc23_obs,pc23_orcamforne,pc23_vlrun as valorunitarioincluir", "pc22_orcamitem", " pc22_codorc=$pc20_codorc "));
					$numrows_orcamforne = $clpcorcamval->numrows;
					for ($i = 0; $i < $numrows_orcamforne; $i ++) {
						db_fieldsmemory($result_orcamforne, $i);
						$clpcorcamval->pc23_vlrun = $valorunitarioincluir;
						$clpcorcamval->pc23_valor = $pc23_valor;
						$clpcorcamval->pc23_quant = $pc23_quant;
						$clpcorcamval->pc23_obs = addslashes(stripslashes($pc23_obs));
						$clpcorcamval->incluir($arr_forn[$pc21_numcgm], $arr_orci[$pc29_solicitem]);
						$erro_msg = $clpcorcamval->erro_msg;
						if ($clpcorcamval->erro_status == 0) {
							$sqlerro = true;
						}
					}

					if ($sqlerro == false) {
						$result_orcamforne = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null, null, "pc21_numcgm,pc29_solicitem,pc24_pontuacao,pc21_orcamforne", "pc22_orcamitem", " pc22_codorc=$pc20_codorc "));
						$numrows_orcamforne = $clpcorcamjulg->numrows;
						for ($i = 0; $i < $numrows_orcamforne; $i ++) {
							db_fieldsmemory($result_orcamforne, $i);
							$clpcorcamjulg->pc24_pontuacao = $pc24_pontuacao;
							$clpcorcamjulg->incluir($arr_orci[$pc29_solicitem], $arr_forn[$pc21_numcgm]);
							$erro_msg = $clpcorcamjulg->erro_msg;
							if ($clpcorcamjulg->erro_status == 0) {
								$sqlerro = true;
							}
						}
					}
					if ($sqlerro == false) {
						$arr_parcount = array_keys($arr_orci);
						for ($countinicio = 0; $countinicio < sizeof($arr_parcount); $countinicio ++) {
							$result_orcamtroca = $clpcorcamtroca->sql_record($clpcorcamtroca->sql_query_file(null, "pc25_motivo,pc25_orcamitem,pc25_forneant,pc25_forneatu", "", " pc11_codigo = ".$arr_parcount[$countinicio]));
							$numrows_orcamtroca = $clpcorcamtroca->numrows;
							for ($i = 0; $i < $numrows_orcamtroca; $i ++) {
								db_fieldsmemory($result_orcamtroca, $i);
								$clpcorcamtroca->pc25_orcamitem = $arr_orci[$arr_parcount[$countinicio]];
								$clpcorcamtroca->pc25_motivo = $pc25_motivo;

                if (trim(@$pc25_forneant)==""){
                     $clpcorcamtroca->pc25_forneant = $clpcorcamforne->pc21_orcamforne;
                } else {
                     $clpcorcamtroca->pc25_forneant = $pc25_forneant;
                }
                
                if (trim(@$pc25_forneatu)==""){
                     $clpcorcamtroca->pc25_forneatu = $clpcorcamforne->pc21_orcamforne;
                } else {
                     $clpcorcamtroca->pc25_forneatu = $pc25_forneatu;
                }

								$clpcorcamtroca->incluir(null);
								$orcamforninclui = $clpcorcamforne->pc21_orcamforne;
								$erro_msg = $clpcorcamforne->erro_msg;
								if ($clpcorcamforne->erro_status == 0) {
									$sqlerro = true;
								}
							}
						}
					}
				} else {
					if (isset ($pc40_numcgm)) {
						$clpcorcamforne->pc21_codorc = $pc22_codorc;
						$clpcorcamforne->pc21_numcgm = $pc40_numcgm;
						$clpcorcamforne->pc21_importado = 't';
						$clpcorcamforne->incluir(null);
						$orcamforninclui = $clpcorcamforne->pc21_orcamforne;
						$erro_msg = $clpcorcamforne->erro_msg;
						if ($clpcorcamforne->erro_status == 0) {
							$sqlerro = true;
						}
					}
					if ($sqlerro == false) {
						for ($i = 0; $i < $numrows_itensinclui; $i ++) {
							db_fieldsmemory($result_itensinclui, $i);
				      $clpcorcamval->pc23_vlrun = $valorunitarioincluir;
							$clpcorcamval->pc23_valor = $valor_sol;
						//	$clpcorcamval->pc23_valor = $pc13_valor;
							$clpcorcamval->pc23_quant = $pc11_quant;
					  //	$clpcorcamval->pc23_quant = $pc13_quant;
							$clpcorcamval->pc23_obs = " ";
							$clpcorcamval->incluir($orcamforninclui, $arr_orci[$index]);
							$erro_msg = $clpcorcamval->erro_msg;
							if ($clpcorcamval->erro_status == 0) {
								$sqlerro = true;
							}
							if ($sqlerro == false) {
								$clpcorcamjulg->pc24_pontuacao = '1';
								$clpcorcamjulg->incluir($arr_orci[$index], $orcamforninclui);
								$erro_msg = $clpcorcamjulg->erro_msg;
								if ($clpcorcamjulg->erro_status == 0) {
									$sqlerro = true;
								}
							}
						}
					}
				}
			}
		}
		$contadorARR_Ant = "";

		$arr_inclusaoAUTs = Array ();
		$arr_inclusaoAUTd = Array ();
		$arr_inclusaoAUTf = Array ();
		$arr_inclusaoITM  = Array ();
		$arr_inclusaoDOT  = Array ();
		$arr_contincITEM  = Array ();
		$cont_autori = 0;
		$vir = "";
		if ($sqlerro == false && isset ($pc22_codorc) && trim($pc22_codorc) != "") {
			
			$arr_recebe = split(",", $valores);
			
			for ($i = 0; $i < sizeof($arr_recebe); $i ++) {
				
				$splitARR        = split("_", $arr_recebe[$i]);
				$contadorARR     = $splitARR[1];
				$pc11_codigo     = $splitARR[2];
				$pc13_coddot     = $splitARR[3];
				$pcfornecdor     = $splitARR[4];
				$pc13_sequencial = $splitARR[5];
				if ($contadorARR_Ant != $contadorARR) {
					$cont_autori ++;
					$contadorARR_Ant = $contadorARR;
				}
				if (!isset ($arr_inclusaoAUTs[$cont_autori])) {
					$arr_contincITEM[$cont_autori]  = 0;
					$arr_inclusaoAUTs[$cont_autori] = $pc11_codigo;
					$arr_inclusaoAUTd[$cont_autori] = $pc13_coddot;
					$arr_inclusaoAUTf[$cont_autori] = $pcfornecdor;
					$aDotacoes[$cont_autori]        = $pc13_sequencial;
					$aSeqDotacoes[$cont_autori]     = $pc13_sequencial;
					$vir = ",";
				} else {
				  
					$arr_inclusaoAUTs[$cont_autori] .= $vir.$pc11_codigo;
					$arr_inclusaoAUTd[$cont_autori] = $pc13_coddot;
					$arr_inclusaoAUTf[$cont_autori] = $pcfornecdor;
					$aDotacoes[$cont_autori]        = $pc13_sequencial;
					$aSeqDotacoes[$cont_autori]    .= $vir.$pc13_sequencial;
			}
		}
		
		for ($i = 0; $i < $cont_autori; $i ++) {
			$result_somatorio = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_geraut(
			                                     null,
			                                     null,
			                                     'sum(pc13_valor) as somatorio_dotac,
			                                      sum(pc23_valor) as somatorio_orcamval',
			                                      "",
			                                      " pc11_codigo in (".$arr_inclusaoAUTs[$i +1].")
			                                        and pc13_sequencial in (".$aSeqDotacoes[$i +1].")
			                                       and pc80_codproc=$pc80_codproc
			                                       and pc24_pontuacao=1"));
			                                       

			                                       
			$numrows_somatorio = $clpcorcamjulg->numrows;
			if ($numrows_somatorio > 0) {
				db_fieldsmemory($result_somatorio, 0);

				if(isset($somatorio_dotac) && trim($somatorio_dotac) != ""){
				 $somatorio = $somatorio_dotac;	
				}else{
				 $somatorio = $somatorio_orcamval;
				}
				
				$e55_sequen = 1;
				$e54_login  = db_getsession("DB_id_usuario");
				$e54_anousu = db_getsession("DB_anousu");
				$e54_emiss  = date("Y-m-d", db_getsession("DB_datausu"));
				$e54_instit = db_getsession("DB_instit");
				$clempautoriza->e54_numcgm = $arr_inclusaoAUTf[$i +1];
				$clempautoriza->e54_login = $e54_login;
				$clempautoriza->e54_codcom = $e54_codcom;
				$clempautoriza->e54_destin = addslashes(stripslashes($e54_destin));
				$clempautoriza->e54_valor = $somatorio;
				$clempautoriza->e54_anousu = $e54_anousu;
				$clempautoriza->e54_tipol = $e54_tipol;
				$clempautoriza->e54_numerl = addslashes(stripslashes($e54_numerl));
				$clempautoriza->e54_praent = addslashes(stripslashes($e54_praent));
				$clempautoriza->e54_entpar = addslashes(stripslashes($e54_entpar));
				$clempautoriza->e54_conpag = addslashes(stripslashes($e54_conpag));
				$clempautoriza->e54_codout = addslashes(stripslashes($e54_codout));
				$clempautoriza->e54_contat = addslashes(stripslashes($e54_contat));
				$clempautoriza->e54_telef  = addslashes(stripslashes($e54_telef));
				$clempautoriza->e54_numsol = 0;
				$clempautoriza->e54_anulad = "null";
				$clempautoriza->e54_emiss = $e54_emiss;
				$clempautoriza->e54_resumo = addslashes(stripslashes(chop($pc10_resumo)));
				$clempautoriza->e54_codtipo = $e54_codtipo;
				$clempautoriza->e54_instit = $e54_instit;
				$clempautoriza->e54_depto = db_getsession("DB_coddepto");
        $clempautoriza->e54_concarpeculiar = "000";

				$clempautoriza->incluir(null);

				$e54_autori = $clempautoriza->e54_autori;

				if ($i == 0) {
					$gerautori .= "e54_autori_ini=$e54_autori";
				}
				if (($i +1) == $cont_autori) {
					$gerautori .= "&e54_autori_fim=$e54_autori";
				}
				$erro_msg = $clempautoriza->erro_msg;
				if ($clempautoriza->erro_status == 0) {
					$sqlerro = true;
					break;
				}

				if ($sqlerro == false) {
					$result_anousu = $clpcdotac->sql_record($clpcdotac->sql_query_descrdot(
					                                        null,
					                                        null,
					                                        null,
					                                        "pc13_anousu as anousu,pc13_coddot as dotacao,
					                                        pc19_orctiporec",
					                                        "",
					                                        "pc13_sequencial =  (".$aDotacoes[$i +1].")
					                                         and pc13_coddot=".$arr_inclusaoAUTd[$i +1]));
					if ($clpcdotac->numrows > 0) {
						db_fieldsmemory($result_anousu, 0);
						$clempautidot->e56_autori = $e54_autori;
						$clempautidot->e56_anousu = $anousu;
						$clempautidot->e56_coddot = $dotacao;
						if ($pc19_orctiporec != '') {
						  
						  $clempautidot->e56_orctiporec = $pc19_orctiporec; 
						} else {
						  $clempautidot->e56_orctiporec = "null";
						}
						$clempautidot->incluir($e54_autori);
						
						if ($clempautidot->erro_status == 0) {
							$erro_msg = $clempautidot->erro_msg;
							$sqlerro = true;
							break;
						}
					}
				}
				
				$result_excluireserva = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(
				                                                                       null,
				                                                                       null,
				                                                                       "o80_codres,
				                                                                        o82_sequencial", "",
				                                                                        "pc13_codigo in (".$arr_inclusaoAUTs[$i +1].")"));
				$numrows_reservas = $clorcreservasol->numrows;
				if ($numrows_reservas > 0) {
					for ($reservas = 0; $reservas < $numrows_reservas; $reservas ++) {
						db_fieldsmemory($result_excluireserva, $reservas);
						$clorcreservasol->excluir($o82_sequencial);
						if ($clorcreservasol->erro_status == 0) {
							$erro_msg = $clorcreservasol->erro_msg;
							$sqlerro = true;
							break;
						}
						if ($sqlerro == false) {
							$clorcreserva->excluir($o80_codres);
							if ($clorcreserva->erro_status == 0) {
								$erro_msg = $clorcreserva->erro_msg;
								$sqlerro = true;
								break;
							}
						}
					}
				}
				
				//====================================================//
				//rotina que verifica se ainda existe saldo disponivel//
				//=========rotina para calcular o saldo final=========//
				$result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$dotacao", $anousu);
				db_fieldsmemory($result, 0, true);
				
				$verificarsaldo = (0 + $atual_menos_reservado);
				$dif = $somatorio;
				if (round($verificarsaldo,2) < round($dif,2)) {
					$erro_msg = "Usuário:\\n\\nDotação (".$dotacao.") sem saldo. Reserva não não gerada. \\nAutorizações não geradas\\n\\nAministrador:";
					$sqlerro = true;
					break;
				}
				if ($sqlerro == false) {
					$clorcreserva->o80_anousu = $anousu;
					$clorcreserva->o80_coddot = $dotacao;
					$clorcreserva->o80_dtfim = date('Y', db_getsession('DB_datausu'))."-12-31";
					$clorcreserva->o80_dtini = date('Y-m-d', db_getsession('DB_datausu'));
					$clorcreserva->o80_dtlanc = date('Y-m-d', db_getsession('DB_datausu'));
					$clorcreserva->o80_valor = $somatorio;
					$clorcreserva->o80_descr = " ";
					$clorcreserva->incluir(null);
					$o80_codres = $clorcreserva->o80_codres;
					
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
						}
					}
				}
			}
			
			$arr_comitens = split(",", $aSeqDotacoes[$i +1]);
			if ($sqlerro == true) {
				$arr_comitens = Array ();
			}
			
			for ($ii = 0; $ii < sizeof($arr_comitens); $ii ++) {
				
				if(!isset($arr_comitens[$ii]) || $arr_comitens[$ii] == ''){
					continue;
				}
				$sCampos        = " pc16_codmater as codmater,           ";
				$sCampos       .= " pc81_codprocitem as procitem,        ";
				$sCampos       .= " 'aaa'  as teste,                     ";
				$sCampos       .= " pc13_quant as dotquant,              ";
				$sCampos       .= " pc23_valor as vallanc,               ";
				$sCampos       .= " pc23_quant as quantlanc,             ";
				$sCampos       .= " pc23_vlrun as valorunitarioautitem,  ";
				$sCampos       .= " pc11_resum as resumsol,              ";
				$sCampos       .= " pc23_obs as observ,                  ";
				$sCampos       .= " pc18_codele as codele,               ";
				$sCampos       .= " pc01_servico,pc17_unid,              ";
				$sCampos       .= " pc17_quant,                          ";
				$sCampos       .= " m61_descr,                           ";
				$sCampos       .= " pc13_valor as valdot,                ";
				$sCampos       .= " (pc13_valor/pc13_quant) as vlunit,   ";
				$sCampos       .= " m61_usaquant, pc11_servicoquantidade ";
				$sSqlGeraAutori = $clpcorcamjulg->sql_query_geraut( null, null, $sCampos, "pc11_codigo",
				                                                   "pc13_sequencial=".$arr_comitens[$ii]."
				                                                    and pc24_pontuacao=1");
				
				$result_incluiitens = $clpcorcamjulg->sql_record($sSqlGeraAutori);
				
				if ($clpcorcamjulg->numrows > 0) {
				   
					db_fieldsmemory($result_incluiitens, 0);
					$clempautitem->e55_autori             = $e54_autori;
					$clempautitem->e55_item               = $codmater;
					$clempautitem->e55_sequen             = $e55_sequen;
					$clempautitem->e55_quant              = $dotquant;
					if(isset($valdot) && trim($valdot) != ""){
						$vltot = $valdot;
					}else{
						$vltot = $vlunit;
					}
					if ((isset ($pc01_servico) && (trim($pc01_servico) == "f" || trim($pc01_servico) == "")) || !isset ($pc01_servico)) {
						$unid = trim(substr($m61_descr, 0, 10));
						if ($m61_usaquant == "t") {
							$unid .= " ($pc17_quant UNIDADES)";
						}
						$descr = $unid."\\n";
						$vluni = $vlunit;
					} else {
						$unid = "SERVIÇO";
						$descr = $unid."\\n";
						if(isset($valdot) && trim($valdot) != ""){
							$vluni = $valdot;
						}else{
							$vluni = $vlunit;
						}
					}
					//echo "vltot: $vltot  vluni: $vluni";	
					$clempautitem->e55_vltot              = $vltot;
					$clempautitem->e55_vlrun              = $vluni;
					$clempautitem->e55_descr              = $descr.addslashes(stripslashes($resumsol))."\n".$observ;
					$clempautitem->e55_codele             = $codele;
					
					$lControlaQuantidade = $pc11_servicoquantidade == 't' ? true : false;
					
					$clempautitem->e55_servicoquantidade  = "$lControlaQuantidade";
					$clempautitem->incluir($e54_autori, $e55_sequen);
					if ($clempautitem->erro_status == 0) {
						$erro_msg = $clempautitem->erro_msg;
						$sqlerro = true;
						break;
					}
					
					
				  $clempautitempcprocitem->e73_autori     = $clempautitem->e55_autori;
          $clempautitempcprocitem->e73_sequen     = $e55_sequen;
          $clempautitempcprocitem->e73_pcprocitem = $procitem;
          $clempautitempcprocitem->incluir(null);
          if ($clempautitempcprocitem->erro_status == 0) {
            $erro_msg = $clempautitempcprocitem->erro_msg;
            $sqlerro = true;
            break;
          }					
				}
				
				$e55_sequen++;
			}			
		}
	}
	
		if (isset($gerautori) && trim(@$gerautori) == ""){
      $erro_msg = "Erro ao gerar autorização. Verifique!";
      $sqlerro=true;
    }
		//db_msgbox($gerautori);
        //$sqlerro=true;
		db_fim_transacao($sqlerro);
	}
}

$numrows_itens = 0;
$ninclui = false;
$issetorc = false;
if (isset ($pc10_numero) && trim($pc10_numero) != "") {
  //retirado o pc10_resumo do sql.
	$result_maxorcam = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query_solicitem(null, null, "max(pc22_codorc) as pcorcam,pc10_numero,pc10_data,descrdepto", "", " pc11_numero=$pc10_numero  and pc81_solicitem is null and pc10_instit=".db_getsession("DB_instit")."  group by pc10_numero,pc10_data,descrdepto"));
	if ($clpcorcamitemsol->numrows > 0) {
		db_fieldsmemory($result_maxorcam, 0);
		$pc20_codorc = $pcorcam;
		if (trim($pcorcam) != "") {
			$issetorc = true;
			$result_itensorcamento = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query_dotac(null, 
			                                                       null,
			                                                       "pc11_seq,pc11_codigo,
			                                                        pc11_resum,
			                                                        pc11_vlrun as valor,
			                                                        pc01_codmater,
			                                                        pc01_servico,
			                                                        pc01_descrmater,
			                                                        z01_nome as nomfornec,
			                                                        pc13_coddot,
			                                                        pc13_quant,
			                                                        pc13_valor,
			                                                        pc13_sequencial,
			                                                        pc19_orctiporec,
			                                                        pc23_quant as quantidade1,
			                                                        pc23_obs as observ,
			                                                        pc23_valor as valor1,
			                                                        z01_numcgm as fornecedor,
			                                                        pc18_codele",
			                                                        "z01_numcgm,pc13_coddot,pc18_codele,pc19_orctiporec",
			                                                        "pc10_numero={$pc10_numero}
			                                                        and pc20_codorc={$pcorcam} and pc24_pontuacao=1"));
			                                                        
			if ($clpcorcamitemsol->numrows > 0) {
				$numrows_itens = $clpcorcamitemsol->numrows;
				$result_final = $result_itensorcamento;
			} else {
				$ninclui = true;
			}
		}
	}
}

if ($issetorc == false) {
  //retidado retirado o pc10_resumo do sql
	$result_contforn = $clpcsugforn->sql_record($clpcsugforn->sql_query(null, 
	                                                                    null, 
	                                                                    "distinct pc40_solic,
	                                                                    pc40_numcgm,
	                                                                    pc10_numero,
	                                                                    pc10_data,
	                                                                    descrdepto",
	                                                                     "", 
	                                                                     " pc11_numero=$pc10_numero
	                                                                     and pc81_solicitem is null 
	                                                                     and pc10_instit=".db_getsession("DB_instit")));
	                                                                
	if ($clpcsugforn->numrows != 1) {
		$ninclui = true;
	} else {
		db_fieldsmemory($result_contforn, 0);
		$result_itensorcamento = $clsolicitem->sql_record($clsolicitem->sql_query_rel(null,
		                         "distinct pc11_seq,pc11_codigo,pc01_codmater,
		                          pc01_servico,pc11_resum,pc01_descrmater,
		                          z01_nome as nomfornec,
		                          pc13_sequencial,
		                          pc13_coddot,pc13_quant,pc19_orctiporec,
		                          pc13_valor,pc11_quant as quantidade,pc11_vlrun as valor,
		                          z01_numcgm as fornecedor,
		                          pc11_resum as observ,pc18_codele",
		                          "z01_numcgm,pc18_codele,pc13_coddot,pc19_orctiporec",
		                          " pc11_numero=$pc10_numero and pc81_solicitem is null and pc10_instit=".db_getsession("DB_instit")));
		
		
		if ($clsolicitem->numrows > 0) {
			$numrows_itens = $clsolicitem->numrows;
			$result_final = $result_itensorcamento;
		} else {
			$ninclui = true;
		}
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
<form name="form1" method='post'>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?


db_input('valores', 8, 0, true, 'hidden', 3);
if ($ninclui == true) {
	echo "                                                                                                                                                                                                                                                                                   <br><br><br><br><br><br><br>
	            <strong>Orçamento de solicitação com valores não lançados ou não existem itens,<BR> nesta solicitação, para gerar autorização.</strong>\n
	            <script>
		      parent.document.form1.incluir.disabled=true;
	            </script>
	           ";
} else {
	db_input('e54_praent',30,$Ie54_entpar,true,'hidden',3,"");
	db_input('e54_entpar',30,$Ie54_entpar,true,'hidden',3,"");
	db_input('e54_conpag',30,$Ie54_conpag,true,'hidden',3,"");
	db_input('e54_codout',30,$Ie54_codout,true,'hidden',3,"");
	db_input('e54_contat',20,$Ie54_contat,true,'hidden',3,"");
	db_input('e54_telef',20,$Ie54_telef,true,'hidden',3,"");
	db_input('pc10_numero', 8, $Ipc10_numero, true, 'hidden', 3);
    ?><textarea name="pc10_resumo" style="visibility: hidden"><?=addslashes(stripslashes(chop($pc10_resumo)))?></textarea><?	
	db_input('pc20_codorc', 8, $Ipc20_codorc, true, 'hidden', 3);
	db_input('e54_codcom', 8, $Ie54_codcom, true, 'hidden', 3);
	db_input('e54_codtipo', 8, $Ie54_codtipo, true, 'hidden', 3);
	db_input('e54_destin', 8, $Ie54_destin, true, 'hidden', 3);
	db_input('e54_numerl', 8, $Ie54_numerl, true, 'hidden', 3);
	db_input('e54_tipol', 8, $Ie54_tipol, true, 'hidden', 3);
	if (isset ($pc40_numcgm) && trim($pc40_numcgm) != "") {
		db_input('pc40_numcgm', 8, $Ipc40_numcgm, true, 'hidden', 3);
	}
	echo "<center>";
	echo "<table border='1' align='center'>\n";
	echo "<tr>";
	echo "  <td nowrap class='bordas02' colspan='2' align='center'><strong>Solicitação</strong></td>";
	echo "  <td nowrap class='bordas02' colspan='1' align='center'><strong>Data</strong></td>";
	echo "  <td nowrap class='bordas02' colspan='3' align='center'><strong>Departamento</strong></td>";
	echo "  <td nowrap class='bordas02' colspan='5' align='center'><strong>Resumo</strong></td>";
	echo "</tr>";
	echo "<tr>";
	echo "  <td nowrap class='bordas' colspan='2' align='center'><strong>$pc10_numero</strong></td>";
	echo "  <td nowrap class='bordas' colspan='1' align='center'><strong>".db_formatar($pc10_data, 'd')."</strong></td>";
	echo "  <td class='bordas' colspan='3'><strong>$descrdepto</strong></td>";
	echo "  <td class='bordas' colspan='5'><strong>".stripslashes($pc10_resumo)."</strong>&nbsp;</td>";
	echo "</tr>";
	echo "<tr>\n";
	echo "  <td nowrap colspan='11'align='left'><strong>&nbsp;</strong></td>\n";
	echo "<tr>\n";
	echo "<tr bgcolor=''>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Item</strong></td>\n";
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

	$outraautori = "";
	$dot_ant = "";
	$sContrant = '' ;//contrapartida
	$forn_ant = "";
	$proxcontra = "";
	$codele_ant = "";
	$contador = 1;

	for ($i = 0; $i < $numrows_itens; $i ++) {
		db_fieldsmemory($result_final, $i);

		if (trim($pc13_coddot) == "")
			continue;

		if ($i < $numrows_itens -1) {
			$proxitem   = pg_result($result_final, $i +1, "pc11_codigo");
			$proxdotac  = pg_result($result_final, $i +1, "pc13_coddot");
			$proxcontra = pg_result($result_final, $i +1, "pc19_orctiporec");
			if ($proxitem == $pc11_codigo && $proxdotac == $pc13_coddot && $proxcontra == $pc19_orctiporec) {
				continue;
			}
		}

		if (!isset ($saldodotacoes[$pc13_coddot])) {
			$saldodotacoes[$pc13_coddot] = 0;
		}

		$result_altexttest = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(
		                                                  null,
		                                                  null,
		                                                  "o80_codres,o80_valor",
		                                                  "",
		                                                  "o82_pcdotac = {$pc13_sequencial}"));
		if ($clorcreservasol->numrows > 0) {
			db_fieldsmemory($result_altexttest, 0, true);
			$saldodotacoes[$pc13_coddot] += $o80_valor;
		}
	}

	for ($i = 0; $i < $numrows_itens; $i ++) {
		db_fieldsmemory($result_final, $i);

		if (trim($pc13_coddot) == "")
			continue;

		if ($i < $numrows_itens -1) {
			$proxitem = pg_result($result_final, $i +1, "pc11_codigo");
			$proxdotac = pg_result($result_final, $i +1, "pc13_coddot");
			$proxcontra = pg_result($result_final, $i +1, "pc19_orctiporec");
			if ($proxitem == $pc11_codigo && $proxdotac == $pc13_coddot && $proxcontra == $pc19_orctiporec)
				continue;
		}

		//====================================================//
		//rotina que verifica se ainda existe saldo disponivel//
		//=========rotina para calcular o saldo final=========//
		$result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$pc13_coddot", db_getsession("DB_anousu"));
		db_fieldsmemory($result, 0, true);
		
		$valdisp = 'valdisp_'.$contador;
		$$valdisp = (0 + $atual_menos_reservado) + $saldodotacoes[$pc13_coddot];

		if (($codele_ant != $pc18_codele) or ($dot_ant != $pc13_coddot)) {
			$testatot = 0;
		}
/*
    echo $dot_ant." => ".$pc13_coddot."<br>";
    echo $forn_ant." => ".$fornecedor."<br>";
    echo $codele_ant." => ".$pc18_codele."<br>";
    echo $contador."<br>";
*/    

		if (($codele_ant != $pc18_codele) or ($dot_ant != $pc13_coddot || $forn_ant != $fornecedor)
		    || ($dot_ant == $pc13_coddot and $sContrant != $pc19_orctiporec)) {
		      
			if ($contador != 1) {
				echo "<tr>\n";
				echo "  <td nowrap colspan='11'align='left'><strong>&nbsp;</strong></td>\n";
				echo "<tr>\n";
			}
			//          $$valdisp = db_formatar($$valdisp,"v");
			echo "</tr>\n";
			echo "  <td nowrap colspan='5' class='bordas' align='left'><strong>$contador&ordf; AUTORIZAÇÃO DA SOLICITAÇÃO $pc10_numero</strong></td>\n";
			echo "  <td nowrap colspan='6' class='bordas' align='left'><strong>Saldo disponível: </strong>";
			db_input('valdisp_'.$contador, 12, 0, true, 'text', 3);
			echo "  </td>\n";
			echo "</tr>\n";
			$dot_ant = $pc13_coddot;
			$forn_ant = $fornecedor;
			$codele_ant = $pc18_codele;
			$sContrant   = $pc19_orctiporec;
			$contador++;
		}

		$simnao = "Não";
		$simnaod = "disabled";
		$bordas = "bordas01";
		if ($$valdisp >= 0) {
			$result_altext = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(
			                                              null, 
			                                              null,
			                                              "o80_codres,o80_valor",
			                                              "",
			                                              "o82_pcdotac = {$pc13_sequencial}"));
			$altcoddot = false;
			if (isset ($valor) && isset ($quantidade)) {
				$testatot = $valor * $pc13_quant;
			} else {
				$testatot = $valor1 / $quantidade1 * $pc13_quant;
			}
			if ($clorcreservasol->numrows > 0) {
				db_fieldsmemory($result_altext, 0, true);
				$$valdisp += $o80_valor;

				if (isset ($valor) && isset ($quantidade)) {
					if ($o80_valor < $valor * $pc13_quant) {
						$testatot = $valor * $pc13_quant - $o80_valor;
					}
				} else {
					if ($o80_valor < $valor1 / $quantidade1 * $pc13_quant) {
						$testatot = $valor1 / $quantidade1 * $pc13_quant - $o80_valor;
					}
				}
			}
			if ($$valdisp >= $testatot) {
				$altcoddot = true;
				$simnao = "Sim";
				$simnaod = "";
				$bordas = "bordas";
			}
		}

		echo "<tr>\n";
		echo "  <td nowrap class='$bordas' align='center' >$pc11_codigo</td>\n";
		echo "  <td nowrap class='$bordas' align='center' >$pc01_codmater</td>\n";
		echo "  <td class='$bordas' align='left' >".ucfirst(strtolower($pc01_descrmater))."</td>\n";
		echo "  <td class='$bordas' align='left' >".stripslashes($observ)."&nbsp;</td>\n";
		echo "  <td class='$bordas' align='left' >$nomfornec</td>\n";
		echo "  <td nowrap class='$bordas' align='center' >$pc13_coddot</td>\n";
		echo "  <td nowrap class='$bordas' align='right'  >$pc13_quant</td>\n";
		//if (isset ($valor) && isset ($quantidade)) {
		if (isset($valor1)) {
			if(isset($pc01_servico) && $pc01_servico == "t"){
				echo "  <td nowrap class='$bordas' align='right'  >R$ ".db_formatar($valor1, "f")."</td>\n";
			}else{
				echo "  <td nowrap class='$bordas' align='right'  >R$ ".db_formatar(@$valor1, "f")."</td>\n";
			}
			echo "  <td nowrap class='$bordas' align='center' >R$ ".db_formatar($valor1, "f")."</td>\n";
		} else {
			echo "  <td nowrap class='$bordas' align='right'  >R$ ".db_formatar($valor1 / $quantidade1, "f")."</td>\n";
			echo "  <td nowrap class='$bordas' align='center' >R$ ".db_formatar($valor1 / $quantidade1 * $pc11_quant, "f")."</td>\n";
		}
		echo "  <td nowrap class='$bordas' align='center' ><strong>$simnao</strong></td>\n";
		echo "</tr>\n";
		echo "<input type='checkbox' name='aut_". ($contador -1)."_".$pc11_codigo."_".$pc13_coddot."_".$fornecedor."_{$pc13_sequencial}'
		      value='aut_". ($contador -1)."_".$pc11_codigo."_".$pc13_coddot."_".$fornecedor."_{$pc13_sequencial}' 
		      style='visibility:hidden;' checked>\n";
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
  parent.location.href = 'com1_selproc001.php';
}
</script>
</body>
</html>
<?


if (isset ($incluir)) {
	if ($sqlerro == false) {
		//    db_msgbox($erro_msg);
		//    db_msgbox("Usuário: \\n\\n$contaautori autorizações geradas com sucesso.\\n\\nAdministrador:");
		echo "<script>js_relatorio();</script>";
	} else
		if ($sqlerro == true) {
			db_msgbox($erro_msg);
		}
}
?>