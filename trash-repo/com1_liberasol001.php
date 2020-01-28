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
require_once("libs/db_liborcamento.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_solicita_classe.php");
require_once("classes/db_solicitem_classe.php");
require_once("classes/db_pcdotac_classe.php");
require_once("classes/db_pcprocitem_classe.php");
require_once("classes/db_pcproc_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_proctransfer_classe.php");
require_once("classes/db_proctransand_classe.php");
require_once("classes/db_proctransferproc_classe.php");
require_once("classes/db_solicitemprot_classe.php");
require_once("classes/db_solandam_classe.php");
require_once("classes/db_solandamand_classe.php");
require_once("classes/db_solandpadraodepto_classe.php");
require_once("classes/db_orcreserva_classe.php");
require_once("classes/db_orcreservasol_classe.php");
require_once("classes/db_solordemtransf_classe.php");
require_once("classes/db_procandam_classe.php");
require_once("libs/db_sql.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
//db_postmemory($HTTP_POST_VARS,2);db_postmemory($HTTP_GET_VARS,2);
$clpcparam           = new cl_pcparam;
$clsolicita          = new cl_solicita;
$clsolicitem         = new cl_solicitem;
$clpcdotac           = new cl_pcdotac;
$clpcprocitem        = new cl_pcprocitem;
$clpcprocitem1       = new cl_pcprocitem;
$clpcproc            = new cl_pcproc;
$clproctransferproc  = new cl_proctransferproc;
$clproctransfer      = new cl_proctransfer;
$clsolicitemprot     = new cl_solicitemprot;
$clsolandam          = new cl_solandam;
$clsolandpadraodepto = new cl_solandpadraodepto;
$clorcreserva        = new cl_orcreserva;
$clorcreservasol     = new cl_orcreservasol;
$clsolordemtransf    = new cl_solordemtransf;
$clprocandam         = new cl_procandam;
$db_botao            = true;
$db_opcao            = 1;
if (isset ($incluir)) {
	db_inicio_transacao();
	$result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_gerareserva,pc30_contrandsol"));
	db_fieldsmemory($result_pcparam, 0);
	$sqlerro = false;
	$arr_valores = split(",", $valores);
	if ($pc30_contrandsol=='t'){		 		
	  	 $sqltran = "select distinct x.p62_codtran                   
      
			from ( select distinct p62_codtran, 
                    p62_dttran, 
                    p63_codproc,                          
                    descrdepto, 
                    p62_hora, 
                    login,
                    pc11_numero,
							      pc11_codigo,
                    pc81_codproc,
                    e55_autori,
							      e54_anulad 
		           from proctransferproc                     
				            inner join solicitemprot        on pc49_protprocesso                   = proctransferproc.p63_codproc
				            inner join solicitem            on pc49_solicitem                      = pc11_codigo
				            inner join proctransfer         on p63_codtran                         = p62_codtran
										inner join db_depart            on coddepto                            = p62_coddepto
										inner join db_usuarios          on id_usuario                          = p62_id_usuario
										left join pcprocitem            on pcprocitem.pc81_solicitem           = solicitem.pc11_codigo
				            left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem    
				            left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori
				                                           and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen
										left join empautoriza           on empautoriza.e54_autori              = empautitem.e55_autori  
             			where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.pc11_numero = $pc10_numero";
			$result_tran=pg_exec($sqltran);
			if(pg_numrows($result_tran)!=0){				
				for($w=0;$w<pg_numrows($result_tran);$w++){					
					db_fieldsmemory($result_tran,$w);					
					$recebetransf=recprocandsol($p62_codtran);
					if ($recebetransf==true){
						$sqlerro=true;
						break;			
					}
				}
				
			}
	}
	/*
	$result_solicitem = $clsolicitem->sql_record($clsolicitem->sql_query_file(null, "distinct pc11_codigo as codigoaltera", "pc11_codigo", "pc11_numero=$pc10_numero"));
	$numrows_itenssolic = $clsolicitem->numrows;
	for ($i = 0; $i < $numrows_itenssolic; $i ++) {
		
		$clsolicitem->pc11_liberado = "false";
		$clsolicitem->pc11_codigo = $codigoaltera;
		$clsolicitem->alterar($codigoaltera);
		if ($clsolicitem->erro_status == 0) {
			$erro_msg = $clsolicitem->erro_msg;
			$sqlerro = true;
		}
	}
	*/
	if (trim($valores) != "") {
		
		for ($i = 0; $i < sizeof($arr_valores); $i ++) {
			$arr_item = split("_", $arr_valores[$i]);
			$codigo = $arr_item[2];
			$clsolicitem->pc11_liberado = "false";
			$clsolicitem->pc11_codigo = $codigo;
			$clsolicitem->alterar($codigo);
			if ($clsolicitem->erro_status == 0) {
				$erro_msg = $clsolicitem->erro_msg;
				$sqlerro = true;
		 	}			        
		}
		
		for ($i = 0; $i < sizeof($arr_valores); $i ++) {
			$arr_item = split("_", $arr_valores[$i]);
			$codigo = $arr_item[2];			        
			//// Controle do andamento da solicitação  
			if ($i == 0) {
				$result_proc = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($codigo));
				if ($clsolicitemprot->numrows > 0) {
					db_fieldsmemory($result_proc, 0);
					$result_ord = $clsolandam->sql_record($clsolandam->sql_query_file(null, "pc43_ordem as ordem", "pc43_codigo desc limit 1", "pc43_solicitem=".$codigo));
					if ($clsolandam->numrows > 0) {
						db_fieldsmemory($result_ord, 0);
						$ordem = $ordem +1;
						$result_deptorec = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem = ".$codigo."   and pc47_ordem = $ordem"));
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
								//	db_msgbox("erro proctransfer!!");
							} else {
								//db_msgbox("transf p/ $pc48_depto no proctransfer!!");
							}
						}
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
						//db_msgbox("erro proctransferproc!!");                  	
					} else {
						//db_msgbox("gravo o $codigo do proctransferproc!!");
					}
					$result_ord = $clsolandam->sql_record($clsolandam->sql_query_file(null, "pc43_ordem as ordem_transf", "pc43_codigo desc limit 1", "pc43_solicitem=".$codigo));
					if ($clsolandam->numrows > 0) {
						db_fieldsmemory($result_ord, 0);
						$ordem_transf=$ordem_transf+1;
						if ($sqlerro == false) {
							$clsolordemtransf->pc41_solicitem=$codigo;
							$clsolordemtransf->pc41_codtran=$codtran;
							$clsolordemtransf->pc41_ordem=$ordem_transf;
							$clsolordemtransf->incluir(null);
							if($clsolordemtransf->erro_status==0){
								$sqlerro=true;
								$erro_msg=$clsolordemtransf->erro_msg;
							}
						}
					}					
				}
			}
			if ($pc30_gerareserva == "t") {
				$result_vlrun = $clpcdotac->sql_record($clpcdotac->sql_query_file($codigo, null, null, "pc13_sequencial,pc13_quant,pc13_anousu,pc13_coddot,pc13_codigo,pc13_valor"));
				$numrows_pcdotac = $clpcdotac->numrows;

				for ($ix = 0; $ix < $numrows_pcdotac; $ix ++) {
					db_fieldsmemory($result_vlrun, $ix);

					$valor_da_reserva = $pc13_valor;
					$valor_do_somator = 0;

					if ($valor_da_reserva > 0) {

						$result_vlres = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(
						                                             null,
						                                             null,
						                                             "o80_codres,o80_valor",
						                                             "",
						                                             "o80_coddot = $pc13_coddot and pc13_codigo = $codigo"));
						echo "consulta:".pg_last_error();                                             
						$numrows_orcres = $clorcreservasol->numrows;

						if ($numrows_orcres > 0) {
							db_fieldsmemory($result_vlres, 0);
							$valor_do_somator = $o80_valor;
						}

						$result_saldatac = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$pc13_coddot", db_getsession("DB_anousu"));
						db_fieldsmemory($result_saldatac, 0);
						
						if (($valor_da_reserva - $valor_do_somator) >= $atual_menos_reservado) {
							$valor_da_reserva = $atual_menos_reservado;
						} else
							if (($valor_da_reserva - $valor_do_somator) < $atual_menos_reservado) {
								$valor_da_reserva = $valor_da_reserva - $valor_do_somator;
							}

						if ($valor_da_reserva > 0) {
							if (isset ($o80_codres)) {
								$clorcreserva->atualiza_valor($o80_codres, "(o80_valor + ".$valor_da_reserva.")");
							} else {
								$clorcreserva->o80_anousu = db_getsession("DB_anousu");
								$clorcreserva->o80_coddot = $pc13_coddot;
								$clorcreserva->o80_dtfim = date('Y', db_getsession('DB_datausu'))."-12-31";
								$clorcreserva->o80_dtini = date('Y-m-d', db_getsession('DB_datausu'));
								$clorcreserva->o80_dtlanc = date('Y-m-d', db_getsession('DB_datausu'));
								$clorcreserva->o80_valor = $valor_da_reserva;
								$clorcreserva->o80_descr = " ";
								$clorcreserva->incluir(null);
								$codreserva = $clorcreserva->o80_codres;
								if ($clorcreserva->erro_status == 0) {
									$erro_msg = $clorcreserva->erro_msg;
									$sqlerro = true;
								}
								if ($sqlerro == false) {
								    
								    $clorcreservasol->o82_codres    = $codreserva;
								    $clorcreservasol->o82_pcdotac   = $pc13_sequencial;
								    $clorcreservasol->o82_solicitem = $codigo;
									$clorcreservasol->incluir(null);
									if ($clorcreservasol->erro_status == 0) {
										$erro_msg = $clorcreservasol->erro_msg;
										$sqlerro = true;
									}
								}
							}
						}
					}
				}
			}
			$clsolicitem->pc11_liberado = "true";
			$clsolicitem->pc11_codigo = $codigo;
			$clsolicitem->alterar($codigo);
			$erro_msg = $clsolicitem->erro_msg;
			if ($clsolicitem->erro_status == 0) {
				$sqlerro = true;
			}
		}
	}
	/*
	if ($sqlerro==true){
	  db_msgbox("Erro!!");
	}else{
		 db_msgbox("feito!!");
	}
	exit;
	*/
	//	$sqlerro=true;
	db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script>
