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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_solicitem_classe.php"));
require_once(modification("classes/db_pcprocitem_classe.php"));
require_once(modification("classes/db_pcparam_classe.php"));
require_once(modification("classes/db_pcdotac_classe.php"));
require_once(modification("classes/db_solandam_classe.php"));
require_once(modification("classes/db_solandpadraodepto_classe.php"));
require_once(modification("classes/db_solicitemprot_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clsolicitem         = new cl_solicitem;
$clpcprocitem        = new cl_pcprocitem;
$clpcparam           = new cl_pcparam;
$clpcdotac           = new cl_pcdotac;
$clsolandam          = new cl_solandam;
$clsolandpadraodepto = new cl_solandpadraodepto;
$clsolicitemprot     = new cl_solicitemprot;
$clempparametro      = new cl_empparametro;
$db_botao            = true;
$db_opcao            = 1;
if(isset($solicita) && trim($solicita)!=""){
	// die($clsolicitem->sql_query_pcmater(null,"pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc11_liberado","pc11_codigo","pc11_numero=$solicita"));

	$sSqlSolicitem = $clsolicitem->sql_query_pcmater(null,"distinct pc11_numero,pc11_codigo,m61_descr, pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc11_liberado","pc11_codigo","pc11_numero=$solicita");

	$select_itens = $clsolicitem->sql_record($sSqlSolicitem);
}
$result_casadec = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_numdec as casadec"));
if($clempparametro->numrows > 0){
	db_fieldsmemory($result_casadec,0);
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
<form name="form1">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td align="left" valign="top" bgcolor="#CCCCCC">
				<center>
					<?
					if($clsolicitem->numrows==0){
						if(isset($solicita) && trim($solicita)!=""){
							echo "<strong><BR><BR><BR>Não existem itens para esta solicitação.</strong>\n";
						}else{
							echo "<strong><BR><BR><BR>Solicitação não informada.</strong>\n";
						}
					}else{
						$result_libera = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_liberaitem,pc30_libdotac,pc30_contrandsol"));
						db_fieldsmemory($result_libera,0);

						echo "<center>";


						echo "<fieldset style='width:800px;'>
            <legend><strong>Itens da Solicitação</strong>
             <table border='1' align='center'>\n  ";
						$arr_disabled = array();
						$index = 0;
						for($i=0;$i<$clsolicitem->numrows;$i++){
							db_fieldsmemory($select_itens,$i);
							$ok       = "OK";
							$readonly = "  ";
							$bordas   = "bordas";
							// ?Controle do andamento das solicitações
							if($pc30_contrandsol=='t'){
								$result_prot = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file(null,"*",null,"pc49_solicitem = $pc11_codigo"));
								if ($clsolicitemprot->numrows>0){
									$result_andam=$clsolandam->sql_record($clsolandam->sql_query_file(null,"*","pc43_codigo desc","pc43_solicitem=$pc11_codigo"));
									if ($clsolandam->numrows>0){
										db_fieldsmemory($result_andam,0);
										$result_tipo=$clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null,"*",null,"pc47_solicitem=$pc11_codigo and pc47_ordem=$pc43_ordem"));
										if($clsolandpadraodepto->numrows>0){
											db_fieldsmemory($result_tipo,0);
											if ($pc47_pctipoandam!=2||$pc48_depto!=db_getsession("DB_coddepto")){
												$sqltran = "select distinct x.p62_codtran,
      x.pc11_numero,
x.pc11_codigo,
                            x.p62_dttran,
                            x.p62_hora,
                			x.descrdepto,
							x.login
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

                        inner join solicitemprot on pc49_protprocesso = proctransferproc.p63_codproc
                        inner join solicitem on pc49_solicitem = pc11_codigo
                        inner join proctransfer on p63_codtran = p62_codtran
						inner join db_depart on coddepto = p62_coddepto
						inner join db_usuarios on id_usuario = p62_id_usuario
						left join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo
						left join empautitem on empautitem.e55_sequen = pcprocitem.pc81_codprocitem
						left join empautoriza on empautoriza.e54_autori= empautitem.e55_autori
             			where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.pc11_codigo = $pc11_codigo";

												$result_tran=db_query($sqltran);
												if(pg_numrows($result_tran)==0){

													$readonly = " disabled ";
													//echo "<script>parent.document.form1.incluir.disabled=true;</script>";
												}

											}
										}
									}else{

										//echo "<script>parent.document.form1.incluir.disabled=true;</script>";
										$readonly = " disabled ";
									}
									/*testa c esta em transferencia*/
									$result_transf = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_transf(null,"*",null,"pc49_solicitem = $pc11_codigo and p64_codtran is null"));
									if ($clsolicitemprot->numrows>0){
										$sqltran = "select distinct x.p62_codtran,
      x.pc11_numero,
x.pc11_codigo,
                            x.p62_dttran,
                            x.p62_hora,
                			x.descrdepto,
							x.login
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

                        inner join solicitemprot on pc49_protprocesso = proctransferproc.p63_codproc
                        inner join solicitem on pc49_solicitem = pc11_codigo
                        inner join proctransfer on p63_codtran = p62_codtran
						inner join db_depart on coddepto = p62_coddepto
						inner join db_usuarios on id_usuario = p62_id_usuario
						left join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo
						left join empautitem on empautitem.e55_sequen = pcprocitem.pc81_codprocitem
						left join empautoriza on empautoriza.e54_autori= empautitem.e55_autori
             			where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and p68_codproc is null and x.pc11_codigo = $pc11_codigo";

										$result_tran=db_query($sqltran);
										if(pg_numrows($result_tran)==0){
											$readonly = " disabled ";
											//echo "<script>parent.document.form1.incluir.disabled=true;</script>";
										}

									} else {

										// Verifica caso o item não esteja em transferencia se está no depto da sessão
										$rsConsultaDeptoAtual = $clsolandam->sql_record($clsolandam->sql_query_file(null,"pc43_depto","pc43_codigo desc limit 1","pc43_solicitem=$pc11_codigo"));
										if ($clsolandam->numrows > 0){
											$oDeptoAtual = db_utils::fieldsMemory($rsConsultaDeptoAtual,0);
											if ($oDeptoAtual->pc43_depto != db_getsession("DB_coddepto")) {
												$readonly = " disabled ";
											}
										}

									}

								}
							}

							$result_procitem = $clpcprocitem->sql_record($clpcprocitem->sql_query_file(null,"pc81_codproc,pc81_codprocitem","","pc81_solicitem=$pc11_codigo"));
							if(!isset($pc01_descrmater) || (isset($pc01_descrmater) && trim($pc01_descrmater)=='')){
								$ok       = "Sel";
							}
							if(((!isset($pc01_descrmater) || (isset($pc01_descrmater) && trim($pc01_descrmater)=='')) && $pc30_liberaitem=='f') || ($clpcprocitem->numrows>0)){
								$arr_disabled[$index]="item_".$pc11_numero."_".$pc11_codigo;
								$readonly = " disabled ";
								$bordas   = "bordas01";
								$checked  = "  ";
								$index++;
							}
							if($pc30_libdotac=='f'){
								$result_dotacao = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc11_codigo,null,null,"pc13_coddot"));
								if($clpcdotac->numrows==0){
									$readonly = " disabled ";
									$bordas   = "bordas01";
								}
							}
							if(isset($pc11_liberado) && $pc11_liberado=="t"){
								$checked  = " checked ";
								$readonly = " disabled ";
								$bordas   = "bordas01";
							}else{
								$checked = "  ";
							}
							unset($pc11_liberado);

							if($i==0){

								//echo "<tr>";
								//echo "  <td colspan='11' nowrap><strong><font size='3'>Itens da solicitação</font></strong></td>";
								//echo "</tr>";
								echo "<tr bgcolor=''>\n";
								echo "  <td nowrap colspan='12' class='bordas02' align='left'>";
								echo "    <strong>Para ver dotações clique em 'Ver', na coluna das dotações, no item desejado.</strong>";
								if($pc30_libdotac=='f'){
									echo "<strong>Itens sem dotação não poderão sem liberados.</strong>";
								}
								echo "<br>";
								if($pc30_liberaitem == 'f'){
									echo "    <strong>Para poder liberar itens desabilitados selecione o tipo de material clicando em 'Sel', no item desejado.<br></strong>";
								}
								echo "      <strong>OBS.: itens incluídos em processo de compras, não podem ser alterados.</strong>";
								echo "    </td>\n";
								echo "  </tr>\n";
								echo "  <tr>";
								echo "  <td nowrap class='bordas02' align='center'><strong>";db_ancora('M','js_marcar();',1);echo"</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Material</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Sequencial</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Item</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Material</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Unidade</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Quantidade</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Valor Unit.</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Valor Tot.</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Codigo</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Resumo</strong></td>\n";
								echo "  <td nowrap class='bordas02' align='center'><strong>Dotações</strong></td>\n";
								echo "</tr>\n";
							}
							echo "<tr>\n";
							echo "  <td nowrap class='$bordas' ><input type='checkbox' ".$readonly.$checked." name='item_".$pc11_numero."_".$pc11_codigo."'></td>\n";
							echo "  <td nowrap class='$bordas' align='center' ".($ok!="OK"?" title='Clique para selecionar o tipo de material' ":" title='Tipo material selecionado. Item pode ser liberado' ").">";db_ancora($ok,"js_selmater($pc11_codigo,$pc11_numero);",($ok=="OK"?"3":"1"));echo"</td>\n";
							echo "  <td nowrap class='$bordas' align='center' >$pc11_seq</td>\n";
							echo "  <td nowrap class='$bordas' align='center' >$pc11_codigo</td>\n";
							echo "  <td class='$bordas' align='left' >  ".ucfirst(strtolower($pc01_descrmater))."</td>\n";
							echo "  <td class='$bordas' align='left' >  ".ucfirst(strtolower($m61_descr))."</td>\n";
							echo "  <td nowrap class='$bordas' align='center' >$pc11_quant</td>\n";
							echo "  <td nowrap class='$bordas' align='right' >R$ ".db_formatar($pc11_vlrun,'v'," ",$casadec)."</td>\n";
							echo "  <td nowrap class='$bordas' align='right' >R$ ".db_formatar(($pc11_vlrun*$pc11_quant),"f")."</td>\n";
							echo "  <td nowrap class='$bordas' align='center' >$pc01_codmater</td>\n";
							if(isset($pc11_resum) && trim($pc11_resum)==""){
								$pc11_resum="&nbsp;";
							}
							echo "  <td class='$bordas' align='left'>".substr($pc11_resum,0,40)."</td>\n";
							echo "  <td nowrap class='$bordas' align='center' title='Ver dotações com este item foi incluído.'>";db_ancora("Ver","js_verdotac($pc11_codigo,'$pc01_codmater','$pc11_numero');",1);echo"</td>\n";
							echo "</tr>\n";
						}
						echo "</table>\n";

						echo "</fieldset>";

						echo "</center>";
					}
					?>
				</center>
			</td>
		</tr>
	</table>


