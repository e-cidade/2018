<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_jsplibwebseller.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clprontuarios = new cl_prontuarios_ext;
$clprontproced = new cl_prontproced;
$clprontcid    = new cl_prontcid;
$clunidades    = new cl_unidades_ext;
$clcgs_und     = new cl_cgs_und;
$clsau_config  = new cl_sau_config_ext;

$db_opcao = $idarq == 2 ? 22 : 1;
$db_botao = true;

//Sau_Config
$resSau_config = $clsau_config->sql_record($clsau_config->sql_query_ext());
$objSau_config = db_utils::fieldsMemory($resSau_config,0 );

$sd24_i_login   = DB_getsession("DB_id_usuario");
$login          = DB_getsession("DB_login");
$sd24_i_unidade = !isset($sd24_i_unidade)?DB_getsession("DB_coddepto"):$sd24_i_unidade;

//Pesquisas
if (isset($chavepesquisaprontuario)&&(int)$chavepesquisaprontuario != 0){

	$db_opcao = 2;
	$result= $clprontuarios->sql_record($clprontuarios->sql_query_nolote_ext(null,"m.z01_nome as profissional_triagem, rhcbo.rh70_descr as cbo_triagem,  sau_lotepront.* ",null,"sd24_i_codigo = $chavepesquisaprontuario"));


	if( $clprontuarios->numrows > 0 ){

		$obj_prontuario = db_utils::fieldsMemory($result, 0);

		if( $obj_prontuario->sd59_i_prontuario != "" ){

			db_msgbox("Impossivel alteracao de FAA incluida via Lote [{$obj_prontuario->sd59_i_lote}] .");
			$sd24_i_codigo = null;
			db_redireciona("sau1_sau_individual001.php?idarq=".$idarq);
		}
	}

	$result = $clprontuarios->sql_record($clprontuarios->sql_query_ext($chavepesquisaprontuario));
	if( $clprontuarios->numrows > 0){
		db_fieldsmemory($result,0);
		$result = $clprontcid->sql_record($clprontcid->sql_query("","prontcid.*, sau_cid.*", ""," sd55_i_prontuario = $chavepesquisaprontuario "));
		if( $clprontcid->numrows > 0){
			db_fieldsmemory($result,0);
		}
	}else{
	   	db_msgbox('FAA não localizada.');
	   	db_redireciona("sau1_sau_individual001.php?idarq=".$idarq);
	}
}
if(isset($chavepesquisacgs)&&(int)$chavepesquisacgs != 0){
   $result = $clcgs_und->sql_record($clcgs_und->sql_query($chavepesquisacgs));
   db_fieldsmemory($result,0);
}