arr_dados = new Array();
<?


/*
$result_solicitem = $clpcprocitem->sql_record($clpcprocitem->sql_query(null,"pc11_numero,pc11_codigo,pc81_codprocitem,pc81_codproc"," pc11_numero desc,pc11_codigo desc "));
for($i=0;$i<$clpcprocitem->numrows;$i++){
  db_fieldsmemory($result_solicitem,$i,true);
  echo "arr_dados.unshift('item".$pc11_numero."_".$pc11_codigo."')";
}
*/
?>
</script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.pc10_numero.select();" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="450" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?


include ("forms/db_frmliberasol.php");
?>
    </center>
    </td>
  </tr>
</table>
</body>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</html>
<?


if (isset ($incluir)) {
	if ($clpcproc->erro_campo != "") {
		db_msgbox($erro_msg);
		echo "<script> document.form1.".$clsolicitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
		echo "<script> document.form1.".$clsolicitem->erro_campo.".focus();</script>";
	} else {
		db_msgbox("Itens da solicitação liberados.");
		//  	echo "<script>location.href='com1_liberasol001.php';</script>";
	}
}
/*
if(isset($solicita) && trim($solicita)!=""){
  echo "<script>location.href = 'com1_liberasol001.php?solicita=$codigo'</script>";
}
*/
if (!isset ($solicita) or isset($incluir)) {
	echo "<script>js_pesquisapc10_numero(true);</script>";
}
?>