</form>
<script>
	function js_selmater(codigo,solicita){
		qry = 'pc16_solicitem='+codigo;
		qry+= '&pc10_numero='+solicita;
		qry+= '&libera=true';
		js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_selmater','com1_selmater001.php?'+qry,'Consulta',true,'20');
		// (window.CurrentWindow || parent.CurrentWindow).corpo.document.location.href = 'com1_selmater001.php?'+qry;
	}
	function js_verdotac(codigo,mater,numero){
		js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_dotac','com1_seldotac001.php?consulta=true&pc13_codigo='+codigo+'&pc16_codmater='+mater+'&numero='+numero,'Consulta',true,'20');
		// (window.CurrentWindow || parent.CurrentWindow).corpo.document.location.href = 'com1_seldotac001.php?pc13_codigo='+codigo+'&pc16_codmater='+mater+'&numero='+numero;
	}
	function js_marcar(){
		cont = 0;
		for(i=0;i<document.form1.length;i++){
			if(document.form1.elements[i].type == 'checkbox'){
				if(document.form1.elements[i].disabled==false){
					if(document.form1.elements[i].checked == true){
						document.form1.elements[i].checked = false;
					}else{
						document.form1.elements[i].checked = true;
					}
				}
			}
		}
	}
	/*
	 function js_marcacampos(){
	 for(i=0;i<document.form1.length;i++){
	 if(document.form1.elements[i].type == 'checkbox' && 1==2){
	 if((window.CurrentWindow || parent.CurrentWindow).corpo.document.form1.valores.value.search(document.form1.elements[i].name)!=-1){
	 document.form1.elements[i].checked = true;
	 }else{
	 document.form1.elements[i].checked = false;
	 }
	 }
	 }
	 }
	 js_marcacampos();
	 */
</script>
</body>
</html>