if(isset($incluir) || isset($alterar)){

	if( !isset($sd24_i_codigo) || (int)$sd24_i_codigo == 0 ){
		// Prontuário
		//gera numatend
		$sql_fc    = "select fc_numatend()";
		$query_fc  = db_query($sql_fc) or die(pg_errormessage().$sql_fc);
		$fc_numatend = explode(",",pg_result($query_fc,0,0));
	}

	db_inicio_transacao();

	//Prontuario
	$clprontuarios->sd24_i_unidade     = $sd24_i_unidadedescr;
	$clprontuarios->sd24_i_numcgs      = $z01_i_cgsund;
	$clprontuarios->sd24_t_diagnostico = $sd24_t_diagnostico;
	if( (int)$sd24_i_codigo == 0 ){
		$clprontuarios->sd24_i_ano      = trim($fc_numatend[0]);
		$clprontuarios->sd24_i_mes      = trim($fc_numatend[1]);
		$clprontuarios->sd24_i_seq      = trim($fc_numatend[2]);
		$clprontuarios->sd24_i_unidade  = $sd24_i_unidadedescr;
		$clprontuarios->sd24_i_numcgs   = $z01_i_cgsund;
		$clprontuarios->sd24_d_cadastro = date("Y-m-d",db_getsession("DB_datausu"));
		$clprontuarios->sd24_c_cadastro = db_hora();
		$clprontuarios->sd24_i_login    = DB_getsession("DB_id_usuario");

		$clprontuarios->incluir(null);
		$sd24_i_codigo = $clprontuarios->sd24_i_codigo;
	}else{

		$clprontuarios->sd24_i_codigo = $sd24_i_codigo;
		$clprontuarios->alterar($sd24_i_codigo);
	}

	db_fim_transacao();

	if($retorno){

		echo "<script> parent.document.formaba.a2.disabled = false;</script>";
		echo "<script> parent.mo_camada('a2');</script>";
		echo "<script>parent.iframe_a2.location.href='sau1_sau_individualproced001.php?idarq=$idarq&tmp_table=true&sd24_i_codigo=$sd24_i_codigo'</script>";
	}
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
	<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
	<link href="estilos.css" rel="stylesheet" type="text/css">
	<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    	<?php
    	 include("forms/db_frmsau_individual.php");
    	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
function js_sair(){
	if(confirm("Deseja realmente sair?")){
		<?php
		if (isset($_SESSION["objRegistros"])) {
			unset($_SESSION["objRegistros"]);
		}
		?>
		js_retorna();
	}
}
function js_retorna(){
	parent.document.formaba.a1.disabled = true;
	parent.document.formaba.a2.disabled = true;
	parent.iframe_a1.location.href='sau1_sau_individual001.php?idarq=1';
	parent.mo_camada('a1');
}

<?
if(isset($chavepesquisacgs)&&(int)$chavepesquisacgs != 0){
	echo "<script>
		focoInclusao   = $('sd70_c_cid');
		js_tabulacaoforms(\"form1\",\"sd70_c_cid\",true,1,\"sd24_t_diagnostico\",true);
		</script>";
	}else if(isset($chavepesquisaprontuario)&&(int)$chavepesquisaprontuario != 0){
	echo "document.form1.z01_i_cgsund.focus()";
}else if(isset($campoFocado) && $campoFocado != "" ) {
	?>
	js_tabulacaoforms("form1","sd24_i_unidade",false,1,"sd24_t_diagnostico",true);
	var evlTmp = "document.form1.<?=$campoFocado?>.focus();";
	eval( evlTmp );
	<?
}else{
	?>
	js_tabulacaoforms("form1","sd24_i_unidade",false,1,"sd24_t_diagnostico",true);
	if( $('sd24_i_unidade').readOnly ){
		$('sd24_i_codigo').focus();
		$('sd24_i_codigo').select();
		focoInclusao   = $('sd24_i_codigo');
		$('campoFocado').value = 'sd24_i_codigo';
	}else{
		$('sd24_i_unidade').focus();
		$('sd24_i_unidade').select();
	}
	<?
}
?>
</script>
<?
if(isset($incluir) || isset($alterar) ){
	if($clprontuarios->erro_status=="0"){
		$clprontuarios->erro(true,false);
		$db_botao=true;
	    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
	    if($clprontuarios->erro_campo!=""){
	    	echo "<script> document.form1.".$clprontuarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
	    	echo "<script> document.form1.".$clprontuarios->erro_campo.".focus();</script>";
	    }
	}else{
		if($clprontcid->erro_status=="0"){
			$clprontcid->erro(true,false);
			$db_botao=true;
			echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
			if($clprontcid->erro_campo!=""){
				echo "<script> document.form1.".$clprontcid->erro_campo.".style.backgroundColor='#99A9AE';</script>";
				echo "<script> document.form1.".$clprontcid->erro_campo.".focus();</script>";
			}
		}else{
			if($clcgs_und->erro_status=="0"){
				$clcgs_und->erro(true,false);
				$db_botao=true;
				echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
				if($clcgs_und->erro_campo!=""){
					echo "<script> document.form1.".$clcgs_und->erro_campo.".style.backgroundColor='#99A9AE';</script>";
					echo "<script> document.form1.".$clcgs_und->erro_campo.".focus();</script>";
				}
			}else{
				$clprontuarios->erro(false,false);
				db_redireciona("sau1_sau_individual001.php?idarq=$idarq&db_botao=false&chavepesquisaprontuario=$sd24_i_codigo&db_opcao=2" );
			}
		}
	}
}

if($db_opcao==22 ){
  echo "<script>document.form1.pesquisarfaa.click();</script>";
}
?>
